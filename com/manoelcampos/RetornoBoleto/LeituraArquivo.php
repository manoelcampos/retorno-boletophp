<?php

namespace com\manoelcampos\RetornoBoleto;    

/** 
 * Classe que implementa o design pattern Strategy,
 * para leitura de arquivos de retorno de cobranças dos bancos brasileiros,
 * vincular uma classe para processamento de uma carteira específica
 * de arquivo de retorno, e criando uma interface única
 * para a execução do processamento do arquivo.
 * 
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="http://manoelcampos.com/contact">Manoel Campos da Silva Filho</a>
 * @version 1.0
 */
class LeituraArquivo {
    /** @property RetornoInterface $retorno Objeto de uma sub-classe de RetornoBase,
     * que implementa a leitura de arquivo de retorno para uma determinada carteira
     * de um banco específico. */
    private $retorno = NULL;

    /**
     * @propert function processarLinha(RetornoInterface, LinhaArquivo) $callback 
     * Uma função anônima (lambda) que será chamada cada vez que uma linha 
     * for lida do arquivo de retorno.  
     */
    private $callback = NULL;
    
    /** 
     * Construtor padrão.
     * 
     * @param function processarLinha(RetornoInterface, LinhaArquivo) $callback 
     * Uma função anônima (lambda) que será chamada cada vez que uma linha 
     * for lida do arquivo de retorno. Tal função definida
     * pelo usuário da classe deve ser responsável pelo processamento 
     * de fato da linha lida do arquivo. Por exemplo, tal função pode salvar
     * os dados da linha em um banco de dados, enviar um email a uma pessoa,
     * gerar um comprovante de pagamento, etc.<p/>
     * Tal função deve ter os seguintes parâmetros:
     * <ul> 
     *  <li>um objeto RetornoInterface que será a intância que de fato realizou
     * a leitura do arquivo de retorno</li>
     *  <li>um objeto LinhaArquivo que contém os dados da linha lida</li>
     * </ul>
     * <p/>
     * Para cada linha lida do arquivo, a função anônima passada
     * será chamada, recebendo os paâmetros acima. Desta forma,
     * o usuário desta classe terá todos os dados necessários
     * para processar a linha lida.
     * 
     * @param RetornoInterface $retorno Objeto que implementa a leitura de arquivo 
     * de retorno para uma determinada carteira de um banco específico.
     */
    public function __construct($callback, $retorno) {
        $this->callback = $callback;
        $this->retorno = $retorno;
    }

    /** 
     * Executa a leitura de todo o arquivo de retorno, linha a linha. 
     * 
     * @return int o total de linhas lidas
     */
    public function lerArquivoRetorno() {
        $total_linhas = 0;
        $linhas = file($this->retorno->getNomeArquivo());
        foreach ($linhas as $numLn => $linha) {
            /*É adicionado um espaço vazio no início_linha para que
            possamos trabalhar com índices iniciando_1, no lugar_zero,
            e assim, ter os valores_posição_campos exatamente
            como no manual dos arquivos de retorno*/
            $linha = " $linha";
            $objLinha = $this->retorno->lerLinhaArquivoRetorno($numLn, $linha);
            $this->notifyObserver($objLinha);
            $total_linhas++;
        }
        
        return $total_linhas;
    }
        
    public function notifyObserver(LinhaArquivo $objLinha) {
        if ($this->callback != NULL && is_callable($this->callback) && $objLinha != NULL) {
            $callback = $this->callback;
            $callback($this->retorno, $objLinha);
        }        
    }        
}