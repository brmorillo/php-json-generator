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
### Gerar um Número Inteiro Aleatório
```
echo generateInteger(['options' => ['min' => 1, 'max' => 100]]);
```

### Gerar um Número de Ponto Flutuante Aleatório
```
echo generateFloating(['options' => ['min' => 0, 'max' => 1]]);
```

### Gerar um Nome Aleatório
```
echo generateName();
```

### Gerar um Endereço Aleatório
```
echo generateAddress();
```

### Gerar Texto "Lorem Ipsum"
```
echo generateLorem(['options' => ['type' => 'words', 'length' => 5]]);
```

### Gerar Coordenadas Geográficas Aleatórias
```
echo generateLatitude(['options' => ['min' => -90, 'max' => 90]]);
echo generateLongitude(['options' => ['min' => -180, 'max' => 180]]);
```

### Gerar uma Data Aleatória
```
echo generateDate(['options' => ['min' => '01/01/1970', 'max' => 'agora']]);
```

## Documentação Completa
Para obter mais detalhes e exemplos, consulte a documentação completa do projeto.

## Contribuição
Se você tem interesse em contribuir para o projeto, veja as orientações sobre como fazer isso.