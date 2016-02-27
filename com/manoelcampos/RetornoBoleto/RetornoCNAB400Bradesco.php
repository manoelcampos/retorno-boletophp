<?php

namespace com\manoelcampos\RetornoBoleto;

require_once("RetornoCNAB400.php");

/** 
 * Classe para leitura de arquivos de retorno de cobranças no padrão 400 posições do Bradesco.<br/>.
 * Baseado no documento "Cobrança Bradesco - Manual Operacional para Troca de Arquivos" do Bradesco 
 * (arquivo layout_cobranca_port_bradesco.pdf)
 * 
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="http://manoelcampos.com/contact">Manoel Campos da Silva Filho</a>
 * @version 1.0
 */
class RetornoCNAB400Bradesco extends RetornoCNAB400 {
    protected function processarHeaderArquivo($linha) {
        /*
        O formato de 400 posicoes do Bradesco é diferente do padrao FEBRABAN
        (pelo menos do usado pelo BB). Assim, não é chamada o método na classe
        pai pois o mesmo é totalmente reimplementada aqui.
        */
        $vetor = array();
        //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
        $vetor["registro"] = substr($linha, 1, 1); //9 Identificação do Registro Header: “0”
        $vetor["tipo_operacao"] = substr($linha, 2, 1); //9 Tipo de Operação: “2”
        $vetor["id_tipo_operacao"] = substr($linha, 3, 7); //X Identificação Tipo de Operação “RETORNO”
        $vetor["id_tipo_servico"] = substr($linha, 10, 2); //9 Identificação do Tipo de Serviço: “01”
        $vetor["tipo_servico"] = substr($linha, 12, 15); //X Identificação por Extenso do Tipo de Serviço: “COBRANCA”
        $vetor["cod_empresa"] = substr($linha, 27, 20);
        $vetor["nome_empresa"] = substr($linha, 47, 30); //razao social
        $vetor["num_banco"] = substr($linha, 77, 3); //237 (Código do bradesco)
        $vetor["banco"] = substr($linha, 80, 15); //Nome do banco (BRADESCO)
        $vetor["data_gravacao"] = $this->formataData(substr($linha, 95, 6)); //9 Data da Gravação: Informe no formado “DDMMAA”
        $vetor["densidade_gravacao"] = substr($linha, 101, 8); //01600000 
        $vetor["num_aviso_bancario"] = substr($linha, 109, 5);
        $vetor["data_credito"] = $this->formataData(substr($linha, 380, 6)); // “DDMMAA”
        $vetor["sequencial_reg"] = substr($linha, 395, 6); //9 Seqüencial do Registro: ”000001”

        return $vetor;
    }

    protected function processarDetalhe($linha) {
        /*
        O formato de 400 posicoes do Bradesco é diferente do padrao FEBRABAN
        (pelo menos do usado pelo BB). Assim, não é chamada o método na classe
        pai pois o mesmo é totalmente reimplementada aqui.
        */
        $vetor = array();
        //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
        $vetor["registro"] = substr($linha, 1, 1);  //9  Id do Registro Detalhe: 1 
        $vetor["tipo_inscr_empresa"] = substr($linha, 2, 2);  //9  01-CPF | 02-CNPJ | 03-PIS/PASEP | 98-Não tem | 99-Outro
        $vetor["num_inscr_empresa"] = substr($linha, 4, 14);  //9  CNPJ/CPF, Número, Filial ou Controle
        $vetor["id_empresa_banco"] = substr($linha, 21, 17); //9  Identificação da Empresa Cedente no Banco
        //Zero, Carteira (size=3), Agência (size=5) e Conta Corrente (size=8)

        $vetor["num_controle_part"] = substr($linha, 38, 25); //No Controle do Participante | Uso da Empresa 
        $vetor["nosso_numero"] = substr($linha, 71, 12); //Identificação do Título no Banco
        $vetor["id_rateio_credito"] = substr($linha, 105, 1); //Indicador de Rateio Crédito “R” 
        $vetor["carteira"] = substr($linha, 108, 1);  //Carteira
        $vetor["id_ocorrencia"] = substr($linha, 109, 2);  //Identificação de Ocorrência (vide pg 47)
        $vetor["data_pagamento"] = $this->formataData(substr($linha, 111, 6)); //X  data_ocorrencia = Data da Entrada/Liquidação (DDMMAA)
        $vetor["num_documento"] = substr($linha, 117, 10);  //A  Número título dado pelo cedente
        //$vetor["id_titulo_banco"]     = substr($linha, 127,  20);  //mesmo valor que o campo nosso_numero (indicado anteriormente)
        $vetor["data_vencimento"] = $this->formataData(substr($linha, 147, 6));  //9  Data de vencimento (DDMMAA) 
        $vetor["valor_titulo"] = $this->formataNumero(substr($linha, 153, 13)); //9  v99 Valor do título
        $vetor["cod_banco"] = substr($linha, 166, 3);  //9  Código do banco recebedor 
        $vetor["agencia"] = substr($linha, 169, 5);  //9  Código da agência recebedora 
        $vetor["desp_cobranca"] = $this->formataNumero(substr($linha, 176, 13)); // Despesas de cobrança para
        //os Códigos de Ocorrência 
        //02 - Entrada Confirmada 
        //28 - Débito de Tarifas

        $vetor["outras_despesas"] = $this->formataNumero(substr($linha, 189, 13)); //9  v99 Outras despesas
        $vetor["juros_atraso"] = $this->formataNumero(substr($linha, 202, 13)); //9  v99 Juros atraso
        $vetor["iof"] = $this->formataNumero(substr($linha, 215, 13)); //9  v99 IOF 
        $vetor["desconto_concedido"] = $this->formataNumero(substr($linha, 241, 13)); //9  v99 Desconto concedido 
        $vetor["valor_recebido"] = $this->formataNumero(substr($linha, 254, 13)); //9  v99 Valor pago
        $vetor["juros_mora"] = $this->formataNumero(substr($linha, 267, 13)); //9  v99 Juros de mora
        $vetor["outros_recebimentos"] = $this->formataNumero(substr($linha, 280, 13)); //9  v99 Outros recebimentos
        $vetor["motivo_cod_ocorrencia"] = substr($linha, 319, 10);  //Motivos das Rejeições para 
        //os Códigos de Ocorrência da Posição 109 a 110 
        $vetor["num_cartorio"] = substr($linha, 369, 2);
        $vetor["num_protocolo"] = substr($linha, 371, 10);

        $vetor["valor_abatimento"] = $this->formataNumero(substr($linha, 228, 13)); //9  v99 Valor do abatimento
        $vetor["abatimento_nao_aprov"] = $this->formataNumero(substr($linha, 293, 13)); //9  v99 Abatimento não aproveitado pelo sacado
        $vetor["valor_pagamento"] = $this->formataNumero(substr($linha, 306, 13)); //9  v99 Valor do lançamento
        $vetor["indicativo_dc"] = substr($linha, 319, 1); //9  Indicativo de débito/crédito - ver nota 11
        $vetor["indicador_valor"] = substr($linha, 320, 1); //9  Indicador de valor -ver  nota 12
        $vetor["valor_ajuste"] = $this->formataNumero(substr($linha, 321, 12)); //9  v99 Valor do ajuste - ver nota 13

        $vetor["sequencial"] = substr($linha, 395, 6); //9 Seqüencial do registro

        return $vetor;
    }

    protected function processarTrailerArquivo($linha) {
        /*
        O formato de 400 posicoes do Bradesco é diferente do padrao FEBRABAN
        (pelo menos do usado pelo BB). Assim, não é chamada o método na classe
        pai pois o mesmo é totalmente reimplementada aqui.
        */
        $vetor = array();
        //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
        $vetor["registro"] = substr($linha, 1, 1);  //9  Identificação do Registro Trailer: “9”
        $vetor["retorno"] = substr($linha, 2, 1);  //9  “2”
        $vetor["tipo_registro"] = substr($linha, 3, 2);  //9  “01”
        $vetor["cod_banco"] = substr($linha, 5, 3);
        $vetor["cob_simples_qtd_titulos"] = substr($linha, 18, 8);  //9  Cobrança Simples - quantidade de títulos em cobranca
        $vetor["cob_simples_vlr_total"] = $this->formataNumero(substr($linha, 26, 14)); //9  v99 Cobrança Simples - valor total
        $vetor["cob_simples_num_aviso"] = substr($linha, 40, 8);  //9  Cobrança Simples - Número do aviso
        $vetor["qtd_regs02"] = substr($linha, 58, 5);  //Quantidade  de Registros- Ocorrência 02 – Confirmação de Entradas
        $vetor["valor_regs02"] = $this->formataNumero(substr($linha, 63, 12)); //Valor dos Registros- Ocorrência 02 – Confirmação de Entradas
        $vetor["valor_regs06liq"] = $this->formataNumero(substr($linha, 75, 12)); //Valor dos Registros- Ocorrência 06 liquidacao
        $vetor["qtd_regs06"] = substr($linha, 87, 5);  //Quantidade  de Registros- Ocorrência 06 – liquidacao
        $vetor["valor_regs06"] = $this->formataNumero(substr($linha, 92, 12)); //Valor dos Registros- Ocorrência 06
        $vetor["qtd_regs09"] = substr($linha, 104, 5);  //Quantidade  de Registros- Ocorrência 09 e 10
        $vetor["valor_regs02"] = $this->formataNumero(substr($linha, 109, 12)); //Valor dos  Registros- Ocorrência 09 e 10
        $vetor["qtd_regs13"] = substr($linha, 121, 5);  //Quantidade  de Registros- Ocorrência 13
        $vetor["valor_regs13"] = $this->formataNumero(substr($linha, 126, 12)); //Valor dos  Registros- Ocorrência 13
        $vetor["qtd_regs14"] = substr($linha, 138, 5);  //Quantidade  de Registros- Ocorrência 14
        $vetor["valor_regs14"] = $this->formataNumero(substr($linha, 143, 12)); //Valor dos  Registros- Ocorrência 14
        $vetor["qtd_regs12"] = substr($linha, 155, 5);  //Quantidade  de Registros- Ocorrência 12
        $vetor["valor_regs12"] = $this->formataNumero(substr($linha, 160, 12)); //Valor dos  Registros- Ocorrência 12
        $vetor["qtd_regs19"] = substr($linha, 172, 5);  //Quantidade  de Registros- Ocorrência 19
        $vetor["valor_regs19"] = $this->formataNumero(substr($linha, 177, 12)); //Valor dos  Registros- Ocorrência 19
        $vetor["valor_total_rateios"] = $this->formataNumero(substr($linha, 363, 15));
        $vetor["qtd_rateios"] = substr($linha, 378, 8);

        $vetor["sequencial"] = substr($linha, 395, 6);  //9  Seqüencial do registro

        return $vetor;
    }
}