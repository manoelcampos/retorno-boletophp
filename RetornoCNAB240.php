<?php 
require_once("RetornoBase.php");

/**Classe para leitura_arquivos_retorno_cobranças_padrão CNAB240.<br/>
* Layout Padrão Febraban 240 posições V08.4 de 01/09/2009<br/>
* http://www.febraban.org.br
* @copyright GPLv2
* @package ArquivoRetornoTitulosBancarios
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.1
*/
class RetornoCNAB240 extends RetornoBase {
	/**@property int HEADER_ARQUIVO Define o valor que identifica uma coluna do tipo HEADER DE ARQUIVO*/
	const HEADER_ARQUIVO = 0;
	/**@property int HEADER_LOTE Define o valor que identifica uma coluna do tipo HEADER DE LOTE*/
	const HEADER_LOTE = 1;
	/**@property int DETALHE Define o valor que identifica uma coluna do tipo DETALHE*/
	const DETALHE = 3;
	/**@property int TRAILER_LOTE Define o valor que identifica uma coluna do tipo TRAILER DEs LOTE*/
	const TRAILER_LOTE = 5;
	/**@property int TRAILER_ARQUIVO Define o valor que identifica uma coluna do tipo TRAILER DE ARQUIVO*/
	const TRAILER_ARQUIVO = 9;

  function processarHeaderArquivo($linha) {
    $vlinha = array();
		$vlinha["banco"] 				  		    = substr($linha,  1,   3); //NUMERICO //Código do Banco na Compensação
	  $vlinha["lote"]			  		        = substr($linha,  4,   4); //num - default 0000 //Lote de Serviço
	  $vlinha["registro"]     		      = substr($linha,  8,   1); //num - default 0 //Tipo de Registro
		$vlinha["cnab1"]     						  = substr($linha,  9,   9); //BRANCOS //Uso Exclusivo FEBRABAN / CNAB
		$vlinha["tipo_inscricao_empresa"] = substr($linha, 18,   1); //num - 1-CPF, 2-CGC //Tipo de Inscrição da Empresa
		$vlinha["num_inscricao_empresa"]  = substr($linha, 19,  14); //numerico  //Número de Inscrição da Empresa
		$vlinha["cod_convenio"] 					= substr($linha, 33,  20); //alfanumerico  //Código do Convênio no Banco
		$vlinha["agencia"] 							  = substr($linha, 53,   5); //numerico //Agência Mantenedora da Conta
		$vlinha["dv_agencia"] 						= substr($linha, 58,   1); //alfanumerico //DV da Agência
		$vlinha["conta_corrente"] 				= substr($linha, 59,  12); //numerico //Número da Conta Corrente
		$vlinha["dv_conta"] 							= substr($linha, 71,   1); //alfanumerico  //DV da Conta Corrent
		$vlinha["dv_ag_conta"]					  = substr($linha, 72,   1); //alfanumerico 
		$vlinha["nome_empresa"] 					= substr($linha, 73,  30); //alfanumerico 
		$vlinha["nome_banco"] 						= substr($linha, 103, 30); //alfanumerico 
		$vlinha["uso_febraban_cnab2"] 		= substr($linha, 133, 10); //brancos //Uso Exclusivo FEBRABAN / CNAB
		$vlinha["cod_arq"] 								= substr($linha, 143,  1); //num - 1-REM E 2-RET ?? //Código do arquivo de remessa/retorno
		$vlinha["data_geracao_arq"] 			= substr($linha, 144,  8); //num - formato ddmmaaaa
		$vlinha["hora_geracao_arq"] 			= substr($linha, 152,  6); //num - formato hhmmss
		$vlinha["sequencia"] 							= substr($linha, 158,  6); //numerico //Número Sequencial do Arquivo
		$vlinha["versao_layout_arq"] 			= substr($linha, 164,  3); //num 084 //Num da Versão do Layout do Arquivo
		$vlinha["densidade"]						  = substr($linha, 167,  5); //numerico //Densidade de Gravação do Arquivo
		$vlinha["reservado_banco"] 				= substr($linha, 172, 20); //alfanumerico //Para Uso Reservado do Banco
		$vlinha["reservado_empresa"] 			= substr($linha, 192, 20); //alfanumerico //Para Uso Reservado da Empresa
		$vlinha["uso_febraban_cnab3"] 		= substr($linha, 212, 29); //brancos //Uso Exclusivo FEBRABAN / CNAB
	  return $vlinha;
	}

	function processarHeaderLote($linha) {
    //SEGMENTO J - Pagamento de Títulos de Cobrança
		$vlinha = array();
		$vlinha["banco"] 		                = substr($linha,  1,  3); //numerico //Código do Banco na Compensação
		$vlinha["lote"]                     = substr($linha,  4,  4); //numerico //Lote de Serviço
		$vlinha["registro"]                 = substr($linha,  8,  1); //num - default 1 //Tipo de Registro
		$vlinha["operacao"]                 = substr($linha,  9,  1); //alfanumerico - default C //Tipo da Operação
		$vlinha["servico"]                  = substr($linha, 10,  2); //num  //Tipo do Serviço
		$vlinha["forma_lancamento"]         = substr($linha, 12,  2); //num //Forma de Lançamento
		$vlinha["layout_lote"]              = substr($linha, 14,  3); //num - default '030' //No da Versão do Layout do Lote
		$vlinha["cnab1"]                    = substr($linha, 17,  1); //alfa - default brancos  //Uso Exclusivo da FEBRABAN/CNAB

		$vlinha["tipo_inscricao_empresa"]   = substr($linha, 18,  1); //num - 1-CPF, 2-CGC //Tipo de Inscrição da Empresa
		$vlinha["num_inscricao_empresa"]    = substr($linha, 19, 14); //numerico //Número de Inscrição da Empresa
		$vlinha["cod_convenio"]             = substr($linha, 33, 20); //alfanumerico //Código do Convênio no Banco

		$vlinha["agencia"]       		        = substr($linha, 53,  5); //numerico //Agência Mantenedora da Conta
		$vlinha["dv_agencia"]               = substr($linha, 58 , 1); //alfanumerico //DV da Agência Mantenedora da Conta
		$vlinha["conta_corrente"] 					= substr($linha, 59, 12); //numerico 
		$vlinha["dv_conta"] 								= substr($linha, 71,  1); //alfanumerico 
		$vlinha["dv_ag_conta"] 							= substr($linha, 72,  1); //alfanumerico //Dígito Verificador da Ag/Conta
		$vlinha["nome_empresa"]							= substr($linha, 73, 30); //alfanumerico 
		$vlinha["mensagem1"]								= substr($linha,103, 40); //alfanumerico 

		$vlinha["logradouro_empresa"]				= substr($linha,143, 30); //alfa //Logradouro da Empresa - Nome da Rua, Av, Pça, Etc
    $vlinha["numero_empresa"]					  = substr($linha,173,  5); //num //Número do endereço da empresa
    $vlinha["complemento_empresa"]			= substr($linha,178, 15); //alfa //Complemento - Casa, Apto, Sala, Etc
    $vlinha["cidade_empresa"]					  = substr($linha,193, 20); //alfa //Cidade da Empresa
    $vlinha["cep_empresa"]					    = substr($linha,213,  5); //num //5 primeiros dígitos do CEP da Empresa
    $vlinha["complemento_cep_empresa"]	= substr($linha,218,  3); //alfa //3 últimos dígitos do CEP da empresa
    $vlinha["estado"]					          = substr($linha,221,  2); //  alfa  //Sigla do Estado
    $vlinha["cnab"]					            = substr($linha,223,  8); // alfa - default brancos //Uso Exclusivo da FEBRABAN/CNAB 
    $vlinha["ocorrencias"]					    = substr($linha,231, 10); //alfa //Código das Ocorrências p/ Retorno  

		return $vlinha; 
	}

	function processarDetalhe($linha) {
    //LIQUIDACAO_TITULOS_CARTEIRA_COBRANCA - SEGMENTO J (Pagamento de Títulos de Cobrança) REMESSA/RETORNO
		$vlinha = array();
		$vlinha["banco"]             = substr($linha,   1,  3); //   Num //Código no Banco da Compensação     
		$vlinha["lote"]              = substr($linha,   4,  4); //   Num //Lote de Serviço                    
		$vlinha["registro"]          = substr($linha,   8,  1); //   Num  default '3' //Tipo de Registro                   
		$vlinha["num_registro_lote"] = substr($linha,   9,  5); //   Num  //No Sequencial do Registro no Lote  
		$vlinha["segmento"]          = substr($linha,  14,  1); //   Alfa  default 'J' //Código de Segmento no Reg. Detalhe 
		$vlinha["tipo_movimento"]    = substr($linha,  15,  1); //   Num //Tipo de Movimento 
		$vlinha["cod_movimento"]     = substr($linha,  16,  2); //   Num  //Código da Instrução p/ Movimento   
		$vlinha["cod_barras"]        = substr($linha,  18, 44); //   Num           
		$vlinha["nome_cedente"]      = substr($linha,  62, 30); //   Alfa          
		$vlinha["data_vencimento"]   = substr($linha,  92,  8); //   Num  //Data do Vencimento (Nominal)       
		$vlinha["valor_titulo"]      = substr($linha, 100, 13); //   Num, 2 casas decimais //Valor do Título (Nominal)          
		$vlinha["desconto"]          = substr($linha, 115, 13); //   Num, 2 casas decimais //Valor do Desconto + Abatimento     
		$vlinha["acrescimos"]        = substr($linha, 130, 13); //   Num, 2 casas decimais //Valor da Mora + Multa              
		$vlinha["data_pagamento"]    = substr($linha, 145,  8); //   Num           
		$vlinha["valor_pagamento"]   = substr($linha, 153, 13); //   Num, 2 casas decimais
		$vlinha["quantidade_moeda"]  = substr($linha, 168, 10); //   Num, 5 casas decimais
		$vlinha["referencia_sacado"] = substr($linha, 183, 20); //   Alfa //Num. do Documento Atribuído pela Empresa 
		$vlinha["nosso_numero"]      = substr($linha, 203, 20); //   Alfa //Num. do Documento Atribuído pelo Banco
		$vlinha["cod_moeda"]         = substr($linha, 223,  2); //   Num 
		$vlinha["cnab"]              = substr($linha, 225,  6); //   Alfa - default Brancos //Uso Exclusivo FEBRABAN/CNAB
		$vlinha["ocorrencias"]       = substr($linha, 231, 10); //   Alfa //Códigos das Ocorrências p/ Retorno
		return $vlinha;
	}

	function processarTrailerLote($linha) {
		$vlinha = array();
		$vlinha["banco"]            = substr($linha,  1,    3); //numerico  //Código do Banco na Compensação
		$vlinha["lote"]             = substr($linha,  4,    4); //numerico //Lote de Serviço
		$vlinha["registro"]         = substr($linha,  8,    1); //num - default 5 //Tipo de Registro
		$vlinha["cnab1"]            = substr($linha,  9,    9); //alfa - default brancos Uso Exclusivo FEBRABAN/CNAB
		$vlinha["quant_regs"]       = substr($linha, 18,    6); //numerico //Quantidade de Registros do Lote
		$vlinha["valor"]      		  = substr($linha, 24,   16); //numerico, 2 casas decimais  //Somatória dos Valores
    $vlinha["quant_moedas"]     = substr($linha, 42,   13); //numerico, 5 casas decimais  //Somatória de Quantidade de Moedas
		$vlinha["num_aviso_debito"] = substr($linha, 60,    6); //numerico //Número Aviso de Débito
    $vlinha["cnab2"]      		  = substr($linha, 66,  165); //alfa, default brancos //Uso Exclusivo FEBRABAN/CNAB
    $vlinha["ocorrencias"]      = substr($linha, 231,  10); //alfa  //Códigos das Ocorrências para Retorno
    return $vlinha;
  }

	function processarTrailerArquivo($linha) {
		$vlinha = array();
		$vlinha["banco"]             = substr($linha,  1,  3); //numerico  //Código do Banco na Compensação
		$vlinha["lote"]              = substr($linha,  4,  4); // num - default 9999  //Lote de Serviço
		$vlinha["registro"]          = substr($linha,  8,  1); //num - default 9   //Tipo de Registro           
		$vlinha["cnab1"]             = substr($linha,  9,  9); //alpha - default brancos //Uso Exclusivo FEBRABAN/CNAB     
		$vlinha["quant_lotes"]       = substr($linha, 18,  6); //num. //Quantidade de Lotes do Arquivo
		$vlinha["quant_regs"]        = substr($linha, 24,  6); //num. //Quantidade de Registros do Arquivo
		$vlinha["quant_contas_conc"] = substr($linha, 30,  6); //num. //Qtde de Contas p/ Conc. (Lotes)
		$vlinha["cnab2"]     			   = substr($linha, 36,205); //alpha - default brancos  //Uso Exclusivo FEBRABAN/CNAB   
		return $vlinha;
	}

	/**Processa uma linha_arquivo_retorno.
  * @param int $numLn Número_linha a ser processada
	* @param string $linha String contendo a linha a ser processada
	* @return array Retorna um vetor associativo contendo os valores_linha processada.*/
	function processarLinha($numLn, $linha) {
      //é adicionado um espaço vazio no início_linha para que
			//possamos trabalhar com índices iniciando_1, no lugar_zero,
			//e assim, ter os valores_posição_campos exatamente
			//como no manual CNAB240
			$linha = " $linha";
      $tipoLn = substr($linha,  8,  1);

	  if($tipoLn == RetornoCNAB240::HEADER_ARQUIVO) 
         $vlinha = $this->processarHeaderArquivo($linha);
  	  else if($tipoLn == RetornoCNAB240::HEADER_LOTE) 
			$vlinha = $this->processarHeaderLote($linha);
      else if($tipoLn == RetornoCNAB240::DETALHE) 
			$vlinha = $this->processarDetalhe($linha);
	  else if($tipoLn == RetornoCNAB240::TRAILER_LOTE) 
			$vlinha = $this->processarTrailerLote($linha); 
	  else if($tipoLn == RetornoCNAB240::TRAILER_ARQUIVO) 
		   $vlinha = $this->processarTrailerArquivo($linha); 
	  return $vlinha;
  }
}

?>
