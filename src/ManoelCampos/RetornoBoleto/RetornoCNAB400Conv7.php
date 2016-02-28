<?php

namespace ManoelCampos\RetornoBoleto;

require_once("RetornoCNAB400.php");

/** 
 * Classe para leitura de arquivos de retorno de cobranças no padrão CNAB400/CBR643 com convênio de 7 posições.
 * Layout Padrão CNAB/Febraban 400 posições.<p/>
 * 
 * Baseado na documentação para "Layout de Arquivo Retorno para convênios na
 * faixa numérica entre 1.000.000 a 9.999.999 (Convênios de 7 posições). Versão Set/09"
 * do Banco do Brasil, disponível <a href="http://www.bb.com.br/docs/pub/emp/empl/dwn/Doc2628CBR643Pos7.pdf">aqui</a>.
 * 
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="http://manoelcampos.com/contact">Manoel Campos da Silva Filho</a>
 * @version 1.0
 */
class RetornoCNAB400Conv7 extends RetornoCNAB400 {
    protected function processarHeaderArquivo($linha) {
        $vetor = parent::processarHeaderArquivo($linha);
        //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
        //$vetor["zeros"]              = substr($linha, 41,    6); //Zeros
        $vetor["complemento2"] = substr($linha, 108, 42); //Complemento do Registro: “Brancos”
        $vetor["convenio"] = substr($linha, 150, 7); //9 Número do convênio
        $vetor["complemento3"] = substr($linha, 157, 238); //X Complemento do Registro: “Brancos”
        
        return $vetor;
    }

    protected function processarDetalhe($linha) {
        $vetor = parent::processarDetalhe($linha);
        //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
        $vetor["convenio"] = substr($linha, 32, 7); //9  Número do Convênio de Cobrança do Cedente
        $vetor["controle"] = substr($linha, 39, 25); //X  Número de Controle do Participante
        $vetor["nosso_numero"] = substr($linha, 64, 17); //9  Nosso-Número
        $vetor["tipo_cobranca"] = substr($linha, 81, 1); //9  Tipo de cobrança - nota 02
        $vetor["tipo_cobranca_cmd72"] = substr($linha, 82, 1); //9  Tipo de cobrança específico p/ comando 72 - nota 03
        $vetor["dias_calculo"] = substr($linha, 83, 4); //9  Dias para cálculo - nota 04
        $vetor["natureza"] = substr($linha, 87, 2); //9  Natureza do recebimento - nota 05
        $vetor["prefixo_titulo"] = substr($linha, 89, 3); //X  Prefixo do título
        $vetor["variacao_carteira"] = substr($linha, 92, 3); //9  Variação da Carteira
        $vetor["conta_caucao"] = substr($linha, 95, 1); //9  Conta Caução - nota 06

        /*
        $vetor["brancos"]             = substr($linha, 127,  20); //X  Brancos
        $vetor["zeros3"]              = substr($linha, 343,   7); //9 Zeros - nota 14
        $vetor["zeros4"]              = substr($linha, 350,   9); //9 Zeros - nota 14
        $vetor["zeros5"]              = substr($linha, 359,   7); //9 Zeros - nota 14
        $vetor["zeros6"]              = substr($linha, 366,   9); //9 Zeros - nota 14
        $vetor["zeros7"]              = substr($linha, 375,   7); //9 Zeros - nota 14
        $vetor["zeros8"]              = substr($linha, 382,   9); //9 Zeros - nota 14
        $vetor["brancos3"]            = substr($linha, 391,   2); //X Brancos
         */
        return $vetor;
    }

    public function getIdDetalhe() {
        return 7;
    }
}