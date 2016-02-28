<?php

namespace ManoelCampos\RetornoBoleto;

require_once("RetornoAbstract.php");

/**
 * Classe abstrata para leitura de arquivos de retorno de cobranças no padrão CNAB400/CBR643.
 * Layout Padrão CNAB/Febraban 400 posições.<p/>
 * 
 * Baseado na documentação para "Layout de Arquivo Retorno para Convênios
 * na faixa numérica entre 000.001 a 999.999 (Convênios de até 6 posições). Versão Set/09" e
 * "Layout de Arquivo Retorno para convênios na faixa numérica entre 1.000.000 a 9.999.999
 * (Convênios de 7 posições). 
 * Versão Set/09" do Banco do Brasil (arquivos Doc8826BR643Pos6.pdf e Doc2628CBR643Pos7.pdf)
 * 
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="http://manoelcampos.com/contact">Manoel Campos da Silva Filho</a>
 * @version 1.1
 */
abstract class RetornoCNAB400Abstract extends RetornoAbstract {
    protected function processarHeaderArquivo($linha) {
        $vetor = array();
        //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
        $vetor["registro"] = substr($linha, 1, 1); //9 Identificação do Registro Header: “0”
        $vetor["tipo_operacao"] = substr($linha, 2, 1); //9 Tipo de Operação: “2”
        $vetor["id_tipo_operacao"] = substr($linha, 3, 7); //X Identificação Tipo de Operação “RETORNO”
        $vetor["id_tipo_servico"] = substr($linha, 10, 2); //9 Identificação do Tipo de Serviço: “01”
        $vetor["tipo_servico"] = substr($linha, 12, 8); //X Identificação por Extenso do Tipo de Serviço: “COBRANCA”
        $vetor["complemento1"] = substr($linha, 20, 7); //X Complemento do Registro: “Brancos”
        $vetor["agencia_cedente"] = substr($linha, 27, 4); //9 Prefixo da Agência: N. Agência onde está cadastrado o convênio líder do cedente
        $vetor["dv_agencia_cedente"] = substr($linha, 31, 1); //X Dígito Verificador - D.V. - do Prefixo da Agência
        $vetor["conta_cedente"] = substr($linha, 32, 8); //9 Número da Conta Corrente onde está cadastrado o Convênio Líder do Cedente
        $vetor["dv_conta _cedente"] = substr($linha, 40, 1); //X Dígito Verificador - D.V. - da Conta Corrente do Cedente

        $vetor["nome_cedente"] = substr($linha, 47, 30); //X Nome do Cedente
        $vetor["banco"] = substr($linha, 77, 18); //X 001BANCODOBRASIL
        $vetor["data_gravacao"] = $this->formataData(substr($linha, 95, 6)); //9 Data da Gravação: Informe no formado “DDMMAA”
        $vetor["sequencial_reg"] = substr($linha, 395, 6); //9 Seqüencial do Registro: ”000001”
        
        return $vetor;
    }

    protected function processarDetalhe($linha) {
        $vetor = array();
        //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
        $vetor["registro"] = substr($linha, 1, 1); //9  Id do Registro Detalhe: 1 p/ convênios de 6 dígitos e 7 para convênios de 7 dígitos
        //$vetor["zeros1"]              = substr($linha,   2,   2); //9  Zeros
        //$vetor["zeros2"]              = substr($linha,   4,  14); //9  Zeros
        $vetor["agencia"] = substr($linha, 18, 4); //9  Prefixo da Agência
        $vetor["dv_agencia"] = substr($linha, 22, 1); //X  Dígito Verificador - D.V. - do Prefixo da Agência
        $vetor["cc_cedente"] = substr($linha, 23, 8); //9  Número da Conta Corrente do Cedente
        $vetor["dv_cc_cedente"] = substr($linha, 31, 1); //X  Dígito Verificador - D.V. - do Número da Conta Corrente do Cedente
        $vetor["taxa_desconto"] = $this->formataNumero(substr($linha, 96, 5)); //9  v99 Taxa de desconto
        $vetor["taxa_iof"] = substr($linha, 101, 5); //9  Taxa de IOF
        //$vetor["branco"]              = substr($linha, 106,   1); //x  Branco
        $vetor["carteira"] = substr($linha, 107, 2); //9  Carteira
        $vetor["comando"] = substr($linha, 109, 2); //9  Comando - nota 07
        $vetor["data_pagamento"] = $this->formataData(substr($linha, 111, 6)); //X data_ocorrencia =  Data da Entrada/Liquidação (DDMMAA)
        $vetor["num_titulo"] = substr($linha, 117, 10); //X  Número título dado pelo cedente - (ver nota 06 para convênio de 6 dígitos)
        $vetor["data_vencimento"] = substr($linha, 147, 6); //9  Data de vencimento (DDMMAA) (ver nota 6 para convênios de 7 dígitos)
        $vetor["valor_titulo"] = $this->formataNumero(substr($linha, 153, 13)); //9  v99 Valor do título

        $vetor["cod_banco"] = substr($linha, 166, 3); //9  Código do banco recebedor - ver nota 08
        $vetor["agencia"] = substr($linha, 169, 4); //9  Prefixo da agência recebedora - ver nota 08
        $vetor["dv_agencia"] = substr($linha, 173, 1); //X  DV prefixo recebedora
        $vetor["especie"] = substr($linha, 174, 2); //9  Espécie do título - ver nota 09
        $vetor["data_credito"] = substr($linha, 176, 6); //9  Data do crédito (DDMMAA) - ver nota 10
        $vetor["valor_tarifa"] = $this->formataNumero(substr($linha, 182, 7)); //9  v99 Valor da tarifa - ver nota 06
        $vetor["outras_despesas"] = $this->formataNumero(substr($linha, 189, 13)); //9  v99 Outras despesas
        $vetor["juros_desconto"] = $this->formataNumero(substr($linha, 202, 13)); //9  v99 Juros do desconto
        $vetor["iof_desconto"] = $this->formataNumero(substr($linha, 215, 13)); //9  v99 IOF do desconto
        $vetor["valor_abatimento"] = $this->formataNumero(substr($linha, 228, 13)); //9  v99 Valor do abatimento
        $vetor["desconto_concedido"] = $this->formataNumero(substr($linha, 241, 13)); //9  v99 Desconto concedido 
        $vetor["valor_pagamento"] = $this->formataNumero(substr($linha, 254, 13)); //9  v99 Valor recebido (valor recebido parcial)
        $vetor["juros_mora"] = $this->formataNumero(substr($linha, 267, 13)); //9  v99 Juros de mora
        $vetor["outros_recebimentos"] = $this->formataNumero(substr($linha, 280, 13)); //9  v99 Outros recebimentos
        $vetor["abatimento_nao_aprov"] = $this->formataNumero(substr($linha, 293, 13)); //9  v99 Abatimento não aproveitado pelo sacado
        $vetor["valor_lancamento"] = $this->formataNumero(substr($linha, 306, 13)); //9  v99 Valor do lançamento
        $vetor["indicativo_dc"] = substr($linha, 319, 1); //9  Indicativo de débito/crédito - ver nota 11
        $vetor["indicador_valor"] = substr($linha, 320, 1); //9  Indicador de valor -ver  nota 12
        $vetor["valor_ajuste"] = $this->formataNumero(substr($linha, 321, 12)); //9  v99 Valor do ajuste - ver nota 13
        //$vetor["brancos1"]            = substr($linha, 333,   1); //X  Brancos (vide observação para cobrança compartilhada) 14
        //$vetor["brancos2"]            = substr($linha, 334,   9); //9  Brancos (vide observação para cobrança compartilhada) 14

        $vetor["canal_pag_titulo"] = substr($linha, 393, 2); //9 Canal de pagamento do título utilizado pelo sacado - ver nota 15
        $vetor["sequencial"] = substr($linha, 395, 6); //9 Seqüencial do registro

        return $vetor;
    }

    protected function processarTrailerArquivo($linha) {
        $vetor = array();
        //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
        $vetor["registro"] = substr($linha, 1, 1);  //9  Identificação do Registro Trailer: “9”
        $vetor["retorno"] = substr($linha, 2, 1);  //9  “2”
        $vetor["tipo_registro"] = substr($linha, 3, 2);  //9  “01”
        $vetor["cod_banco"] = substr($linha, 5, 3);
        $vetor["cob_simples_qtd_titulos"] = substr($linha, 18, 8);  //9  Cobrança Simples - quantidade de títulos em cobranca
        $vetor["cob_simples_vlr_total"] = $this->formataNumero(substr($linha, 26, 14)); //9  v99 Cobrança Simples - valor total
        $vetor["cob_simples_num_aviso"] = substr($linha, 40, 8);  //9  Cobrança Simples - Número do aviso
        $vetor["cob_vinc_qtd_titulos"] = substr($linha, 58, 8);  //9  Cobrança Vinculada - quantidade de títulos
        $vetor["cob_vinc_valor_total"] = $this->formataNumero(substr($linha, 66, 14)); //9  v99 Cobrança Vinculada - valor total
        $vetor["cob_vinc_num_aviso"] = substr($linha, 80, 8);  //9  Cobrança Vinculada - Número do aviso
        //$vetor["cob_vinc_brancos"]        = substr($linha,  88,  10); //X  Cobrança Vinculada - Brancos
        $vetor["cob_cauc_qtd_titulos"] = substr($linha, 98, 8);  //9  Cobrança Caucionada - quantidade de títulos
        $vetor["cob_cauc_vlr_total"] = $this->formataNumero(substr($linha, 106, 14)); //9  v99 Cobrança Caucionada - valor total
        $vetor["cob_cauc_num_aviso"] = substr($linha, 120, 8);  //9  Cobrança Caucionada - Número do aviso
        //$vetor["cob_cauc_brancos"]        = substr($linha, 128,  10); //X  Cobrança Caucionada - Brancos
        $vetor["cob_desc_qtd_titulos"] = substr($linha, 138, 8);  //9  Cobrança Descontada - quantidade de títulos
        $vetor["cob_desc_vlr_total"] = $this->formataNumero(substr($linha, 146, 14)); //9  v99 Cobrança Descontada - valor total
        $vetor["cob_desc_num_aviso"] = substr($linha, 160, 8);  //9  Cobrança Descontada - Número do aviso
        //$vetor["cob_desc_brancos"]        = substr($linha, 168,  50); //X  Cobrança Descontada - Brancos
        $vetor["cob_vendor_qtd_titulos"] = substr($linha, 218, 8);  //9  Cobrança Vendor - quantidade de títulos
        $vetor["cob_vendor_vlr_total"] = $this->formataNumero(substr($linha, 226, 14)); //9  v99 Cobrança Vendor - valor total
        $vetor["cob_vendor_num_aviso"] = substr($linha, 240, 8);  //9  Cobrança Vendor - Número do aviso
        //$vetor["cob_vendor_brancos"]      = substr($linha, 248, 147); //X  Cobrança Vendor – Brancos
        $vetor["sequencial"] = substr($linha, 395, 6);  //9  Seqüencial do registro

        return $vetor;
    }

    protected function getTipoLinha($linha) {
        return substr($linha, 1, 1);
    }    

    public function getTotalCaracteresPorLinha() {
        return 400;
    }

    public function getIdHeaderArquivo() {
        return 0;
    }

    public function getIdTrailerArquivo() {
        return 9;
    }
}