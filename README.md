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
      - [**StateSelected**](#stateselected)
      - [**Integer**](#integer)
      - [**objectId**](#objectid)
      - [**boolean**](#boolean)
      - [**floating**](#floating)
      - [**money**](#money)
      - [**custom**](#custom)
      - [**gender**](#gender)
      - [**company**](#company)
      - [**phone**](#phone)
      - [**stateSelected**](#stateselected-1)
      - [**lorem**](#lorem)
      - [**latitude**](#latitude)
      - [**longitude**](#longitude)
      - [**date**](#date)
      - [**JSON Exemplo**:](#json-exemplo-1)
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
                /* ... outros campos ... */
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

#### **StateSelected**
Gera um estado aleatório de acordo com o país enviado. ***stateSelected()***.
#### **Integer**
Gera um número inteiro aleatório, podendo ser nulo ou falso. ***integer()***.
#### **objectId**
Gera um ID aleatório, como um hash que pode identificar um objeto. ***objectId()***
#### **boolean**
Gera um resultado boolean, pode também retornar nulo, aumentar a probabilidade de false ou negar o false para ser true, utilizando como "truePercentage". ***boolean()***
#### **floating**
Gera um resultado aleatório com ponto flutuante. Podendo ser nulo ou falso. ***floating()***
#### **money**
Gera um float formatado no padrão da moeda que for especificado. ******
#### **custom**
Retorna um dos itens do array fornecido de forma aleatória. ******
#### **gender**
Retorna um dos gêneros fornecidos de forma aleatória, caso nenhum seja fornecido, retorna o padrão. ******
#### **company**
Retorna o nome de uma empresa fictícia aleatória. Podendo variar a string de retorno entre os tipos: toUpperCase, toLowerCase, capitalize, camelCase, slugify. ******
#### **phone**
Retorna um número de telefone aleatório, podendo ser formatado de acordo com a localidade. ******
#### **stateSelected**
Retorna um estado selecionado, diferente do state sem opções que vem um estado aleatório, este é possível específicar o país. ******
#### **lorem**
Retorna um texto LoremIpsum aleatório, podendo especificar a quantidade e variar entre os tipos: words, sentenses, paragraphs. ******
#### **latitude**
Retorna um float entre -90.000001 e 90. ******
#### **longitude**
Retorna um float entre -180.000001 e 180. ******
#### **date**
Retorna uma data dentro do período específicado no formato específicado. <a href="https://www.php.net/manual/en/function.date">***date()***</a>
#### **JSON Exemplo**:
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
        "integer": {
            "options": {
                "min": 1,
                "max": 10,
                "falsePercentage": 0,
                "nullPercentage": 0
            }
        },
        "objectId": {
            "options": {
                "length": 5
            }
        },
        "boolean": {
            "options": {
                "falsePercentage": 0,
                "nullPercentage": 0
            }
        },
        "floating": {
            "min": 100,
            "max": 4000,
            "decimals": 2,
            "falsePercentage": 0,
            "nullPercentage": 0,
            "round": false
        },
        "money": {
            "options": {
                "falsePercentage": 0,
                "nullPercentage": 0,
                "min": 100,
                "max": 4000,
                "decimals": 2,
                "thousand": ".",
                "separator": ",",
                "prefix": "R$"
            }
        },
        "custom": {
            "data": {
                "1": "Red",
                "2": "Green",
                "3": "Blue"
            }
        },
        "gender": {
            "data": {
                "1": "Male",
                "2": "Femeale",
                "3": "Others"
            }
        },
        "company": {
            "options": {
                "type": "toUpperCase"
            }
        },
        "phone": {
            "options": {
                "ddiLength": 2,
                "dddLength": 2,
                "phoneLength": 9,
                "plus": true,
                "spaceAfterPlus": true,
                "parentheses": true,
                "spaceAfterParentheses": true,
                "dash": true,
                "dashBefore": 4,
                "spaceAroundDash": false
            },
            "data": {
                "ddi": null,
                "ddd": null,
                "phoneNumber": null
            }
        },
        "stateSelected": {
            "options": {
                "country": 3
            }
        },
        "lorem": {
            "options": {
                "length": 1,
                "type": "words"
            }
        },
        "latitude": {
            "options": {
                "min": "-90.000001",
                "max": "90"
            }
        },
        "longitude": {
            "options": {
                "min": "-180.000001",
                "max": "180"
            }
        },
        "date": {
            "options": {
                "min": "2023-09-01 00:00:00",
                "max": "2023-09-10 00:00:00",
                "format": "'Y-m-d H:i:s"
            }
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