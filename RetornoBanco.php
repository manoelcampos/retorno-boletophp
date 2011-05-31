<?
/**Classe que implementa o design pattern Strategy,
* para leitura de arquivos de retorno de cobranças dos bancos brasileiros,
* vincular uma classe para processamento de uma carteira específica
* de arquivo de retorno, e criando uma interface única
* para a execução do processamento do arquivo.<br/>
* @copyright GPLv2
* @package ArquivoRetornoTitulosBancarios
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.1
*/
class RetornoBanco {
	/**@property RetornoBase $retorno 
  * Atributo que deve ser um objeto de uma classe que estenda a classe RetornoBase */
	public $retorno;

	/**Construtor da classe
	* @param RetornoBase $retorno Objeto de uma sub-classe de RetornoBase,
	* que implementa a leitura de arquivo de retorno para uma determinada carteira
	* de um banco específico.
	*/
	public function RetornoBanco($retorno) {
	 	$this->retorno=$retorno;
	}

	/**Executa o processamento de todo o arquivo, linha a linha.*/
	public function processar() {
		$linhas = file($this->retorno->getNomeArquivo());  
		foreach($linhas as $numLn => $linha) {
		   $vlinha = $this->retorno->processarLinha($numLn, $linha);
			 //Dispara o evento aoProcessarLinha, caso haja alguma função handler associada a ele.
			 //Esta função deve ser criada pelo usuário da classe, dentro da página
			 //que usará a mesma, sendo associada ao evento aoProcessarLinha, 
			 //no construtor da classe.
			 //Desta forma, o usuário da classe pode capturar os valores da linha processada
			 //e fazer o que desejar com eles (como salvar em um banco de dados).
			 $this->retorno->triggerAoProcessarLinha($this->retorno, $numLn, $vlinha);
		}
	}
}

?>
