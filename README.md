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
    - [Dados sem opções](#dados-sem-opções)
      - [JSON Exemplo:](#json-exemplo)
      - [JSON Formatado:](#json-formatado)
    - [Dados com opções](#dados-com-opções)
      - [StateSelected](#stateselected)
      - [Integer](#integer)
      - [JSON Exemplo:](#json-exemplo-1)
  - [Avançado - Geração aninhada.](#avançado---geração-aninhada)
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
Existem 3 tipos gerais de dados a serem gerados.
- repeat().
- Dados sem opções.
- Dados com opções.

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

###  Dados sem opções
- index() - Gera um índice por nível do array, como uma lista ordenada.
- fullName() - Gera um nome completo, com nome e sobrenome.
- firstName() - Gera um nome aleatório.
- surName() - Gera um sobrenome aleatório.
- guid() - Gera um guid, um hash aleatório com o dobro do tamanho informado.
- email() - Gera um e-mail completo = firstName().surName()@emailDomain().
- emailDomain() - Gera um domínio de email. Ex: @outlook.com.
- logradouro() - Gera um logradouro aleatório. Ex: Rua/Avenida/Praça/Travessa.
- street() - Gera o nome de uma rua aleatória. Ex: João.
- number() - Gera um número aleatório entre 1 e 999.999.
- bairro() - Gera o nome de um bairro aleatório.
- country() - Gera o nome de um país aleatório.
- state() - Gera o nome de um estado aleatório [Pode ser utilizado definindo-se o país também](#stateselected).

#### JSON Exemplo:
```JSON
[
    {
        "indice": "index()",
        "dadosCliente": {
            "indice": "index()",
            "nomeCompleto": "fullName()",
            "id": "index()"
        },
        "guid": "guid()",
        "fullName": "fullName()",
        "firstName": "firstName()",
        "surName": "surName()",
        "email": "email()",
        "logradouro": "logradouro()",
        "street": "street()",
        "number": "number()",
        "bairro": "bairro()",
        "country": "country()",
        "state": "state()",
        "address": "address()"
    }
]
```

#### JSON Formatado:
```JSON
[
    {
        "indice": "1",
        "dadosCliente": {
            "id": "1",
            "nomeCompleto": "Bruno Morillo",
            "indice": "2",
        },
        "guid": "4f959f9f-41e4-4868-a786-85e8856a66a0",
        "fullName": "Bruno Morillo",
        "firstName": "Liliana",
        "surName": "Oliveira",
        "email": "iva.cisneros@hotmail.com",
        "logradouro": "Corredor",
        "street": "Cedro",
        "number": "4909",
        "bairro": "Campo Grande",
        "country": "Brasil",
        "state": "Acre",
        "address": "Rua. Esmeralda, 1487 - Vila Madalena, Mato Grosso, Estados Unidos"
    }
]
```

###  Dados com opções

#### StateSelected
Gera um estado aleatório de acordo com o país enviado. stateSelected().
#### Integer
Gera um número inteiro aleatório, entre os valores passados, podendo ser nulo ou falso o retorno. ***integer()***.
#### JSON Exemplo:
```JSON
[
    {
        "stateSelected": {
            "stateSelected()": {
                "options": {
                    "country": 1
                }
            }
        },
        "": {

        }
    }
]
```



## Avançado - Geração aninhada.
```JSON
[
    {
        "stateSelected": {
            "stateSelected()": {
                "options": {
                    "country": 1
                }
            }
        }
    }
]
```

## Contribuição
Se você tem interesse em contribuir para o projeto, veja as orientações sobre como fazer isso.