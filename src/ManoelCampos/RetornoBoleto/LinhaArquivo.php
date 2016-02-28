<?php

namespace ManoelCampos\RetornoBoleto;

/**
 * Dados de uma linha lida de um arquivo de retorno de boleto bancário.
 * 
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="http://manoelcampos.com/contact">Manoel Campos da Silva Filho</a>
 * @version 1.1
 */
class LinhaArquivo {
    /** @property int Número da linha lida do arquivo. */
    public $numero;
    
    /** 
     * @property int Tipo da linha lida do arquivo,
     * conforme valores definidos no layout do arquivo de retorno.
     * Tal valor é uma constante inteira que define se a linha
     * é um header, trailer, linha detalhe, etc. 
     * Os valores específicos de tal atributo são definidos
     * nas classes que herdam de @see RetornoBase,
     * em métodos como getIdDetalhe().
     */
    public $tipo;
    
    /** @property array<mixed> Vetor associativo contendo os valores da linha lida do arquivo,
     * onde os índices do vetor são os nomes das colunas e o conteúdo de cada posição
     * é o valor da respectiva coluna da linha lida. */
    public $dados;
    
    public function __construct($numero=NULL, $tipo=NULL, $dados=NULL){
       $this->numero = $numero;
       $this->tipo = $tipo;
       $this->dados = $dados;
    }    
    
    /**
     * Seta os dados da linha.
     * 
     * @param array<mixed> $dados Vetor associativo contendo os valores da linha lida do arquivo,
     * onde os índices do vetor são os nomes das colunas e o conteúdo de cada posição
     * é o valor da respectiva coluna da linha lida.
     * @return LinhaArquivo retorna o próprio objeto LinhaArquivo
     */
    public function setDados($dados){
        $this->dados = $dados;
        return $this;
    }
}

