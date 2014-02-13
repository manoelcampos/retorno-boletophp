<?php
require_once("RetornoBase.php");

/**Classe abstrata para leitura de arquivos de retorno de cobranças no padrão CNAB400/CBR643.<br/>
* Layout Padrão CNAB/Febraban 400 posições<br/>.
* Baseado na documentação para "Layout de Arquivo Retorno para Convênios
* na faixa numérica entre 000.001 a 999.999 (Convênios de até 6 posições). Versão Set/09" e
* "Layout de Arquivo Retorno para convênios na faixa numérica entre 1.000.000 a 9.999.999
* (Convênios de 7 posições). Versão Set/09" do Banco do Brasil 
* (arquivos Doc8826BR643Pos6.pdf e Doc2628CBR643Pos7.pdf)
* @copyright GPLv2
* @package ArquivoRetornoTitulosBancarios
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.4
*/
abstract class RetornoCNAB400Base extends RetornoBase {
	/**@property int HEADER_ARQUIVO Define o valor que identifica uma coluna do tipo HEADER DE ARQUIVO*/
	const HEADER_ARQUIVO = 0;

	
  /**@property int TRAILER_ARQUIVO Define o valor que identifica uma coluna do tipo TRAILER DE ARQUIVO*/
	const TRAILER_ARQUIVO = 9;

  public function __construct($nomeArquivo=NULL, $aoProcessarLinhaFunctionName=""){
       parent::__construct($nomeArquivo, $aoProcessarLinhaFunctionName);
  }

	/**Processa a linha header do arquivo
	* @param string $linha Linha do header de arquivo processado
	* @return array<mixed> Retorna um vetor contendo os dados dos campos do header do arquivo.*/
  protected function processarHeaderArquivo($linha) {
    $vlinha = array();	
																													    //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
		$vlinha["registro"]           = substr($linha, 1,     1); //9 Identificação do Registro Header: “0”
		$vlinha["tipo_operacao"]      = substr($linha, 2,     1); //9 Tipo de Operação: “2”
		$vlinha["id_tipo_operacao"]   = substr($linha, 3,     7); //X Identificação Tipo de Operação “RETORNO”
		$vlinha["id_tipo_servico"]    = substr($linha, 10,    2); //9 Identificação do Tipo de Serviço: “01”
		$vlinha["tipo_servico"]       = substr($linha, 12,    8); //X Identificação por Extenso do Tipo de Serviço: “COBRANCA”
		$vlinha["complemento1"]       = substr($linha, 20,    7); //X Complemento do Registro: “Brancos”
		$vlinha["agencia_cedente"]    = substr($linha, 27,    4); //9 Prefixo da Agência: N. Agência onde está cadastrado o convênio líder do cedente
		$vlinha["dv_agencia_cedente"] = substr($linha, 31,    1); //X Dígito Verificador - D.V. - do Prefixo da Agência
		$vlinha["conta_cedente"]      = substr($linha, 32,    8); //9 Número da Conta Corrente onde está cadastrado o Convênio Líder do Cedente
		$vlinha["dv_conta _cedente"]  = substr($linha, 40,    1); //X Dígito Verificador - D.V. - da Conta Corrente do Cedente

		$vlinha["nome_cedente"]       = substr($linha, 47,   30); //X Nome do Cedente
		$vlinha["banco"]     = substr($linha, 77,   18); //X 001BANCODOBRASIL
		$vlinha["data_gravacao"]      = $this->formataData(substr($linha, 95,    6)); //9 Data da Gravação: Informe no formado “DDMMAA”
		$vlinha["sequencial_reg"]     = substr($linha, 395,   6); //9 Seqüencial do Registro: ”000001”
	  return $vlinha;
	}

	/**Processa uma linha detalhe do arquivo.
	* @param string $linha Linha detalhe do arquivo processado
	* @return array<mixed> Retorna um vetor contendo os dados dos campos da linha detalhe.*/
	protected function processarDetalhe($linha) {
		$vlinha = array();
		                                                           //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
		$vlinha["registro"]         = substr($linha,   1,   1); //9  Id do Registro Detalhe: 1 p/ convênios de 6 dígitos e 7 para convênios de 7 dígitos
		//$vlinha["zeros1"]              = substr($linha,   2,   2); //9  Zeros
		//$vlinha["zeros2"]              = substr($linha,   4,  14); //9  Zeros
		$vlinha["agencia"]             = substr($linha,  18,   4); //9  Prefixo da Agência
		$vlinha["dv_agencia"]          = substr($linha,  22,   1); //X  Dígito Verificador - D.V. - do Prefixo da Agência
		$vlinha["cc_cedente"]          = substr($linha,  23,   8); //9  Número da Conta Corrente do Cedente
		$vlinha["dv_cc_cedente"]       = substr($linha,  31,   1); //X  Dígito Verificador - D.V. - do Número da Conta Corrente do Cedente
		$vlinha["taxa_desconto"]       = $this->formataNumero(substr($linha,  96,   5)); //9  v99 Taxa de desconto
		$vlinha["taxa_iof"]            = substr($linha, 101,   5); //9  Taxa de IOF
		//$vlinha["branco"]              = substr($linha, 106,   1); //x  Branco
		$vlinha["carteira"]            = substr($linha, 107,   2); //9  Carteira
		$vlinha["comando"]             = substr($linha, 109,   2); //9  Comando - nota 07
		$vlinha["data_ocorrencia"]=$this->formataData(substr($linha, 111,   6)); //X  Data da Entrada/Liquidação (DDMMAA)
		$vlinha["num_titulo"]          = substr($linha, 117,  10); //X  Número título dado pelo cedente - (ver nota 06 para convênio de 6 dígitos)
		$vlinha["data_vencimento"]     = substr($linha, 147,   6); //9  Data de vencimento (DDMMAA) (ver nota 6 para convênios de 7 dígitos)
		$vlinha["valor"]=$this->formataNumero(substr($linha, 153,  13)); //9  v99 Valor do título

		$vlinha["cod_banco"]           = substr($linha, 166,   3); //9  Código do banco recebedor - ver nota 08
		$vlinha["agencia"]             = substr($linha, 169,   4); //9  Prefixo da agência recebedora - ver nota 08
		$vlinha["dv_agencia"]          = substr($linha, 173,   1); //X  DV prefixo recebedora
		$vlinha["especie"]             = substr($linha, 174,   2); //9  Espécie do título - ver nota 09
		$vlinha["data_credito"]        = substr($linha, 176,   6); //9  Data do crédito (DDMMAA) - ver nota 10
		$vlinha["valor_tarifa"]        = $this->formataNumero(substr($linha, 182,   7)); //9  v99 Valor da tarifa - ver nota 06
		$vlinha["outras_despesas"]     = $this->formataNumero(substr($linha, 189,  13)); //9  v99 Outras despesas
		$vlinha["juros_desconto"]      = $this->formataNumero(substr($linha, 202,  13)); //9  v99 Juros do desconto
		$vlinha["iof_desconto"]        = $this->formataNumero(substr($linha, 215,  13)); //9  v99 IOF do desconto
		$vlinha["valor_abatimento"]    = $this->formataNumero(substr($linha, 228,  13)); //9  v99 Valor do abatimento
		$vlinha["desconto_concedido"]  = $this->formataNumero(substr($linha, 241,  13)); //9  v99 Desconto concedido 
		$vlinha["valor_recebido"]      = $this->formataNumero(substr($linha, 254,  13)); //9  v99 Valor recebido (valor recebido parcial)
		$vlinha["juros_mora"]          = $this->formataNumero(substr($linha, 267,  13)); //9  v99 Juros de mora
		$vlinha["outros_recebimentos"] = $this->formataNumero(substr($linha, 280,  13)); //9  v99 Outros recebimentos
		$vlinha["abatimento_nao_aprov"]= $this->formataNumero(substr($linha, 293,  13)); //9  v99 Abatimento não aproveitado pelo sacado
		$vlinha["valor_lancamento"]    = $this->formataNumero(substr($linha, 306,  13)); //9  v99 Valor do lançamento
		$vlinha["indicativo_dc"]       = substr($linha, 319,   1); //9  Indicativo de débito/crédito - ver nota 11
		$vlinha["indicador_valor"]     = substr($linha, 320,   1); //9  Indicador de valor -ver  nota 12
		$vlinha["valor_ajuste"]        = $this->formataNumero(substr($linha, 321,  12)); //9  v99 Valor do ajuste - ver nota 13
		//$vlinha["brancos1"]            = substr($linha, 333,   1); //X  Brancos (vide observação para cobrança compartilhada) 14
		//$vlinha["brancos2"]            = substr($linha, 334,   9); //9  Brancos (vide observação para cobrança compartilhada) 14

		$vlinha["canal_pag_titulo"]    = substr($linha, 393,   2); //9 Canal de pagamento do título utilizado pelo sacado - ver nota 15
		$vlinha["sequencial"]          = substr($linha, 395,   6); //9 Seqüencial do registro

		return $vlinha;
	}

	/**Processa a linha trailer do arquivo.
	* @param string $linha Linha trailer do arquivo processado
	* @return array<mixed> Retorna um vetor contendo os dados dos campos da linha trailer do arquivo.*/
	protected function processarTrailerArquivo($linha) {
		$vlinha = array();
																																	  //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
		$vlinha["registro"]                = substr($linha,   1,   1);  //9  Identificação do Registro Trailer: “9”
		$vlinha["retorno"]                 = substr($linha,   2,   1);  //9  “2”
		$vlinha["tipo_registro"]           = substr($linha,   3,   2);  //9  “01”
		$vlinha["cod_banco"]               = substr($linha,   5,   3);  
		$vlinha["cob_simples_qtd_titulos"] = substr($linha,  18,   8);  //9  Cobrança Simples - quantidade de títulos em cobranca
		$vlinha["cob_simples_vlr_total"]   = $this->formataNumero(substr($linha,  26,  14)); //9  v99 Cobrança Simples - valor total
		$vlinha["cob_simples_num_aviso"]   = substr($linha,  40,   8);  //9  Cobrança Simples - Número do aviso
		$vlinha["cob_vinc_qtd_titulos"]    = substr($linha,  58,   8);  //9  Cobrança Vinculada - quantidade de títulos
		$vlinha["cob_vinc_valor_total"]    = $this->formataNumero(substr($linha,  66,  14)); //9  v99 Cobrança Vinculada - valor total
		$vlinha["cob_vinc_num_aviso"]      = substr($linha,  80,   8);  //9  Cobrança Vinculada - Número do aviso
		//$vlinha["cob_vinc_brancos"]        = substr($linha,  88,  10); //X  Cobrança Vinculada - Brancos
		$vlinha["cob_cauc_qtd_titulos"]    = substr($linha,  98,   8);  //9  Cobrança Caucionada - quantidade de títulos
		$vlinha["cob_cauc_vlr_total"]      = $this->formataNumero(substr($linha, 106,  14)); //9  v99 Cobrança Caucionada - valor total
		$vlinha["cob_cauc_num_aviso"]      = substr($linha, 120,   8);  //9  Cobrança Caucionada - Número do aviso
		//$vlinha["cob_cauc_brancos"]        = substr($linha, 128,  10); //X  Cobrança Caucionada - Brancos
		$vlinha["cob_desc_qtd_titulos"]    = substr($linha, 138,   8);  //9  Cobrança Descontada - quantidade de títulos
		$vlinha["cob_desc_vlr_total"]      = $this->formataNumero(substr($linha, 146,  14)); //9  v99 Cobrança Descontada - valor total
		$vlinha["cob_desc_num_aviso"]      = substr($linha, 160,   8);  //9  Cobrança Descontada - Número do aviso
		//$vlinha["cob_desc_brancos"]        = substr($linha, 168,  50); //X  Cobrança Descontada - Brancos
		$vlinha["cob_vendor_qtd_titulos"]  = substr($linha, 218,   8);  //9  Cobrança Vendor - quantidade de títulos
		$vlinha["cob_vendor_vlr_total"]    = $this->formataNumero(substr($linha, 226,  14)); //9  v99 Cobrança Vendor - valor total
		$vlinha["cob_vendor_num_aviso"]    = substr($linha, 240,   8);  //9  Cobrança Vendor - Número do aviso
		//$vlinha["cob_vendor_brancos"]      = substr($linha, 248, 147); //X  Cobrança Vendor – Brancos
		$vlinha["sequencial"]              = substr($linha, 395,   6);  //9  Seqüencial do registro

		return $vlinha;
	}

	/**Processa uma linha do arquivo de retorno.
  * @param int $numLn Número_linha a ser processada
	* @param string $linha String contendo a linha a ser processada
	* @return array Retorna um vetor associativo contendo os valores_linha processada.*/
	public function processarLinha($numLn, $linha) {
			$tamLinha = 400; //total de caracteres das linhas do arquivo
			//o +2 é utilizado para contar o \r\n no final da linha
			if(strlen($linha)!=$tamLinha and strlen($linha)!=$tamLinha+2)
					die("A linha $numLn não tem $tamLinha posições. Possui " . strlen($linha));
			if(trim($linha)=="")
					die("A linha $numLn está vazia.");

      //é adicionado um espaço vazio no início_linha para que
			//possamos trabalhar com índices iniciando_1, no lugar_zero,
			//e assim, ter os valores_posição_campos exatamente
			//como no manual CNAB400
			$linha = " $linha";
      $tipoLn = substr($linha,  1,  1);
		  if($tipoLn == RetornoCNAB400Base::HEADER_ARQUIVO) 
         $vlinha = $this->processarHeaderArquivo($linha);
      else if($tipoLn == RetornoCNAB400Base::DETALHE)
				 $vlinha = $this->processarDetalhe($linha);
		  else if($tipoLn == RetornoCNAB400Base::TRAILER_ARQUIVO)
			   $vlinha = $this->processarTrailerArquivo($linha); 
			else $vlinha = NULL;
			return $vlinha;
  }
}

?>
