<?php
require_once("RetornoBase.php");

/**Classe para leitura de arquivos de retorno de cobranças no padrão CNAB240.<br/>
* Layout Padrão Febraban 240 posições V08.4 de 01/09/2009<br/>
* http://www.febraban.org.br
* @copyright GPLv2
* @package ArquivoRetornoTitulosBancarios
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.2
* @modified 01/04/2011 por Bento David Ribeiro Silva
*/
class RetornoCNAB240 extends RetornoBase {
	/**@property int HEADER_ARQUIVO Define o valor que identifica uma coluna do tipo HEADER DE ARQUIVO*/
	const HEADER_ARQUIVO = 0;
  /**@property int HEADER_LOTE Define o valor que identifica uma coluna do tipo HEADER DE LOTE*/
	const HEADER_LOTE = 1;
  /**@property int DETALHE Define o valor que identifica uma coluna do tipo DETALHE*/
	const DETALHE = 3;
	const DETALHE_T = "T";
	const DETALHE_U = "U";
  /**@property int TRAILER_LOTE Define o valor que identifica uma coluna do tipo TRAILER DEs LOTE*/
	const TRAILER_LOTE = 5;
  /**@property int TRAILER_ARQUIVO Define o valor que identifica uma coluna do tipo TRAILER DE ARQUIVO*/
	const TRAILER_ARQUIVO = 9;

  public function __construct($nomeArquivo=NULL, $aoProcessarLinhaFunctionName=""){
       parent::__construct($nomeArquivo, $aoProcessarLinhaFunctionName);
  }

  protected function processarHeaderArquivo($linha) {
    $vlinha = array();
		$vlinha["banco"]					= substr($linha,  1,   3); //NUMERICO //Código do Banco na Compensação
	  $vlinha["lote"]						= substr($linha,  4,   4); //num - default 0000 //Lote de Serviço
	  $vlinha["registro"]					= substr($linha,  8,   1); //num - default 0 //Tipo de Registro
		$vlinha["cnab1"]					= substr($linha,  9,   9); //BRANCOS //Uso Exclusivo FEBRABAN / CNAB
		$vlinha["tipo_inscricao_empresa"]	= substr($linha, 18,   1); //num - 1-CPF, 2-CGC //Tipo de Inscrição da Empresa
		$vlinha["num_inscricao_empresa"]  	= substr($linha, 19,  14); //numerico  //Número de Inscrição da Empresa
		$vlinha["convenio"]				= substr($linha, 33,  20); //alfanumerico  //Código do Convênio no Banco
		$vlinha["agencia"]					= substr($linha, 53,   5); //numerico //Agência Mantenedora da Conta
		$vlinha["dv_agencia"]				= substr($linha, 58,   1); //alfanumerico //DV da Agência
		$vlinha["conta_corrente"]			= substr($linha, 59,  12); //numerico //Número da Conta Corrente
		$vlinha["dv_conta"]					= substr($linha, 71,   1); //alfanumerico  //DV da Conta Corrent
		$vlinha["dv_ag_conta"]				= substr($linha, 72,   1); //alfanumerico 
		$vlinha["nome_empresa"]				= substr($linha, 73,  30); //alfanumerico 
		$vlinha["nome_banco"]				= substr($linha, 103, 30); //alfanumerico 
		$vlinha["uso_febraban_cnab2"] 		= substr($linha, 133, 10); //brancos //Uso Exclusivo FEBRABAN / CNAB
		$vlinha["cod_arq"]					= substr($linha, 143,  1); //num - 1-REM E 2-RET ?? //Código do arquivo de remessa/retorno
		$vlinha["data_geracao_arq"] 		= substr($linha, 144,  8); //num - formato ddmmaaaa
		$vlinha["hora_geracao_arq"] 		= substr($linha, 152,  6); //num - formato hhmmss
		$vlinha["sequencia"] 				= substr($linha, 158,  6); //numerico //Número Sequencial do Arquivo
		$vlinha["versao_layout_arq"] 		= substr($linha, 164,  3); //num 084 //Num da Versão do Layout do Arquivo
		$vlinha["densidade"]				= substr($linha, 167,  5); //numerico //Densidade de Gravação do Arquivo
		$vlinha["reservado_banco"] 			= substr($linha, 172, 20); //alfanumerico //Para Uso Reservado do Banco
		$vlinha["reservado_empresa"] 		= substr($linha, 192, 20); //alfanumerico //Para Uso Reservado da Empresa
		$vlinha["uso_febraban_cnab3"] 		= substr($linha, 212, 29); //brancos //Uso Exclusivo FEBRABAN / CNAB
	  return $vlinha;
	}

  protected function processarHeaderLote($linha) {
	  $vlinha = array();
		$vlinha["banco"]				= substr($linha,   1,  3); //Código do Banco na Compensação
	  $vlinha["lote"]					= substr($linha,   4,  4); //Lote de Serviço
	  $vlinha["registro"]				= substr($linha,   8,  1); //Tipo de Registro
		$vlinha["operacao"]				= substr($linha,   9,  1); //Tipo de Operação
		$vlinha["servico"]				= substr($linha,  10,  2); //Tipo de Serviço
		$vlinha["servico_CNAB"]			= substr($linha,  12,  2); //Uso Exclusivo FEBRABAN/CNAB
		$vlinha["layout_lote"]			= substr($linha,  14,  3); //Nº da Versão do Layout do Lote
		$vlinha["CNAB"]					= substr($linha,  17,  1); //Uso Exclusivo FEBRABAN/CNAB
		$vlinha["inscricao_tipo"]		= substr($linha,  18,  1); //Tipo de Inscrição da Empresa
		$vlinha["inscricao_numero"]		= substr($linha,  19, 15); //Nº de Inscrição da Empresa
		$vlinha["convenio"]				= substr($linha,  34, 20); //Código do Convênio no Banco
		$vlinha["agencia"]				= substr($linha,  54,  5); //Agência Mantenedora da Conta
		$vlinha["dv_agencia"]			= substr($linha,  59,  1); //Dígito Verificador da Conta
		$vlinha["conta"]				= substr($linha,  60, 12); //Número da Conta Corrente
		$vlinha["dv_conta"] 			= substr($linha,  72,  1); //Dígito Verificador da Conta
		$vlinha["dv"]					= substr($linha,  73,  1); //Dígito Verificador da Ag/Conta
		$vlinha["nome_empresa"]			= substr($linha,  74, 30); //Nome da Empresa
		$vlinha["informacao1"]			= substr($linha, 104, 40); //Mensagem 1
		$vlinha["informacao2"]			= substr($linha, 144, 40); //Mensagem 2
		$vlinha["n_retorno"]			= substr($linha, 184,  8); //Número Remessa/Retorno
		$vlinha["data_retorno"]			= $this->formataData(substr($linha, 192,  8)); //Data de Gravação Remessa/Retorno
		$vlinha["data_credito"]			= $this->formataData(substr($linha, 200,  8)); //Data do Crédito
		$vlinha["CNAB2"]				= substr($linha, 208, 33); //Uso Exclusivo FEBRABAN/CNAB 
	return $vlinha; 
  }

	protected function processarDetalheT($linha) {
		  $vlinha = array();
			$vlinha["banco"] 				= substr($linha,   1,  3); //Código do Banco na Compensação
			$vlinha["lote"] 				= substr($linha,   4,  4); //Lote de Serviço
			$vlinha["registro"] 			= substr($linha,   8,  1); //Tipo de Registro
			$vlinha["sequencial"] 			= substr($linha,   9,  5); //Número Sequencial Registro no Lote
			$vlinha["segmento"] 			= substr($linha,  14,  1); //Código Segmento do Registro Detalhe
			$vlinha["servico_CNAB"] 		= substr($linha,  15,  1); //Uso Exclusivo FEBRABAN/CNAB
			$vlinha["cod_mov"] 				= substr($linha,  16,  2); //Código de Movimento Retorno
			$vlinha["agencia"] 				= substr($linha,  15,  5); //Agência Mantenedora da Conta
			$vlinha["dv_agencia"] 			= substr($linha,  23,  1); //Dígito Verificador da Agência
			$vlinha["conta"] 				= substr($linha,  24, 12); //Número da Conta Corrente
			$vlinha["dv_conta"]				= substr($linha,  36,  1); //Dígito Verificador da Conta
			$vlinha["dv"] 					= substr($linha,  37,  1); //Dígito Verificador da Ag/Conta
			$vlinha["nosso_numero"]	 		= substr($linha,  38,  20); //Identificação do Título
			$vlinha["carteira"] 			= substr($linha,  58,  1); //Código da Carteira
			$vlinha["n_documento"] 			= substr($linha,  59, 15); //Número do Documento de Cobrança
			$vlinha["vencimento"] 			= $this->formataData(substr($linha,  74,  8)); //Data do Vencimento do Título
			$vlinha["valor"] 		= $this->formataNumero(substr($linha,  82, 15)); //Valor Nominal do Título
			$vlinha["banco_receb"] 			= substr($linha,  97,  3); //Número do Banco
			$vlinha["ag_receb"] 			= substr($linha, 100,  5); //Agência Cobradora/Recebedora
			$vlinha["dv_receb"] 			= substr($linha, 105,  1); //Dígito Verificador da Agência
			$vlinha["uso_empresa"] 			= substr($linha, 106, 25); //Identificação do Título na Empresa
			$vlinha["moeda"] 				= substr($linha, 131,  2); //Código da Moeda
			$vlinha["sacado_tipo"] 			= substr($linha, 133,  1); //Tipo de Inscrição
			$vlinha["sacado_numero"] 		= substr($linha, 134, 15); //Número de Inscrição
			$vlinha["sacado_nome"] 			= substr($linha, 149, 40); //Nome
			$vlinha["n_contrato"] 			= substr($linha, 189, 10); //Nº do Contr. da Operação de Crédito
			$vlinha["valor_tarifa"] 		= $this->formataNumero(substr($linha, 199, 15)); //Valor da Tarifa / Custas
			$vlinha["motivo_ocorrencia"] 	= substr($linha, 214, 10); //Identificação para Rejeições, Tarifas, Custas, Liquidação e Baixas
			$vlinha["CNAB"] 				= substr($linha, 224, 17); //Uso Exclusivo FEBRABAN/CNAB
		return $vlinha;
	}


	protected function processarDetalheU($linha) {
	  	$vlinha = array();
			$vlinha["banco"] 				= substr($linha,   1,  3); //Código do Banco na Compensação
			$vlinha["lote"] 				= substr($linha,   4,  4); //Lote de Serviço
			$vlinha["registro"] 			= substr($linha,   8,  1); //Tipo de Registro
			$vlinha["sequencial"] 			= substr($linha,   9,  5); //Nº Sequencial do Registro no Lote
			$vlinha["segmento"] 			= substr($linha,  14,  1); //Cód. Segmento do Registro Detalhe
			$vlinha["servico_CNAB"] 		= substr($linha,  15,  1); //Uso Exclusivo FEBRABAN/CNAB
			$vlinha["cod_mov"] 				= substr($linha,  16,  2); //Código de Movimento Retorno
			$vlinha["acrescimos"] 			= $this->formataNumero(substr($linha,  18, 15)); //Juros / Multa / Encargos
			$vlinha["valor_desconto"] 		= $this->formataNumero(substr($linha,  33, 15)); //Valor do Desconto Concedido
			$vlinha["valor_abatimento"] 	= $this->formataNumero(substr($linha,  48, 15)); //Valor do Abat. Concedido/Cancel.
			$vlinha["IOF"] 					= $this->formataNumero(substr($linha,  63, 15)); //Valor do IOF Recolhido
			$vlinha["valor_pago"] 			= $this->formataNumero(substr($linha,  78, 15)); //Valor Pago pelo Sacado
			$vlinha["valor_liquido"] 		= $this->formataNumero(substr($linha,  93, 15)); //Valor Líquido a ser Creditado
			$vlinha["despesas"] 			= $this->formataNumero(substr($linha, 108, 15)); //Valor de Outras Despesas
			$vlinha["creditos"] 			= $this->formataNumero(substr($linha, 123, 15)); //Valor de Outros Créditos
			
			$vlinha["data_ocorrencia"] 		= $this->formataData(substr($linha, 138,  8)); //Data da Ocorrência
			$vlinha["data_credito"] 		= $this->formataData(substr($linha, 146,  8)); //Data da Efetivação do Crédito
			$vlinha["cod_ocorrencia_sac"] 				= substr($linha, 154,  4); //Código da Ocorrência
			$vlinha["data_ocorrencia_sac"] 		= $this->formataData(substr($linha, 158,  8)); //Data da Ocorrência
			$vlinha["valor_ocorrencia_sac"] 	= $this->formataNumero(substr($linha, 166, 15)); //Valor da Ocorrência
			$vlinha["compl_ocorrencia_sac"] 	= substr($linha, 181, 30); //Complem. da Ocorrência
			$vlinha["cod_bco_corr"] 		= substr($linha, 211,  3); //Cód. Banco Correspondente Compens.
			$vlinha["n_bco_corr"] 			= substr($linha, 214, 20); //Nosso Nº Banco Correspondente
			$vlinha["CNAB"] 				= substr($linha, 234,  7); //Uso Exclusivo FEBRABAN/CNAB
		  return $vlinha;
	}

	protected function processarTrailerLote($linha) {
		  $vlinha = array();
			$vlinha["banco"]            = substr($linha,  1,    3); //numerico  //Código do Banco na Compensação
			$vlinha["lote"]             = substr($linha,  4,    4); //numerico //Lote de Serviço
			$vlinha["registro"]         = substr($linha,  8,    1); //num - default 5 //Tipo de Registro
			$vlinha["cnab1"]            = substr($linha,  9,    9); //alfa - default brancos Uso Exclusivo FEBRABAN/CNAB
			$vlinha["quant_regs"]       = substr($linha, 18,    6); //numerico //Quantidade de Registros do Lote
			$vlinha["valor"]			= substr($linha, 24,   16); //numerico, 2 casas decimais  //Somatória dos Valores
			$vlinha["quant_moedas"]		= substr($linha, 42,   13); //numerico, 5 casas decimais  //Somatória de Quantidade de Moedas
			$vlinha["num_aviso_debito"] = substr($linha, 60,    6); //numerico //Número Aviso de Débito
			$vlinha["cnab2"]			= substr($linha, 66,  165); //alfa, default brancos //Uso Exclusivo FEBRABAN/CNAB
			$vlinha["ocorrencias"]		= substr($linha, 231,  10); //alfa  //Códigos das Ocorrências para Retorno
    	return $vlinha;
  }

	protected function processarTrailerArquivo($linha) {
		  $vlinha = array();
			$vlinha["banco"]             	= substr($linha,  1,  3); //numerico  //Código do Banco na Compensação
			$vlinha["lote"]              	= substr($linha,  4,  4); // num - default 9999  //Lote de Serviço
			$vlinha["registro"]          	= substr($linha,  8,  1); //num - default 9   //Tipo de Registro           
			$vlinha["cnab1"]             	= substr($linha,  9,  9); //alpha - default brancos //Uso Exclusivo FEBRABAN/CNAB     
			$vlinha["quant_lotes"]       	= substr($linha, 18,  6); //num. //Quantidade de Lotes do Arquivo
			$vlinha["quant_regs"]        	= substr($linha, 24,  6); //num. //Quantidade de Registros do Arquivo
			$vlinha["quant_contas_conc"]	= substr($linha, 30,  6); //num. //Qtde de Contas p/ Conc. (Lotes)
			$vlinha["cnab2"]				= substr($linha, 36,205); //alpha - default brancos  //Uso Exclusivo FEBRABAN/CNAB   
		  return $vlinha;
	}

	/**Processa uma linha do arquivo de retorno.
  * @param int $numLn Número da linha a ser processada
	* @param string $linha String contendo a linha a ser processada
	* @return array Retorna um vetor associativo contendo os valores da linha processada.*/
	public function processarLinha($numLn, $linha) {
    //é adicionado um espaço vazio no início_linha para que
		//possamos trabalhar com índices iniciando em 1, no lugar de zero,
		//e assim, ter os valores de posição dos campos exatamente
		//como no manual CNAB240
		$linha = " $linha";
		
    $tipoLn = substr($linha,  8,  1);
	  //echo "$tipoLn<br/>";

    if(strcmp($tipoLn,RetornoCNAB240::HEADER_ARQUIVO)==0)
          $vlinha = $this->processarHeaderArquivo($linha);
    else if(strcmp($tipoLn, RetornoCNAB240::HEADER_LOTE)==0)
          $vlinha = $this->processarHeaderLote($linha);
    else if(strcmp($tipoLn, RetornoCNAB240::DETALHE)==0) {
       $tipoDetalhe = substr($linha, 14,  1);
       if(strcmp($tipoDetalhe, static::DETALHE_T)==0)
	        $vlinha = $this->processarDetalheT($linha); 
       else if(strcmp($tipoDetalhe, static::DETALHE_U)==0)
          $vlinha = $this->processarDetalheU($linha);
    }
    else if(strcmp($tipoLn, RetornoCNAB240::TRAILER_LOTE)==0)
          $vlinha = $this->processarTrailerLote($linha); 
    else if(strcmp($tipoLn,RetornoCNAB240::TRAILER_ARQUIVO)==0)
          $vlinha = $this->processarTrailerArquivo($linha); 
    else $vlinha = NULL;
	    
    return $vlinha;
  }
}

?>
