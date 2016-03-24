Retorno-BoletoPHP
-----------------
Biblioteca em PHP 5.5+ para leitura de arquivos de retorno de títulos de cobrança de bancos brasileiros.

O projeto utiliza Design Patterns para permitir a fácil extensão, para adição de novos padrões de arquivos de retorno.

A implementação do projeto para versões do PHP inferiores a 5.5 está obsoleta, não sendo recomendado seu uso. Tal versão também não é mais suportada. Se de qualquer maneira desejar utilizá-la, pode baixar os fontes na [branch php53](https://github.com/manoelcampos/Retorno-BoletoPHP/tree/php53).

Documentação
------------
A documentação do projeto foi gerada com [PhpDoc](http://phpdoc.org), que está incluído como dependência de desenvolvimento do projeto. Assim, para instalá-lo via composer, basta executar `composer install`. Em seguida execute `vendor/bin/phpdoc` para gerar a documentação em HTML. Toda a configuração para geração da documentação está definida no arquivo [phpdoc.dist.xml](phpdoc.dist.xml).

A documentação das classes do projeto pode ser consultada online em [http://manoelcampos.github.io/retorno-boletophp/doc/](http://manoelcampos.github.io/retorno-boletophp/doc/).

Exemplos
--------
Diversos exemplos são disponibilizados com o projeto, podendo ser acessados a partir da branch [exemplos](https://github.com/manoelcampos/Retorno-BoletoPHP/tree/exemplos). Para mais informações sobre o uso, acessa tal branch.

Aviso Legal
-----------
O projeto é disponibilizado "como está" e nenhuma garantia legal é fornecida. Nenhuma responsabilidade será atribuída ao desenvolvedor por danos e prejuízos que por ventura possam vir a ocorrer devido ao uso do projeto, ficando este completamente isento de qualquer responsabilidade.

Todo o processo de geração de boletos bancários e leitura de arquivos de retorno deve ser homologado junto ao banco conveniado para assegurar que está tudo ocorrendo conforme esperado. Mesmo existindo os padrões FEBRABAN CNAB240 E CNAB400, existem diferentes versões
dos mesmos e alguns bancos podem utilizar certos campos do arquivo de retorno enquanto outros não. Por isso, é extremamente recomendável que o desenvolvedor, ou o cliente para qual está desenvolvendo o software, entre em contato com o banco para realizar o processo de homologação. 

Use o projeto por sua conta e risco.

Palavras-chave
--------------
- Boleto Bancário
- Retorno Boleto
- Arquivo de Retorno
- Títulos de Cobrança
- Bancos Brasileiros

Fórum
-----
Dúvidas, acesse o fórum de discussão no [Google Groups](http://groups.google.com/group/retorno-boletophp).

Licença
-------
Os direitos sobre uso do projeto estão protegidos pela [Licença MIT](LICENSE).
