<?php

namespace ManoelCampos\RetornoBoleto;

require_once("RetornoCNAB240.php");
require_once("RetornoCNAB400.php");
require_once("RetornoCNAB400Abstract.php");
require_once("RetornoCNAB400Conv7.php");
require_once("RetornoCNAB400Bradesco.php");
require_once("RetornoCNAB240Sicoob.php");

/**
 * Classe que identifica o tipo de arquivo de retorno sendo carregado e
 * automaticamente instancia a classe específica para leitura do mesmo.
 *
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="http://manoelcampos.com/contact">Manoel Campos da Silva Filho</a>
 * @version 1.1
 */
class RetornoFactory {
    /**
     * @propert string $header Header do arquivo de retorno
     */
    private $header;

    /**
     * @propert string $fileName Nome do arquivo de retorno
     */
    private $fileName;

    /**
     * @propert FILE $arq Um ponteiro para o arquivo de retorno indicado por $fileName
     */
    private $arq;

    /**
     * Construtor padrão.
     *
     * @param string $fileName Nome do arquivo de retorno a ser identificado
     * para poder instancia a classe específica para leitura do mesmo.
     */
    private function __construct($fileName){
        if ($fileName == "") {
            throw new \Exception("Informe o nome do arquivo de retorno.");
        }

        $this->fileName = $fileName;
    }

    /**
     * Instancia um objeto de uma das sub-classes de RetornoBase,
     * com base no tipo do arquivo de retorno indicado por $fileName.
     *
     * @param string $fileName Nome do arquivo de retorno a ser identificado
     * para poder instancia a classe específica para leitura do mesmo.
     * @return Uma instância de uma subclasse de RetornoBase
     */
    public static function getRetorno($fileName) {
        $factory = new RetornoFactory($fileName);
        $factory->arq = fopen($fileName, "r");
        if ($factory->arq == NULL) {
            throw new \Exception("Não foi possível abrir o arquivo '$fileName'.");
        }

        try{
            $factory->header = fgets($factory->arq, 500);
            if ($factory->header == NULL) {
                throw new \Exception("Tipo de arquivo de retorno não identificado. Não foi possível ler o header do arquivo.");
            }

            return $factory->tentaInstanciarObjetoLeituraArquivoRetorno();
        } finally {
            fclose($factory->arq);
        }
    }

    /**
     * Tenta descobrir qual o tipo do arquivo de retorno e instancia
     * um objeto da classe responsável pra ler tal arquivo.
     *
     * @return \com\manoelcampos\RetornoBoleto\RetornoCNAB240
     */
    private function tentaInstanciarObjetoLeituraArquivoRetorno(){
        $retorno = $this->tentaInstanciarCnab240();
        if ($retorno != NULL) {
            return $retorno;
        }

        $retorno = $this->tentaInstanciarCnab400($this->arq);
        if ($retorno != NULL) {
            return $retorno;
        }

        throw new \Exception(
                "Tipo de arquivo de retorno não identificado. ".
                "Total de colunas do header: " . strlen($this->header));
    }

    /**
     * Tenta instanciar um objeto para leitura de arquivos CNAB240,
     * de acordo com a versão do arquivo de retorno.
     * @return RetornoCNAB240 Uma instância de uma subclasse de @see RetornoCNAB240
     * @throws \Exception
     */
    private function tentaInstanciarCnab240(){
        $retorno = new RetornoCNAB240($this->fileName);
        if (!$retorno->arquivoEstaNoFormato($this->header)) {
            return NULL;
        }

        if (strstr($this->header, "SICOOB")) {
            return new RetornoCNAB240Sicoob($this->fileName);
        }

        return $retorno;
    }

    /**
     * Tenta instanciar um objeto para leitura de arquivos CNAB400,
     * de acordo com a versão do arquivo de retorno.
     * @return RetornoCNAB400 Uma instância de uma subclasse de @see RetornoCNAB400
     * @throws \Exception
     */
    private function tentaInstanciarCnab400(){
        $retorno = new RetornoCNAB400($this->fileName);
        if (!$retorno->arquivoEstaNoFormato($this->header)) {
            return NULL;
        }

        if (strstr($this->header, "BRADESCO")) {
            return new RetornoCNAB400Bradesco($this->fileName);
        }

        //Lê o primeiro registro detalhe
        $linha_detalhe = fgets($this->arq, 500);
        if ($linha_detalhe == NULL) {
            throw new \Exception(
                "Tipo de arquivo de retorno não identificado. Não foi possível ler um registro detalhe.");
        }

        $retorno = new RetornoCNAB400($this->fileName);
        if($retorno->getIdDetalhe() == $linha_detalhe[0]) {
            return $retorno;
        }

        $retorno = new RetornoCNAB400Conv7($this->fileName);
        if($retorno->getIdDetalhe() == $linha_detalhe[0]) {
            return $retorno;
        }

        throw new \Exception("Tipo de registro detalhe desconhecido: " . $linha_detalhe[0]);
    }
}
