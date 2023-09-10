# PHP Json Generator
Um gerador de JSON feito em PHP.

## Descrição
O PHP Json Generator é uma biblioteca desenvolvida para gerar dados aleatórios em diversos formatos, tais como números inteiros e de ponto flutuante, nomes, endereços, textos "Lorem Ipsum", coordenadas geográficas e datas. Ideal para cenários de testes, mock-ups de dados, entre outros.
Passo a passo para finalizar o projeto

## Índice
1. [Índice](#Índice)
2. [Introdução](#Introdução)
3. [Requisitos](#Requisitos)

4. [Instalação](#Instalação)

5. [Configuração](#Configuração)
6. [Uso Basico](#Uso_Basico)
7. Funções de Geração de Dados
   1. objectId()
   2. index()
   3. guid()
   4. boolean()
   5. money()
   6. floating()
   7. integer()
   8. custom()
   9. fullName()
   10. firstName()
   11. surName()
   12. gender()
   13. company()
   14. email()
   15. phone()
   16. address()
   17. street()
   18. number()
   19. bairro()
   20. state()
   21. country()
   22. lorem()
   23. date()
   24. latitude()
   25. longitude()
   26. repeat()

## Requisitos
PHP 8.x ou superior

## Instalação
Para instalar a biblioteca, você pode utilizar o Composer:
```
composer require php-json-generator
```
Ou você pode incluir manualmente o arquivo em seu projeto.

## Uso_Basico
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

$jsonConfig = '[{"repeat()": {"options": {"qtd": 1}, "data": {"objectId": {"objectId()": {"options": {"qtd": {"integer()": {"options": {"min": "0", "max": "4"}}}}}}}, "index1": "index()", "guid": "guid()"}}]';

$generator = new PhpJsonGenerator($jsonConfig);
$result = $generator->generate();
```

## Contribuição
Se você tem interesse em contribuir para o projeto, veja as orientações sobre como fazer isso.