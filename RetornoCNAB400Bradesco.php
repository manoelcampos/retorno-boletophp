<?php
require_once("RetornoCNAB400Base.php");

/**Classe para leitura de arquivos de retorno de cobranças no padrão 400 posições do Bradesco.<br/>.
* Baseado no documento "Cobrança Bradesco - Manual Operacional para Troca de Arquivos" do Bradesco 
* (arquivo layout_cobranca_port_bradesco.pdf)
* @copyright GPLv2
* @package ArquivoRetornoTitulosBancarios
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.4
*/
class RetornoCNAB400Bradesco extends RetornoCNAB400Base {
  /**@property int DETALHE Define o valor que identifica uma coluna do tipo DETALHE*/
	const DETALHE = 1;

  public function __construct($nomeArquivo=NULL, $aoProcessarLinhaFunctionName=""){
       parent::__construct($nomeArquivo, $aoProcessarLinhaFunctionName);
  }

	/**Processa a linha header do arquivo
	* @param string $linha Linha do header de arquivo processado
	* @return array<mixed> Retorna um vetor contendo os dados dos campos do header do arquivo.*/
  protected function processarHeaderArquivo($linha) {
	  //O formato de 400 posicoes do bradesco é diferente do padrao FEBRABAN
	  //(pelo menos do usado pelo BB). Assim, nao é chamada a funcao na classe
	  //pai pois a mesma é totalmente reimplementada aqui
		//$vlinha = parent::processarHeaderArquivo($linha);	
		
    $vlinha = array();	 
													 																     //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
		$vlinha["registro"]           = substr($linha, 1,      1); //9 Identificação do Registro Header: “0”
		$vlinha["tipo_operacao"]      = substr($linha, 2,      1); //9 Tipo de Operação: “2”
		$vlinha["id_tipo_operacao"]   = substr($linha, 3,      7); //X Identificação Tipo de Operação “RETORNO”
		$vlinha["id_tipo_servico"]    = substr($linha, 10,     2); //9 Identificação do Tipo de Serviço: “01”
		$vlinha["tipo_servico"]       = substr($linha, 12,    15); //X Identificação por Extenso do Tipo de Serviço: “COBRANCA”
		$vlinha["cod_empresa"]        = substr($linha, 27,    20);
		$vlinha["nome_empresa"]       = substr($linha, 47,    30); //razao social
		$vlinha["num_banco"]          = substr($linha, 77,     3); //237 (Código do bradesco)
		$vlinha["banco"]              = substr($linha, 80,    15); //Nome do banco (BRADESCO)
		$vlinha["data_gravacao"]      = $this->formataData(substr($linha, 95,    6)); //9 Data da Gravação: Informe no formado “DDMMAA”
		$vlinha["densidade_gravacao"] = substr($linha, 101,    8); //01600000 
		$vlinha["num_aviso_bancario"] = substr($linha, 109,    5); 
		$vlinha["data_credito"]       = $this->formataData(substr($linha, 380,    6)); // “DDMMAA”
		$vlinha["sequencial_reg"]     = substr($linha, 395,   6); //9 Seqüencial do Registro: ”000001”

		return $vlinha;
	}

	/**Processa uma linha detalhe do arquivo.
	* @param string $linha Linha detalhe do arquivo processado
	* @return array<mixed> Retorna um vetor contendo os dados dos campos da linha detalhe.*/
	protected function processarDetalhe($linha) {
	  //O formato de 400 posicoes do bradesco é diferente do padrao FEBRABAN
	  //(pelo menos do usado pelo BB). Assim, nao é chamada a funcao na classe
	  //pai pois a mesma é totalmente reimplementada aqui	
		//$vlinha = parent::processarDetalhe($linha);
		$vlinha = array();
		                                                            //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
		$vlinha["registro"]            = substr($linha,   1,   1);  //9  Id do Registro Detalhe: 1 
		$vlinha["tipo_inscr_empresa"]  = substr($linha,   2,   2);  //9  01-CPF | 02-CNPJ | 03-PIS/PASEP | 98-Não tem | 99-Outro
		$vlinha["num_inscr_empresa"]   = substr($linha,   4,  14);  //9  CNPJ/CPF, Número, Filial ou Controle
		$vlinha["id_empresa_banco"]    = substr($linha,  21,   17); //9  Identificação da Empresa Cedente no Banco
		                                                            //Zero, Carteira (size=3), Agência (size=5) e Conta Corrente (size=8)

		$vlinha["num_controle_part"]   = substr($linha,  38,   25); //No Controle do Participante | Uso da Empresa 
		$vlinha["nosso_numero"]        = substr($linha,  71,   12); //Identificação do Título no Banco
		$vlinha["id_rateio_credito"]   = substr($linha,  105,   1); //Indicador de Rateio Crédito “R” 
    $vlinha["carteira"]            = substr($linha, 108,   1);  //Carteira
    $vlinha["id_ocorrencia"]       = substr($linha, 109,   2);  //Identificação de Ocorrência (vide pg 47)
		$vlinha["data_ocorrencia"]     = $this->formataData(substr($linha, 111,   6)); //X  Data da Entrada/Liquidação (DDMMAA)
		$vlinha["num_documento"]       = substr($linha, 117,  10);  //A  Número título dado pelo cedente
		//$vlinha["id_titulo_banco"]     = substr($linha, 127,  20);  //mesmo valor que o campo nosso_numero (indicado anteriormente)
		$vlinha["data_vencimento"]     = $this->formataData(substr($linha, 147,   6));  //9  Data de vencimento (DDMMAA) 
		$vlinha["valor"]=$this->formataNumero(substr($linha, 153,  13)); //9  v99 Valor do título
		$vlinha["cod_banco"]           = substr($linha, 166,   3);  //9  Código do banco recebedor 
		$vlinha["agencia"]             = substr($linha, 169,   5);  //9  Código da agência recebedora 
		$vlinha["desp_cobranca"]       = $this->formataNumero(substr($linha, 176,   13));// Despesas de cobrança para
                                                                                     //os Códigos de Ocorrência 
                                                                                     //02 - Entrada Confirmada 
                                                                                     //28 - Débito de Tarifas

		$vlinha["outras_despesas"]     = $this->formataNumero(substr($linha, 189,  13)); //9  v99 Outras despesas
		$vlinha["juros_atraso"]        = $this->formataNumero(substr($linha, 202,  13)); //9  v99 Juros atraso
		$vlinha["iof"]                 = $this->formataNumero(substr($linha, 215,  13)); //9  v99 IOF 
		$vlinha["desconto_concedido"]  = $this->formataNumero(substr($linha, 241,  13)); //9  v99 Desconto concedido 
		$vlinha["valor_recebido"]      = $this->formataNumero(substr($linha, 254,  13)); //9  v99 Valor pago
		$vlinha["juros_mora"]          = $this->formataNumero(substr($linha, 267,  13)); //9  v99 Juros de mora
		$vlinha["outros_recebimentos"] = $this->formataNumero(substr($linha, 280,  13)); //9  v99 Outros recebimentos
		$vlinha["motivo_cod_ocorrencia"] = substr($linha, 319,   10);  //Motivos das Rejeições para 
                                                                   //os Códigos de Ocorrência da Posição 109 a 110 
    $vlinha["num_cartorio"]        = substr($linha, 369,   2);
		$vlinha["num_protocolo"]       = substr($linha, 371,   10);
		
		
		$vlinha["valor_abatimento"]    = $this->formataNumero(substr($linha, 228,  13)); //9  v99 Valor do abatimento
		$vlinha["abatimento_nao_aprov"]= $this->formataNumero(substr($linha, 293,  13)); //9  v99 Abatimento não aproveitado pelo sacado
		$vlinha["valor_lancamento"]    = $this->formataNumero(substr($linha, 306,  13)); //9  v99 Valor do lançamento
		$vlinha["indicativo_dc"]       = substr($linha, 319,   1); //9  Indicativo de débito/crédito - ver nota 11
		$vlinha["indicador_valor"]     = substr($linha, 320,   1); //9  Indicador de valor -ver  nota 12
		$vlinha["valor_ajuste"]        = $this->formataNumero(substr($linha, 321,  12)); //9  v99 Valor do ajuste - ver nota 13

		$vlinha["sequencial"]          = substr($linha, 395,   6); //9 Seqüencial do registro

		return $vlinha;
	}
	
	/**Processa a linha trailer do arquivo.
	* @param string $linha Linha trailer do arquivo processado
	* @return array<mixed> Retorna um vetor contendo os dados dos campos da linha trailer do arquivo.*/
	protected function processarTrailerArquivo($linha) {
	  //O formato de 400 posicoes do bradesco é diferente do padrao FEBRABAN
	  //(pelo menos do usado pelo BB). Assim, nao é chamada a funcao na classe
	  //pai pois a mesma é totalmente reimplementada aqui	
		//$vlinha = parent::processarTrailerArquivo($linha);	
		$vlinha = array();
																																	  //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
		$vlinha["registro"]                = substr($linha,   1,   1);  //9  Identificação do Registro Trailer: “9”
		$vlinha["retorno"]                 = substr($linha,   2,   1);  //9  “2”
		$vlinha["tipo_registro"]           = substr($linha,   3,   2);  //9  “01”
		$vlinha["cod_banco"]               = substr($linha,   5,   3);  
		$vlinha["cob_simples_qtd_titulos"] = substr($linha,  18,   8);  //9  Cobrança Simples - quantidade de títulos em cobranca
		$vlinha["cob_simples_vlr_total"]   = $this->formataNumero(substr($linha,  26,  14)); //9  v99 Cobrança Simples - valor total
		$vlinha["cob_simples_num_aviso"]   = substr($linha,  40,   8);  //9  Cobrança Simples - Número do aviso
		$vlinha["qtd_regs02"]              = substr($linha,  58,   5);  //Quantidade  de Registros- Ocorrência 02 – Confirmação de Entradas
		$vlinha["valor_regs02"]            = $this->formataNumero(substr($linha,  63,  12)); //Valor dos Registros- Ocorrência 02 – Confirmação de Entradas
		$vlinha["valor_regs06liq"]         = $this->formataNumero(substr($linha,  75,  12)); //Valor dos Registros- Ocorrência 06 liquidacao
		$vlinha["qtd_regs06"]              = substr($linha,  87,   5);  //Quantidade  de Registros- Ocorrência 06 – liquidacao
		$vlinha["valor_regs06"]            = $this->formataNumero(substr($linha,  92,  12)); //Valor dos Registros- Ocorrência 06
		$vlinha["qtd_regs09"]              = substr($linha,  104,   5);  //Quantidade  de Registros- Ocorrência 09 e 10
		$vlinha["valor_regs02"]            = $this->formataNumero(substr($linha,  109,  12)); //Valor dos  Registros- Ocorrência 09 e 10
		$vlinha["qtd_regs13"]              = substr($linha,  121,   5);  //Quantidade  de Registros- Ocorrência 13
		$vlinha["valor_regs13"]            = $this->formataNumero(substr($linha,  126,  12)); //Valor dos  Registros- Ocorrência 13
		$vlinha["qtd_regs14"]              = substr($linha,  138,   5);  //Quantidade  de Registros- Ocorrência 14
		$vlinha["valor_regs14"]            = $this->formataNumero(substr($linha,  143,  12)); //Valor dos  Registros- Ocorrência 14
		$vlinha["qtd_regs12"]              = substr($linha,  155,   5);  //Quantidade  de Registros- Ocorrência 12
		$vlinha["valor_regs12"]            = $this->formataNumero(substr($linha,  160,  12)); //Valor dos  Registros- Ocorrência 12
		$vlinha["qtd_regs19"]              = substr($linha,  172,   5);  //Quantidade  de Registros- Ocorrência 19
		$vlinha["valor_regs19"]            = $this->formataNumero(substr($linha,  177,  12)); //Valor dos  Registros- Ocorrência 19
    $vlinha["valor_total_rateios"]     = $this->formataNumero(substr($linha,  363,  15));
    $vlinha["qtd_rateios"]             = substr($linha,  378,   8);

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
		  if($tipoLn == $this::HEADER_ARQUIVO) 
         $vlinha = $this->processarHeaderArquivo($linha);
      else if($tipoLn == $this::DETALHE)
				 $vlinha = $this->processarDetalhe($linha);
		  else if($tipoLn == $this::TRAILER_ARQUIVO)
			   $vlinha = $this->processarTrailerArquivo($linha); 
			else $vlinha = NULL;
			return $vlinha;
  }
}

?>
