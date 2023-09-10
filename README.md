# PHP Json Generator
Um gerador de JSON feito em PHP.

## Descrição
O PHP Json Generator é uma biblioteca desenvolvida para gerar dados aleatórios em diversos formatos, tais como números inteiros e de ponto flutuante, nomes, endereços, textos "Lorem Ipsum", coordenadas geográficas e datas. Ideal para cenários de testes, mock-ups de dados, entre outros.
Passo a passo para finalizar o projeto

## Índice
- [PHP Json Generator](#php-json-generator)
  - [Descrição](#descrição)
  - [Índice](#índice)
  - [Requisitos](#requisitos)
  - [Instalação](#instalação)
  - [Uso Basico](#uso-basico)
  - [Exemplo de Uso](#exemplo-de-uso)
  - [Funções de Geração de Dados](#funções-de-geração-de-dados)
    - [repeat()](#repeat)
      - [Exemplo](#exemplo)
    - [index()](#index)
  - [Contribuição](#contribuição)

## Requisitos
PHP 8.x ou superior

## Instalação
Para instalar a biblioteca, você pode utilizar o Composer:
```
composer require php-json-generator
```
Ou você pode incluir manualmente o arquivo em seu projeto.

## Uso Basico
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
                            "length": 4
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

$jsonConfig = '[{"repeat()": {"options": {"qtd": 1}, "data": {"objectId": {"objectId()": {"options": {"length": {"integer()": {"options": {"min": "0", "max": "4"}}}}}}}, "index1": "index()", "guid": "guid()"}}]';

$generator = new PhpJsonGenerator($jsonConfig);
$result = $generator->generate();
```

## Funções de Geração de Dados
### repeat()
Repete X vezes os valores que estiverem dentro de data.
#### Exemplo
```JSON
[
    {
        "repeat()": {
            "options": {
                "qtd": 2
            },
            "data": {
                "guid": "guid()",
                "indice": "index()",
                //ETC.
            }
        }
    }
]
```
Será gerado 2 arrays com guid aleatório.

###  index()
Gera um índice por nível do array, como uma lista ordenada da seguinte forma:
1. A
   1. A
   2. B
2. B
   1. A
   2. B
```JSON
[
    {
        "indice": "index()"
    }
]
```

## Contribuição
Se você tem interesse em contribuir para o projeto, veja as orientações sobre como fazer isso.