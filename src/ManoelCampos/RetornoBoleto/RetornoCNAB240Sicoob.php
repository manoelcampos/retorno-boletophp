<?php

namespace ManoelCampos\RetornoBoleto;

require_once("RetornoAbstract.php");

/**
 * Classe para leitura_arquivos_retorno_cobranças_padrão CNAB240 para Banco Sicoob.
 * Layout Padrão <a href="http://www.febraban.org.br">Febraban</a> 240 posições V08.4 de 01/09/2009.<p/>
 *
 * @license <a href="https://opensource.org/licenses/MIT">MIT License</a>
 * @author <a href="mailto:mauroagr@gmail.com">Mauro Tschiedel</a>
 * @version 1.1
 */
class RetornoCNAB240Sicoob extends RetornoCNAB240 {
    protected function processarHeaderArquivo($linha) {
        $vetor = array();
        $vetor["banco"] = substr($linha, 1, 3); //NUMERICO //Código do Banco na Compensação
        $vetor["lote"] = substr($linha, 4, 4); //num - default 0000 //Lote de Serviço
        $vetor["registro"] = substr($linha, 8, 1); //num - default 0 //Tipo de Registro
        $vetor["cnab1"] = substr($linha, 9, 9); //BRANCOS //Uso Exclusivo FEBRABAN / CNAB
        $vetor["tipo_inscricao_empresa"] = substr($linha, 18, 1); //num - 1-CPF, 2-CGC //Tipo de Inscrição da Empresa
        $vetor["num_inscricao_empresa"] = substr($linha, 19, 14); //numerico  //Número de Inscrição da Empresa
        $vetor["cod_convenio"] = substr($linha, 33, 20); //alfanumerico  //Código do Convênio no Banco
        $vetor["agencia"] = substr($linha, 53, 5); //numerico //Agência Mantenedora da Conta
        $vetor["dv_agencia"] = substr($linha, 58, 1); //alfanumerico //DV da Agência
        $vetor["conta_corrente"] = substr($linha, 59, 12); //numerico //Número da Conta Corrente
        $vetor["dv_conta"] = substr($linha, 71, 1); //alfanumerico  //DV da Conta Corrent
        $vetor["dv_ag_conta"] = substr($linha, 72, 1); //alfanumerico
        $vetor["nome_empresa"] = substr($linha, 73, 30); //alfanumerico
        $vetor["nome_banco"] = substr($linha, 103, 30); //alfanumerico
        $vetor["uso_febraban_cnab2"] = substr($linha, 133, 10); //brancos //Uso Exclusivo FEBRABAN / CNAB
        $vetor["cod_arq"] = substr($linha, 143, 1); //num - 1-REM E 2-RET ?? //Código do arquivo de remessa/retorno
        $vetor["data_geracao_arq"] = substr($linha, 144, 8); //num - formato ddmmaaaa
        $vetor["hora_geracao_arq"] = substr($linha, 152, 6); //num - formato hhmmss
        $vetor["sequencia"] = substr($linha, 158, 6); //numerico //Número Sequencial do Arquivo
        $vetor["versao_layout_arq"] = substr($linha, 164, 3); //num 084 //Num da Versão do Layout do Arquivo
        $vetor["densidade"] = substr($linha, 167, 5); //numerico //Densidade de Gravação do Arquivo
        $vetor["reservado_banco"] = substr($linha, 172, 20); //alfanumerico //Para Uso Reservado do Banco
        $vetor["reservado_empresa"] = substr($linha, 192, 20); //alfanumerico //Para Uso Reservado da Empresa
        $vetor["uso_febraban_cnab3"] = substr($linha, 212, 29); //brancos //Uso Exclusivo FEBRABAN / CNAB

        return $vetor;
    }

    protected function processarHeaderLote($linha) {
        //SEGMENTO J - Pagamento de Títulos de Cobrança
        $vetor = array();
        $vetor["banco"] = substr($linha, 1, 3); //numerico //Código do Banco na Compensação
        $vetor["lote"] = substr($linha, 4, 4); //numerico //Lote de Serviço
        $vetor["registro"] = substr($linha, 8, 1); //num - default 1 //Tipo de Registro
        $vetor["operacao"] = substr($linha, 9, 1); //alfanumerico - default C //Tipo da Operação
        $vetor["servico"] = substr($linha, 10, 2); //num  //Tipo do Serviço
        $vetor["cnab_servico"] = substr($linha, 12, 2); //num //Forma de Lançamento
        $vetor["layout_lote"] = substr($linha, 14, 3); //num - default '030' //No da Versão do Layout do Lote
        $vetor["cnab1"] = substr($linha, 17, 1); //alfa - default brancos  //Uso Exclusivo da FEBRABAN/CNAB
        $vetor["tipo_inscricao_empresa"] = substr($linha, 18, 1); //num - 1-CPF, 2-CGC //Tipo de Inscrição da Empresa
        $vetor["num_inscricao_empresa"] = substr($linha, 19, 15); //numerico //Número de Inscrição da Empresa
        $vetor["cod_convenio"] = substr($linha, 34, 20); //alfanumerico //Código do Convênio no Banco

        $vetor["agencia"] = substr($linha, 54, 5); //numerico //Agência Mantenedora da Conta
        $vetor["dv_agencia"] = substr($linha, 59, 1); //alfanumerico //DV da Agência Mantenedora da Conta
        $vetor["conta_corrente"] = substr($linha, 60, 12); //numerico
        $vetor["dv_conta"] = substr($linha, 72, 1); //alfanumerico
        $vetor["dv_ag_conta"] = substr($linha, 73, 1); //alfanumerico //Dígito Verificador da Ag/Conta
        $vetor["nome_empresa"] = substr($linha, 74, 30); //alfanumerico
        $vetor["mensagem1"] = substr($linha, 104, 40); //alfanumerico
        $vetor["mensagem2"] = substr($linha, 144, 40); //alfanumerico

        $vetor["numero_remessa_retorno"] = substr($linha, 184, 8); // numerico // numero do retorno
        $vetor["data_remessa_retorno"] = substr($linha, 192, 8); // numerico // numero do retorno
        $vetor["data_credito"] = substr($linha, 200, 8); // numerico // numero do retorno
        $vetor["cnab"] = substr($linha, 208, 33); // alfa - default brancos //Uso Exclusivo da FEBRABAN/CNAB
        return $vetor;
    }

    protected function processarDetalhe($linha)
    {
        $segmento = substr($linha, 14, 1);
        if ($segmento == "T"){
            return $this -> processarDetalheSegmentoT($linha);
        }elseif ($segmento == "U") {
            return $this -> processarDetalheSegmentoU($linha);
        }
    }

    protected function processarDetalheSegmentoU($linha) {
        //LIQUIDACAO_TITULOS_CARTEIRA_COBRANCA - SEGMENTO U (Pagamento de Títulos de Cobrança) REMESSA/RETORNO
        $vetor = array();
        $vetor["banco"] = substr($linha, 1, 3); //   Num //Código no Banco da Compensação
        $vetor["lote"] = substr($linha, 4, 4); //   Num //Lote de Serviço
        $vetor["registro"] = substr($linha, 8, 1); //   Num  default '3' //Tipo de Registro
        $vetor["num_registro_lote"] = substr($linha, 9, 5); //   Num  //No Sequencial do Registro no Lote
        $vetor["segmento"] = substr($linha, 14, 1); //   Alfa  default 'J' //Código de Segmento no Reg. Detalhe
        $vetor["tipo_movimento"] = substr($linha, 15, 1); //   Num //Tipo de Movimento
        $vetor["cod_movimento"] = substr($linha, 16, 2); //   Num  //Código da Instrução p/ Movimento
        $vetor["acrescimos"] = substr($linha, 18, 15); //   Num, 2 casas decimais //Valor da Mora + Multa
        $vetor["desconto"] = substr($linha, 33, 15); //   Num, 2 casas decimais //Valor do Desconto
        $vetor["abatimento"] = substr($linha, 48, 15); //   Num, 2 casas decimais //Valor Abatimento
        $vetor["iof"] = substr($linha, 63, 15); //   Num, 2 casas decimais //Valor IOF
        $vetor["valor_pagamento"] = substr($linha, 78, 15); //   Num, 2 casas decimais
        $vetor["valor_liquido"] = substr($linha, 93, 15); //   Num, 2 casas decimais
        $vetor["outras_despesas"] = substr($linha, 108, 15); //   Num, 2 casas decimais
        $vetor["outros_creditos"] = substr($linha, 123, 15); //   Num, 2 casas decimais
        $vetor["data_ocorrencia"] = substr($linha, 138, 8); //   Num
        $vetor["data_credito"] = substr($linha, 146, 8); //   Num
        $vetor["codigo_ocorrencia_pag"] = substr($linha, 154, 4); //   Alfa
        $vetor["data_ocorrencia_pag"] = substr($linha, 158, 8); //   Alfa
        $vetor["valor_ocorrencia_pag"] = substr($linha, 166, 13); //   Num
        $vetor["compl_ocorrencia_pag"] = substr($linha, 181, 30); //   Alfa
        $vetor["cod_banco_correspondente"] = substr($linha, 211, 3); //   Num
        $vetor["num_banco_correspondente"] = substr($linha, 214, 20); //   Num
        $vetor["cnab"] = substr($linha, 234, 7); //   Alfa - default Brancos //Uso Exclusivo FEBRABAN/CNAB
        return $vetor;
    }


    protected function processarDetalheSegmentoT($linha) {
        //LIQUIDACAO_TITULOS_CARTEIRA_COBRANCA - SEGMENTO T (Pagamento de Títulos de Cobrança) REMESSA/RETORNO
        $vetor = array();
        $vetor["banco"] = substr($linha, 1, 3); //   Num //Código no Banco da Compensação
        $vetor["lote"] = substr($linha, 4, 4); //   Num //Lote de Serviço
        $vetor["registro"] = substr($linha, 8, 1); //   Num  default '3' //Tipo de Registro
        $vetor["num_registro_lote"] = substr($linha, 9, 5); //   Num  //No Sequencial do Registro no Lote
        $vetor["segmento"] = substr($linha, 14, 1); //   Alfa  default 'J' //Código de Segmento no Reg. Detalhe
        $vetor["tipo_movimento"] = substr($linha, 15, 1); //   Num //Tipo de Movimento
        $vetor["cod_movimento"] = substr($linha, 16, 2); //   Num  //Código da Instrução p/ Movimento
        $vetor["agencia"] = substr($linha, 18, 5); //numerico //Agência Mantenedora da Conta
        $vetor["dv_agencia"] = substr($linha, 23, 1); //alfanumerico //DV da Agência Mantenedora da Conta
        $vetor["conta_corrente"] = substr($linha, 24, 12); //numerico
        $vetor["dv_conta"] = substr($linha, 36, 1); //alfanumerico
        $vetor["dv_ag_conta"] = substr($linha, 37, 1); //alfanumerico //Dígito Verificador da Ag/Conta
        $vetor["nosso_numero"] = substr($linha, 38, 20); //   Alfa //Num. do Documento Atribuído pelo Banco
        $vetor["carteira"] = substr($linha, 58, 1); //   Alfa //Num. do Documento Atribuído pelo Banco
        $vetor["numero_documento"] = substr($linha, 59, 15); //   Alfa //Num. do Documento Atribuído pela Empresa
        $vetor["data_vencimento"] = substr($linha, 74, 8); //   Num  //Data do Vencimento (Nominal)
        $vetor["valor_titulo"] = substr($linha, 82, 15); //   Num, 2 casas decimais //Valor do Título (Nominal)
        $vetor["banco_recebedor"] = substr($linha, 97, 3); //  Num, Banco Recebedor
        $vetor["ag_cobradora"] = substr($linha, 100, 5); //  Num, Agencia Cobradora
        $vetor["dv_ag_cobradora"] = substr($linha, 105, 1); //  Num, DV Agencia Cobradora
        $vetor["referencia_sacado"] = substr($linha, 106, 25); // Alfa,  Identificação do Título na Empresa
        $vetor["cod_moeda"] = substr($linha, 131, 2); //   Num, codigo da moeda
        $vetor["tipo_inscricao_pagador"] = substr($linha, 133, 1); //num - 1-CPF, 2-CGC //Tipo de Inscrição do Pagador
        $vetor["num_inscricao_pagador"] = substr($linha, 134, 15); //numerico  //Número de Inscrição do Pagador
        $vetor["nome_pagador"] = utf8_decode(substr($linha, 149, 40)); // alfa  Nome Pagador
        $vetor["numero_contrato"] = substr($linha, 189, 10); // num, Numero do contrato
        $vetor["valor_tarifas"] = substr($linha, 199, 15); // num, Numero do contrato
        $vetor["ocorrencias"] = substr($linha, 214, 10); //   Alfa //Códigos das Ocorrências p/ Retorno
        $vetor["cnab"] = substr($linha, 224, 17); //   Alfa - default Brancos //Uso Exclusivo FEBRABAN/CNAB
        return $vetor;
    }

    protected function processarTrailerLote($linha) {
        $vetor = array();
        $vetor["banco"] = substr($linha, 1, 3); //numerico  //Código do Banco na Compensação
        $vetor["lote"] = substr($linha, 4, 4); //numerico //Lote de Serviço
        $vetor["registro"] = substr($linha, 8, 1); //num - default 5 //Tipo de Registro
        $vetor["cnab1"] = substr($linha, 9, 9); //alfa - default brancos Uso Exclusivo FEBRABAN/CNAB
        $vetor["quant_regs"] = substr($linha, 18, 6); //numerico //Quantidade de Registros do Lote
        $vetor["quant_regs_simples"] = substr($linha, 24, 6); //numerico //Quantidade de Registros do Lote
        $vetor["valor_simples"] = substr($linha, 30, 17); //numerico, 2 casas decimais  //Somatória dos Valores
        $vetor["quant_regs_vinculada"] = substr($linha, 47, 6); //numerico //Quantidade de Registros do Lote
        $vetor["valor_vinculada"] = substr($linha, 53, 17); //numerico, 2 casas decimais  //Somatória dos Valores
        $vetor["quant_regs_caucionada"] = substr($linha, 70, 6); //numerico //Quantidade de Registros do Lote
        $vetor["valor_caucionada"] = substr($linha, 76, 17); //numerico, 2 casas decimais  //Somatória dos Valores
        $vetor["quant_regs_descontada"] = substr($linha, 93, 6); //numerico //Quantidade de Registros do Lote
        $vetor["valor_descontada"] = substr($linha, 99, 17); //numerico, 2 casas decimais  //Somatória dos Valores
        $vetor["num_aviso_debito"] = substr($linha, 116, 8); //numerico //Número Aviso de Débito
        $vetor["cnab2"] = substr($linha, 124, 117); //alfa, default brancos //Uso Exclusivo FEBRABAN/CNAB
        return $vetor;
    }

    protected function processarTrailerArquivo($linha) {
        $vetor = array();
        $vetor["banco"] = substr($linha, 1, 3); //numerico  //Código do Banco na Compensação
        $vetor["lote"] = substr($linha, 4, 4); // num - default 9999  //Lote de Serviço
        $vetor["registro"] = substr($linha, 8, 1); //num - default 9   //Tipo de Registro
        $vetor["cnab1"] = substr($linha, 9, 9); //alpha - default brancos //Uso Exclusivo FEBRABAN/CNAB
        $vetor["quant_lotes"] = substr($linha, 18, 6); //num. //Quantidade de Lotes do Arquivo
        $vetor["quant_regs"] = substr($linha, 24, 6); //num. //Quantidade de Registros do Arquivo
        $vetor["quant_contas_conc"] = substr($linha, 30, 6); //num. //Qtde de Contas p/ Conc. (Lotes)
        $vetor["cnab2"] = substr($linha, 36, 205); //alpha - default brancos  //Uso Exclusivo FEBRABAN/CNAB

        return $vetor;
    }
}
