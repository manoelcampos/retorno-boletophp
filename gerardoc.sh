#!/bin/bash
echo "Geração de documentação com PhpDoc: http://www.phpdoc.org"
echo -e "O script usa uma versão do PhpDoc baixada com o composer: https://getcomposer.org\n"

composer --version
RET=$?
if [ $RET -ne 0 ]; then 
	echo -e "\nErro: Instale o Composer https://getcomposer.org que o PhpDoc será baixado utilizando tal ferramenta\n"; 
	exit $RET;
fi

composer install #instala o PhpDoc via composer
vendor/bin/phpdoc --parseprivate \
 	--title "Documentação do Módulo para Processamento de Arquivos de Retorno de Títulos Bancários Brasileiros" \
 	--directory com/manoelcampos/RetornoBoleto --target ./doc --encoding UTF-8

rm -rf output