# PHP Json Generator
Um gerador de JSON feito em PHP.

## Descrição
O PHP Json Generator é uma biblioteca desenvolvida para gerar dados aleatórios em diversos formatos, tais como números inteiros e de ponto flutuante, nomes, endereços, textos "Lorem Ipsum", coordenadas geográficas e datas. Ideal para cenários de testes, mock-ups de dados, entre outros.
Passo a passo para finalizar o projeto

## Requisitos
PHP 8.x ou superior

## Instalação
Para instalar a biblioteca, você pode utilizar o Composer:
```
composer require php-json-generator
```
Ou você pode incluir manualmente o arquivo em seu projeto.

## Uso Básico
Para usar o PHP Json Generator, você pode enviar um JSON com configurações para o backend. Abaixo está um exemplo de como o JSON deve ser formatado para gerar diferentes tipos de dados aleatórios:
```JSON
[
    {
        "repeat()": {
            "options": {
                "qtd": 2
            },
            "data": {
                "objectId": {
                    "objectId()": {
                        "options": {
                            "qtd": {
                                "integer()": {
                                    "options": {
                                        "min": "0",
                                        "max": "4"
                                    }
                                }
                            }
                        }
                    }
                },
                "index": "index()",
                "guid": "guid()",
                /* ... outros campos ... */
            }
        }
    }
]
```
## Exemplo de Uso
```PHP
use PhpJsonGenerator\PhpJsonGenerator;

$jsonConfig = '[{"repeat()": {"options": {"qtd": 1}, "data": {"objectId": {"objectId()": {"options": {"qtd": {"integer()": {"options": {"min": "0", "max": "4"}}}}}}}, "index1": "index()", "guid": "guid()", /* ... outros campos ... */}}]';

$generator = new PhpJsonGenerator($jsonConfig);
$result = $generator->generate();
```

## Documentação Completa
Para obter mais detalhes e exemplos, consulte a documentação completa do projeto.

## Contribuição
Se você tem interesse em contribuir para o projeto, veja as orientações sobre como fazer isso.