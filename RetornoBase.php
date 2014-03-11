<?php 
/**Classe base para leitura de arquivos de retorno de cobranças dos bancos brasileiros.<br/>
* @copyright GPLv2
* @package ArquivoRetornoTitulosBancarios
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.1
* @abstract
*/
class RetornoBase {
    /**@property string $nomeArquivo Nome do arquivo de texto a ser lido*/
    var $nomeArquivo = "";
	/**@property string $aoProcessarLinha Armazena o nome da função handler 
	* que será chamada após o processamento de cada linha do arquivo, com
	* isto, definindo um evento aoProcessarLinha.	*/
	var $aoProcessarLinha="";

	/**Construtor da classe.
	* @param string $nomeArquivo Nome do arquivo de retorno do banco.
	* @param string $aoProcessarLinhaFunctionName Nome da função handler a ser associada
	* ao evento aoProcessarLinha. Se for informado um valor ao parâmetro, a função, indicada por ele, 
	* será executada após cada linha ser processada no arquivo de retorno.
	*/
	function RetornoBase($nomeArquivo=NULL, $aoProcessarLinhaFunctionName="") {
			if(isset($nomeArquivo))
				$this->setNomeArquivo($nomeArquivo);
	   $this->setAoProcessarLinha($aoProcessarLinhaFunctionName);
	}
	
	/**Setter para o atributo @see nomeArquivo*/
	function setNomeArquivo($nomeArquivo) { $this->nomeArquivo = $nomeArquivo; }
	/**Getter para o atributo @see nomeArquivo*/
	function getNomeArquivo() { return $this->nomeArquivo; }

	/**
	* Processa uma linha do arquivo de retorno. O método é abstrato e deve ser implementado nas sub-classes.
  	* @param int $numLn Número da linha a ser processada
	* @param string $linha String contendo a linha a ser processada
	* @return array Retorna um vetor associativo contendo os valores da linha processada.
	* @abstract
	*/
	function processarLinha($numLn, $linha) {}
	
	/**Atribui uma função ao evento aoProcessarLinha.
	* @param string $handlerFunctionName String contendo o nome da função handler,
	* definida pelo usuário fora da classe, que será executada quando o evento aoProcessarLinha for disparado.
	* Esta função deve ter a assinatura funcao($numLn, $vlinha), onde
	* $numLn recebe o número da linha processada e $vlinha recebe um vetor associativo
	* contendos os valores da linha.
	* Nela o usuário pode fazer o que desejar com os parâmetro recebidos ($numLn e $vlinha),
	* como setar um campo em uma tabela do banco de dados, para indicar
	* o pagamento de um boleto de um determinado cliente.
	*/
	function setAoProcessarLinha($handlerFunctionName) {
		 $this->aoProcessarLinha = $handlerFunctionName;
  }

	/**Se existe uma função handler associadao ao evento aoProcessarLinha,
	* executa a mesma, disparando o evento.
	* @param int $numLn Número da linha processada.
	* @param array $vlinha Vetor contendo a linha processada, contendo os valores da armazenados
	* nas colunas deste vetor. Nesta função o usuário pode fazer o que desejar,
	* como setar um campo em uma tabela do banco de dados, para indicar
	* o pagamento de um boleto de um determinado cliente.
	* @see setAoProcessarLinha*/
	function triggerAoProcessarLinha($self, $numLn, $vlinha) {
		//Obtém o nome da função handler associada ao evento aoProcessarLinha
		$funcName = $this->aoProcessarLinha;
		//Se foi associada alguma função ao evento aoProcessarLinha e 
		//a função existe, executa a mesma, que obrigatoriamente
		//deve ter sido definida pelo usuário, fora da classe,
		//com a assinatura funcao($numLn, $vlinha).
		if ($funcName != "" and function_exists($funcName)) 
		   	   //chama a função handler, passando núm. da linha processada e um vetor com os valores da mesma
			   $funcName($self, $numLn, $vlinha); 
  }
	
	/**Formata uma string, contendo um valor real (float) sem o separador de decimais,
	* para a sua correta representação real.
	* @param string $valor String contendo o valor na representação
	* usada nos arquivos de retorno do banco, sem o separador de decimais.
	* @param int $numCasasDecimais Total de casas decimais do número
	* representado em $valor.
	* @return float Retorna o número representado em $valor, no seu formato float,
	* contendo o separador de decimais.*/
	function formataNumero($valor, $numCasasDecimais=2) {
	  if($valor=="")
		   return 0;
		$casas = $numCasasDecimais;
	  if($casas > 0) {
   		 $valor = substr($valor, 0, strlen($valor)-$casas) . "." . substr($valor, strlen($valor)-$casas, $casas);
    	 $valor = (float)$valor;
    } else $valor = (int)$valor;

	  return $valor;
	}

	/**Formata uma string, contendo uma data sem o separador, no formato DDMMAA,
	* para o formato DD/MM/AAAA.
	* @param string $data String contendo a data no formato DDMMAA.
	* @return string Retorna a data non formato DD/MM/AAAA.*/
	function formataData($data) {
	  if($data=="")
		   return "";
		//formata a data par ao padrão americano MM/DD/AA
		$data =  substr($data, 2, 2) . "/". substr($data, 0, 2) . "/" . substr($data, 4, 2);

		//formata a data, a partir do padrão americano, para o padrão DD/MM/AAAA
		return date("d/m/Y", strtotime($data));

	}
}

?>
