Retorno-BoletoPHP
-----------------
Biblioteca de classes em PHP 5.5+ para leitura de arquivos de retorno de títulos de cobrança de bancos brasileiros.

O projeto utiliza Design Patterns para permitir a fácil extensão, para adição de novos padrões de arquivos de retorno.

A implementação do projeto para versões do PHP inferiores a 5.5 estão obsoletas, não sendo recomendado seu uso. Tal versão obsoleta também não é mais suportada. Se de qualquer maneira desejar tal versão, pode baixar os fonte na [branch php53](https://github.com/manoelcampos/Retorno-BoletoPHP/tree/php53).

Documentação
------------
A documentação do projeto foi gerada com [PhpDoc](http://phpdoc.org), que está incluído como dependência de desenvolvimento do projeto. Assim, para intalá-lo via composer, basta executar
`composer install`. Com isto, para gerar a documentação, basta executar `vendor/bin/phpdoc`. Toda a configuração para geração da documentação está definida no arquivo [phpdoc.dist.xml](phpdoc.dist.xml).

Exemplos
--------
Diversos exemplos são disponibilizados junto com o projeto, podendo ser acessados a partir da branch [exemplos](/manoelcampos/Retorno-BoletoPHP/tree/exemplos). Para mais informações sobre o uso, acessa tal branch.

Aviso Legal
-----------
O projeto é disponibilizado "como está" e nenhuma garantia legal é fornecida. Nenhuma responsabilidade será atribuída ao desenvolvedor por danos e prejuízos que por ventura possam ter sido decorridos do uso do projeto, ficando este completamente isento
de qualquer responsabilidade.

Todo o processo de geração de boletos bancários e leitura de arquivos de retorno deve ser homologado junto ao banco conveniado para assegurar que está tudo ocorrendo dentro dos padrões. Mesmo existindo os padrões FEBRABAN CNAB240 E CNAB400, existem diferentes versões
de tais padrões e alguns bancos podem utilizar certos campos do arquivo de retorno enquanto outros não. Por isso, é extremamente recomendável que o desenvolvedor, ou o cliente para qual está desenvolvendo o software, entre em contato com o banco para realizar o processo de homologação. 

Use o projeto por sua conta e risco.

Fórum
-----
Dúvidas, acesse o fórum de discussão no [Google Groups](http://groups.google.com/group/retorno-boletophp).

Licença
-------
Os direitos sobre uso do projeto estão protegidos pela [Licença MIT](LICENSE).
