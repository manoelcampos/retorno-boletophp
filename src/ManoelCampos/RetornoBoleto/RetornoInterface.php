<?php

namespace ManoelCampos\RetornoBoleto;

/**
 * Interface a ser implementada por classes que realizam a leitura de arquivos 
 * de retorno de cobranças dos bancos brasileiros.
 * 
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="http://manoelcampos.com/contact">Manoel Campos da Silva Filho</a>
 * @version 1.1
 */
interface RetornoInterface {

    /** 
     * Setter para o atributo nomeArquivo 
     * @param string $nomeArquivo Nome do arquivo de retorno a ser lido
     */
    public function setNomeArquivo($nomeArquivo);

    /** 
     * Getter para o atributo nomeArquivo. 
     * @return string Nome do arquivo de retorno a ser lido
     */
    public function getNomeArquivo();
  
    /**
     * Verifica se um arquivo está no formato que a classe é capaz de manipular.
     * Tal verificação é feita apenas pelo número de colunas de uma linha do arquivo.
     * Tal método deve ser implementdo pelas sub-classes.
     * @param string $linha
     * @return boolean true se o arquivo está no formato, falso caso contrário
     */
    public function arquivoEstaNoFormato($linha);
    
     /** 
      * Define o valor que identifica uma coluna do tipo HEADER DE ARQUIVO.
      * @return int 
      */
    public function getIdHeaderArquivo();
    
    /** 
     * Define o valor que identifica uma coluna do tipo HEADER DE LOTE.
     * @return int 
     */
    public function getIdHeaderLote();
    
    /**
     *  Define o valor que identifica uma coluna do tipo DETALHE.
     * @return int 
     */
    public function getIdDetalhe();
    
    /** 
     * Define o valor que identifica uma coluna do tipo TRAILER DE LOTE.
     * @return int 
     */
    public function getIdTrailerLote();
    
    /** 
     * Define o valor que identifica uma coluna do tipo TRAILER DE ARQUIVO 
     * @return int 
     */
    public function getIdTrailerArquivo(); 
    
    /**
     * Obtém o total de caracteres que cada linha do arquivo de retorno possui.
     * @return int
     */
    public function getTotalCaracteresPorLinha();

    /** 
     * Lê uma linha do arquivo de retorno.
     * 
     * @param int $numLn Número da linha a ser processada
     * @param string $linhaIniciandoComEspaco String contendo a linha a ser processada
     * @return LinhaArquivo um objeto contendo os dados da linha lida ou 
     * NULL caso a linha não tenha sido identificada.
     */
    public function lerLinhaArquivoRetorno($numLn, $linhaIniciandoComEspaco);
}