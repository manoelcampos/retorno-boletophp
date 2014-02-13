<?php
require_once("RetornoCNAB400Base.php");

/**Classe para leitura de arquivos de retorno de cobranças no padrão CNAB400/CBR643 com convênio de 7 posições.<br/>
* Layout Padrão CNAB/Febraban 400 posições<br/>.
* Baseado na documentação para "Layout de Arquivo Retorno para convênios na faixa numérica entre 1.000.000 a 9.999.999
* (Convênios de 7 posições). Versão Set/09" do Banco do Brasil (arquivo Doc8826BR643Pos6.pdf),
* disponível em http://www.bb.com.br/docs/pub/emp/empl/dwn/Doc8826BR643Pos6.pdf
* @copyright GPLv2
* @package ArquivoRetornoTitulosBancarios
* @author Manoel Campos da Silva Filho. http://manoelcampos.com/contato
* @version 0.4
*/
class RetornoCNAB400Conv6 extends RetornoCNAB400Base {
  /**@property int DETALHE Define o valor que identifica uma coluna do tipo DETALHE*/
	const DETALHE = 1;

  public function __construct($nomeArquivo=NULL, $aoProcessarLinhaFunctionName=""){
       parent::__construct($nomeArquivo, $aoProcessarLinhaFunctionName);
  }

	/**Processa a linha header do arquivo
	* @param string $linha Linha do header de arquivo processado
	* @return array<mixed> Retorna um vetor contendo os dados dos campos do header do arquivo.*/
  protected function processarHeaderArquivo($linha) {
    $vlinha = parent::processarHeaderArquivo($linha);	
																													    //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
		$vlinha["convenio"]           = substr($linha, 41,    6); //9 Número do convênio líder 
		$vlinha["sequencial_ret"]     = substr($linha, 101,   7); //9 Seqüencial do Retorno - ver nota 01		
		$vlinha["complemento2"]       = substr($linha, 108, 287); //X Complemento do Registro: “Brancos”
	  return $vlinha;
	}

	/**Processa uma linha detalhe do arquivo.
	* @param string $linha Linha detalhe do arquivo processado
	* @return array<mixed> Retorna um vetor contendo os dados dos campos da linha detalhe.*/
	protected function processarDetalhe($linha) {
		$vlinha = parent::processarDetalhe($linha);
		                                                           //X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
		$vlinha["convenio"]            = substr($linha,  32,   6); //9  Número do Convênio de Cobrança do Cedente
		$vlinha["controle"]            = substr($linha,  38,  25); //X  Número de Controle do Participante
		$vlinha["nosso_numero"]        = substr($linha,  63,  11); //9  Nosso-Número
		$vlinha["dv_nosso_numero"]     = substr($linha,  74,   1); //X  DV do Nosso-Número
		$vlinha["tipo_cobranca"]       = substr($linha,  75,   1); //9  Tipo de cobrança - nota 02
		$vlinha["tipo_cobranca_cmd72"] = substr($linha,  76,   1); //9  Tipo de cobrança específico p/ comando 72 - nota 03
		$vlinha["dias_calculo"]        = substr($linha,  77,   4); //9  Dias para cálculo - nota 04
		$vlinha["natureza"]            = substr($linha,  81,   2); //9  Natureza do recebimento - nota 05
		$vlinha["uso_banco1"]          = substr($linha,  83,   3); //X  Uso do Banco
		$vlinha["variacao_carteira"]   = substr($linha,  86,   3); //9  Variação da Carteira
		$vlinha["conta_caucao"]        = substr($linha,  89,   1); //9  Conta Caução - nota 06

		$vlinha["uso_banco2"]          = substr($linha,  90,   5); //9  Uso do Banco
		$vlinha["uso_banco3"]          = substr($linha,  95,   1); //X  Uso do Banco
		$vlinha["confirmacao"]         = substr($linha, 127,  20); //X  Confirmação das posições 63 a 82

    /*
		$vlinha["zeros3"]              = substr($linha, 343,   6); //9 Zeros - nota 14
		$vlinha["zeros4"]              = substr($linha, 349,   9); //9 Zeros - nota 14
		$vlinha["zeros5"]              = substr($linha, 358,   6); //9 Zeros - nota 14
		$vlinha["zeros6"]              = substr($linha, 364,   9); //9 Zeros - nota 14
		$vlinha["zeros7"]              = substr($linha, 373,   6); //9 Zeros - nota 14
		$vlinha["zeros8"]              = substr($linha, 379,   9); //9 Zeros - nota 14
		$vlinha["brancos3"]            = substr($linha, 388,   5); //X Brancos
    */
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
