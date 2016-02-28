<?php

/** 
 * Exemplo de uso da classe para processamento de arquivo de retorno de cobranças em formato FEBRABAN/CNAB240,
 * testado com arquivo de retorno do Banco do Brasil.
 * Cateira 18 variação 19 e carteira 18 variação 27 do Banco do Brasil.
 * 
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="http://manoelcampos.com/contact">Manoel Campos da Silva Filho</a>
 * @version 1.0
 */

require_once("../vendor/autoload.php");

use ManoelCampos\RetornoBoleto\LeituraArquivo;
use ManoelCampos\RetornoBoleto\RetornoFactory;
use ManoelCampos\RetornoBoleto\RetornoInterface;
use ManoelCampos\RetornoBoleto\LinhaArquivo;

/**
 * Função de callback que será chamada cada vez que uma linha for lida do
 * arquivo de retorno. Esta versão da função de callback imprime campos
 * específicos de cada linha lida.
 * 
 * @param RetornoInterface $retorno Objeto responsável pela leitura do arquivo de retorno
 * @param LinhaArquivo $linha Objeto contendo os dados da linha lida
 */
$processarLinha1 = function (RetornoInterface $retorno, LinhaArquivo $linha) {
    if($linha->dados["registro"] == $retorno->getIdHeaderArquivo()){
        echo "<b>Tipo de Arquivo de Retorno: " . get_class($retorno) . "</b><p/>";
        echo "<table>\n";
        echo "<tr><th>Linha</th><th>Nosso Número</th><th>Data Pag</th>".
             "<th>Valor Título</th><th>Valor Pago</th></tr>\n";
    }
    else if($linha->dados["registro"] == $retorno->getIdTrailerArquivo()){
        echo "</table>\n";
    }
    else if($linha->dados["registro"] == $retorno->getIdDetalhe()){ 
        printf(
            "<tr><td>%d</td><td>%d</td><td>%s</td><td>%.2f</td><td>%.2f</td></tr>\n",
            $linha->numero, 
            $linha->dados['nosso_numero'], 
            $linha->dados["data_pagamento"],
            $linha->dados["valor_titulo"],
            $linha->dados["valor_pagamento"]);

        echo "</tr>\n";
    }
};

/**
 * Função de callback que será chamada cada vez que uma linha for lida do
 * arquivo de retorno. Esta versão da função de callback imprime todos os campos
 * da linha lida.
 * 
 * @param RetornoInterface $retorno Objeto responsável pela leitura do arquivo de retorno
 * @param LinhaArquivo $linha Objeto contendo os dados da linha lida
 */
$processarLinha2 = function (RetornoInterface $retorno, LinhaArquivo $linha) {
    if($linha->dados["registro"] == $retorno->getIdHeaderArquivo()){
        echo "<b>Tipo de Arquivo de Retorno: " . get_class($retorno) . "</b><p/>";
        echo "<table>\n";
    }
    else if($linha->dados["registro"] == $retorno->getIdTrailerArquivo()){
        echo "</table>\n";
    }
    else {
        printf("<tr><th colspan='2'>Número da Linha: %08d</th></tr>\n", $linha->numero);
        foreach ($linha->dados as $nome_campo => $valor_campo){
            printf("<tr><td><b>%s</b></td><td>%s</td>\n ", $nome_campo, $valor_campo);
        }
        echo "</tr>\n";
    }
};

//--------------------------INÍCIO DA EXECUÇÃO DO CÓDIGO------------------------
$fileName = "retornos/retorno_cnab240-bb.ret";

$cnab240 = RetornoFactory::getRetorno($fileName);

/*
 Use uma das duas instrucões abaixo para usar uma das duas funções
 de callback definidas acima (comente uma e descomente a outra).
*/
$leitura = new LeituraArquivo($processarLinha1, $cnab240);
//$leitura = new LeituraArquivo($processarLinha2, $cnab240);

$leitura->lerArquivoRetorno();

