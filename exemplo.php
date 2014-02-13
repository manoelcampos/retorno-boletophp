<?php 
/**Exemplo de uso da classe para processamento de arquivo de retorno de cobranças em formato FEBRABAN/CNAB400,
* testado com arquivo de retorno do Banco do Brasil.<br/>
* @copyright GPLv2
* @package LeituraArquivoRetorno
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.1
*/

//Adiciona a classe strategy LeituraRetornoBanco que vincula um objeto de uma sub-classe
//de LeituraRetornoBase, e assim, executa o processamento do arquivo de uma determinada
//carteira de um banco específico.
require_once("RetornoBanco.php");
//Adiciona a classe para leitura de arquivos de retorno para o formato Febraban/CNAB400
require_once("RetornoCNAB400.php");

/**Função handler a ser associada ao evento aoProcessarLinha de um objeto da classe
* LeituraRetornoBase. A função será chamada cada vez que o evento for disparado.
* @param $numLn Número da linha processada.
* @param $vlinha Vetor contendo a linha processada, contendo os valores da armazenados
* nas colunas deste vetor. Nesta função o usuário pode fazer o que desejar,
* como setar um campo em uma tabela do banco de dados, para indicar
* o pagamento de um boleto de um determinado cliente.
*/
function linhaProcessada($numLn, $vlinha) {
	if($vlinha["id_registro"] == 1) { //RetornoCNAB400::DETALHE
    printf("%08d: ", $numLn);
    echo "Nosso N&uacute;mero <b>".$vlinha['nosso_numero']."</b> ".
         "Data <b>".$vlinha["data_ent_liq"]."</b> ". 
         "Valor <b>".$vlinha["valor"]."</b><br/>\n";
  }
}


/**Outro exemplo de função handler, a ser associada ao evento
* aoProcessarLinha de um objeto da classe LeituraRetornoBase.
* Neste exemplo, é utilizado um laço foreach para percorrer
* o vetor associativo $vlinha, mostrando os nomes das chaves
* e os valores obtidos da linha processada.
* @see linhaProcessada*/
function linhaProcessada1($numLn, $vlinha) {
  printf("%08d) ", $numLn);
  foreach($vlinha as $nome_indice => $valor)
    echo "$nome_indice: <b>$valor</b><br/>\n ";
  echo "<br/>\n";
}

//--------------------------------------INÍCIO DA EXECUÇÃO DO CÓDIGO-----------------------------------------------------

//$cnab400 = new RetornoCNAB400("retorno.ret", "linhaProcessada1");
$cnab400 = new RetornoCNAB400("retorno.ret", "linhaProcessada");
$retorno = new RetornoBanco($cnab400);
$retorno->processar();

?>
