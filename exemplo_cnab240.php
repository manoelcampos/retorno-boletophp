<?php
/**Exemplo de uso da classe para processamento de arquivo de retorno de cobranças em formato FEBRABAN/CNAB240,
* testado com arquivo de retorno do Banco do Brasil.
* Cateira 18 variação 19 e carteira 18 variação 27 do Banco do Brasil.
* @copyright GPLv2
* @package LeituraArquivoRetorno
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @modified 01/04/2011 por Bento David Ribeiro Silva
* @version 0.2
*/

//Adiciona a classe strategy RetornoBanco que vincula um objeto de uma sub-classe
//de RetornoBase, e assim, executa o processamento do arquivo de uma determinada
//carteira de um banco específico.
require_once("RetornoBanco.php");
//Adiciona a classe para leitura de arquivos de retorno para o formato Febraban/CNAB240
require_once("RetornoCNAB240.php");


/**Função handler a ser associada ao evento aoProcessarLinha de um objeto da classe
* RetornoBase. A função será chamada cada vez que o evento for disparado.
*
* A coluna do tipo DETALHE em retorno CNAB240 tem 2 segmentos(duas linhas) "T" e "U"
* este exemplo lista no nome da empresa
* e alguns dados do DETALHE de cada boleto pago.
* Nota: o Segmento "U" sempre é continuação do Segmento "T" que o precedeu
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
	  if($vlinha["registro"] == $self::HEADER_ARQUIVO)
		  echo "<b>".$vlinha['nome_empresa']."</b><br />";
	  if($vlinha["ID"] == $self::DETALHE_T) {
		  echo "<p>Nosso N&uacute;mero: <b>".$vlinha['nosso_numero']."</b> - 
		  Vencimento: <b>".$vlinha['vencimento']."</b><br />
		  Valor do Titulo: <b>R\$ ".number_format($vlinha['valor_titulo'], 2, ',', '')."</b> - 
		  Valor da Tarifa: <b>R\$ ".number_format($vlinha['valor_tarifa'], 2, ',', '')."</b><br />";
	  }
	  if($vlinha["ID"] == $self::DETALHE_U) {
		  echo "Valor Pago: <b>R\$ ".number_format($vlinha['valor_pago'], 2, ',', '')."</b> - 
		  Valor recebido: <b>R\$ ".number_format($vlinha['valor_liquido'], 2, ',', '')."</b><br />
		  Data do pagamento: <b>".$vlinha['data_ocorrencia']."</b> - 
		  Data do credito: <b>".$vlinha['data_credito']."</b>";
    }
  } else echo "Tipo da linha n&atilde;o identificado<br/>\n";
}

/**Outro exemplo de função handler, a ser associada ao evento
* aoProcessarLinha de um objeto da classe RetornoBase.
* Neste exemplo, é utilizado um laço foreach para percorrer
* o vetor associativo $vlinha, mostrando os nomes das chaves
* e os valores obtidos da linha processada.
* @see linhaProcessada
*/
function linhaProcessada1($self, $numLn, $vlinha) {
  printf("%08d) ", $numLn);
  if($vlinha) {
    foreach($vlinha as $nome_indice => $valor)
      echo "$nome_indice: <b>$valor</b><br/>\n ";
  } else echo "Tipo da linha n&atilde;o identificado<br/>\n";
  echo "<br/>\n";
  
}

//--------------------------------------INÍCIO DA EXECUÇÃO DO CÓDIGO-----------------------------------------------------

//$cnab240 = new RetornoCNAB400("retorno_cnab240.ret", "linhaProcessada");
$cnab240 = new RetornoCNAB240("retorno_cnab240.ret", "linhaProcessada1");
$retorno = new RetornoBanco($cnab240);
$retorno->processar();
?>
