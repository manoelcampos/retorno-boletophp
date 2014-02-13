#!/bin/bash
#http://tech.motion-twin.com/php_php2xmi.html

#A execução deste script substituirá o arquivo xmi de diagramas UML do Umbrello existente
cd ~/htdocs/inscricoes/admin/retorno/
#php2xmi --output=result.xmi RetornoBanco.php
php2xmi --output=diagramas-uml.umbrello.xmi --recursive ./



