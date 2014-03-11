<?php

require_once("RetornoCNAB240.php");
require_once("RetornoCNAB400Conv6.php");
require_once("RetornoCNAB400Conv7.php");
require_once("RetornoCNAB400Bradesco.php");

/**Classe que identifica o tipo de arquivo de retorno sendo carregado e instancia a classe
* específica para leitura do mesmo.
* @copyright GPLv2
* @package ArquivoRetornoTitulosBancarios
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.4
*/
class RetornoFactory  {
  /**Instancia um objeto de uma das sub-classes de RetornoBase,
  * com base no tipo do arquivo de retorno indicado por $fileName
  * @param string fileName Nome do arquivo de retorno a ser identificado
  * para poder instancia a classe específica para leitura do mesmo.
  * @param string $aoProcessarLinhaFunctionName @see RetornoBase
  * @return RetornoBase Retorna um objeto de uma das sub-classes de RetornoBase.
  */
  static function getRetorno($fileName, $aoProcessarLinhaFunctionName) {
    if($fileName == "")
      throw new Exception("Informe o nome do arquivo de retorno.");
      
    if($arq = fopen($fileName, "r")) {
       //Lê o header do arquivo
       if($linha=fgets($arq, 500)) {
          //echo "<h1>Arquivo: $fileName. Linha: $linha</h1>";
          $len = strlen($linha);
          if($len >= 240 and $len <= 242)
             return new RetornoCNAB240($fileName, $aoProcessarLinhaFunctionName);
          else if($len >= 400 and $len <= 402) {
             if(strstr($linha, "BRADESCO"))
               return new RetornoCNAB400Bradesco($fileName, $aoProcessarLinhaFunctionName);
          
             //Lê o primeiro registro detalhe
             if($linha=fgets($arq, 500)) {
                switch ($linha[0]) {
                  case RetornoCNAB400Conv6::DETALHE:
                    return new RetornoCNAB400Conv6($fileName, $aoProcessarLinhaFunctionName);
                  break;
                  case RetornoCNAB400Conv7::DETALHE:
                    return new RetornoCNAB400Conv7($fileName, $aoProcessarLinhaFunctionName);
                  break;
                  default:
                    throw new Exception("Tipo de registro detalhe desconhecido: " . $linha[0]);   
                  break;
                }
             }
             else throw new Exception("Tipo de arquivo de retorno não identificado. Não foi possível ler um registro detalhe.");
          }
          else throw new Exception("Tipo de arquivo de retorno não identificado. Total de colunas do header: $len");
       } 
       else throw new Exception("Tipo de arquivo de retorno não identificado. Não foi possível ler o header do arquivo.");
       
       fclose($arq);
    }
    else throw new Exception("Não foi possível abrir o arquivo \"$fileName\".");
  }
}
?>
