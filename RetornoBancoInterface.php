<?php

/**
 *  Interface para chamada ao processar uma linha do arquivo de retorno
 * @copyright GPLv2
 * @package ArquivoRetornoTitulosBancarios
 * @author Gilberto C. de Almeida <gibalmeida@gmail.com>
 * @version 0.1
 * @abstract
 */

interface RetornoBancoInterface {
    public function aoProcessarLinha($self, $numLn, $vlinha);
}