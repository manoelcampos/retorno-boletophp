<?php 
/**Exemplo de uso da classe para processamento de arquivo de retorno de cobranças em formato FEBRABAN/CNAB400,
* testado com arquivo de retorno do Banco do Brasil com convênio de 7 posições.<br/>
* @copyright GPLv2
* @package LeituraArquivoRetorno
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.4
*/

//Adiciona a classe strategy RetornoBanco que vincula um objeto de uma sub-classe
//de RetornoBase, e assim, executa o processamento do arquivo de uma determinada
//carteira de um banco específico.
require_once("RetornoBanco.php");
require_once("RetornoFactory.php");

/**Função handler a ser associada ao evento aoProcessarLinha de um objeto da classe
* RetornoBase. A função será chamada cada vez que o evento for disparado.
* @param RetornoBase $self Objeto da classe RetornoBase que está processando o arquivo de retorno
* @param $numLn Número da linha processada.
* @param $vlinha Vetor contendo a linha processada, contendo os valores da armazenados
* nas colunas deste vetor. Nesta função o usuário pode fazer o que desejar,
* como setar um campo em uma tabela do banco de dados, para indicar
* o pagamento de um boleto de um determinado cliente.
* @see linhaProcessada1
*/
function linhaProcessada($self, $numLn, $vlinha) {
  if($vlinha) {
	  if($vlinha["registro"] == $self::DETALHE) {
      printf("%08d: ", $numLn);
      echo get_class($self) . ": Nosso N&uacute;mero <b>".$vlinha['nosso_numero']."</b> ".
           "Data <b>".$vlinha["data_ocorrencia"]."</b> ". 
           "Valor <b>".$vlinha["valor"]."</b><br/>\n";
    }
  } else echo "Tipo da linha n&atilde;o identificado<br/>\n";
}


/**Outro exemplo de função handler, a ser associada ao evento
* aoProcessarLinha de um objeto da classe RetornoBase.
* Neste exemplo, é utilizado um laço foreach para percorrer
* o vetor associativo $vlinha, mostrando os nomes das chaves
* e os valores obtidos da linha processada.
* @see linhaProcessada */
function linhaProcessada1($self, $numLn, $vlinha) {
  printf("%08d) ", $numLn);
  if($vlinha) {
    foreach($vlinha as $nome_indice => $valor)
      echo get_class($self) . ": $nome_indice: <b>$valor</b><br/>\n ";
    echo "<br/>\n";
  } else echo "Tipo da linha n&atilde;o identificado<br/>\n";
}

//--------------------------------------INÍCIO DA EXECUÇÃO DO CÓDIGO-----------------------------------------------------
$fileName = "retorno_cnab400conv7.ret";

//Use uma das duas instrucões abaixo (comente uma e descomente a outra)
//$cnab400 = RetornoFactory::getRetorno($fileName, "linhaProcessada1");
$cnab400 = RetornoFactory::getRetorno($fileName, "linhaProcessada");

$retorno = new RetornoBanco($cnab400);
$retorno->processar();

?>  
