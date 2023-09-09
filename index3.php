<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$jsonOriginal = json_decode(file_get_contents("php://input"), true);

if (!empty($jsonOriginal)) {
    $return = replaceAll($jsonOriginal);
    http_response_code(200);
    echo json_encode($return);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Nenhum dado recebido."]);
}

function replaceAll($jsonOriginal)
{
    $jsonRepeatReplaced = replaceRepeat($jsonOriginal);
    return replaceOthers($jsonRepeatReplaced[0]);
}

function replaceRepeat($jsonAtual)
{
    $result = [];

    if (gettype($jsonAtual) == 'array') {
        foreach ($jsonAtual as $key => $value) {
            if ($key === "repeat()") {
                $qtd = $value['options']['qtd'];
                $data = $value['data'];
                $result = array_merge($result, repeatJsonData($data, $qtd));
            } elseif (is_array($value)) {
                $result[$key] = replaceRepeat($value);
            } else {
                $result[$key] = $value;
            }
        }
    }

    return $result;
}

function repeatJsonData($data, $qtd)
{
    $result = [];
    for ($i = 1; $i <= $qtd; $i++) {
        $result[] = replaceRepeat($data);
    }
    return $result;
}


function falseOrNull($falsePercentage, $nullPercentage)
{
    $value = true;
    if ($falsePercentage != 0 || $nullPercentage != 0) {
        if ($falsePercentage) {
            if (rand(1, 100) <= $falsePercentage) {
                $value = false;
            }
        }
        if ($nullPercentage) {
            if (rand(1, 100) <= $nullPercentage) {
                $value = null;
            }
        }
    }
    return $value;
}


function replaceOthers($jsonAtual)
{
    $index = 1;
    //Verifica se o JSON é um array, para rodar o foreach dentro dele.
    if (gettype($jsonAtual) == 'array') {
        foreach ($jsonAtual as $key => $value) {
            //Verifica se o item atual é um array, para poder chamar de forma recursiva a função.
            if (is_array($value)) {

                //Caso seja um objectId().
                if (isset($value['objectId()'])) {
                    $value = generateObjectId($value['objectId()']);
                }

                //Caso seja um integer().
                if (isset($value['integer()'])) {
                    $value = generateInteger($value['integer()']);
                }

                //Caso seja um boolean().
                if (isset($value['boolean()'])) {
                    $value = generateBoolean($value['boolean()']);
                }

                //Caso seja um floating().
                if (isset($value['floating()'])) {
                    $value = generateFloating($value['floating()']);
                }

                //Caso seja um money().
                if (isset($value['money()'])) {
                    $value = generateMoney($value['money()']);
                }

                //Caso seja um custom().
                if (isset($value['custom()'])) {
                    $value = selectCustom($value['custom()']);
                }

                $jsonAtual[$key] = replaceOthers($value);
            } else {
                //echo $value . "\n";
                if ($value === 'guid()' || $key === 'guid()') {
                    $jsonAtual[$key] = generateGuid();
                }
                if ($value === 'index()' || $key === 'index()') {
                    $jsonAtual[$key] = $index;
                    $index++;
                }
            }
        }
    }

    return $jsonAtual;
}

function generateInteger($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $min = ($value['options']['min']) ?? 1;
    $max = ($value['options']['max']) ?? 9;

    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }
    return rand($min, $max);
}

function generateRandomHash($qtd = 1)
{
    //return md5(uniqid(rand(), true));
    return bin2hex(random_bytes($qtd));
}

function generateGuid()
{
    if (function_exists('com_create_guid')) {
        return trim(com_create_guid(), '{}');
    } else {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            rand(0, 0xffff),
            rand(0, 0xffff),
            rand(0, 0xffff),
            rand(0, 0x0fff) | 0x4000,
            rand(0, 0x3fff) | 0x8000,
            rand(0, 0xffff),
            rand(0, 0xffff),
            rand(0, 0xffff)
        );
    }
}

function generateObjectId($value)
{
    $value['options']['qtd'] = ($value['options']['qtd']) ?? 1;

    if (is_array($value['options']['qtd'])) {
        //Caso a qtd seja um array (Ou seja, outra função gerando ela), chama de forma recursiva a função para gerar o valor.
        $value['options']['qtd'] = array_values(replaceOthers($value['options']))[0];
    }

    //Caso o valor seja 0 não é possível gerar um hash, então ele é definido como 1.
    if ($value['options']['qtd'] == 0)
        $value['options']['qtd'] = 1;

    return generateRandomHash($value['options']['qtd']);
}

function generateBoolean($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;

    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }
    return rand(0, 1) === 1;
}

function generateFloating($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $min = ($value['options']['min']) ?? 1;
    $max = ($value['options']['max']) ?? 9;
    $decimals = ($value['options']['decimals']) ?? 2;
    $round = ($value['options']['round']) ?? false;

    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }

    $scale = 10 ** $decimals;
    $randomFloat = $min + (rand() / getrandmax()) * ($max - $min);
    $value = round($randomFloat * $scale) / $scale;
    if ($round) {
        $value = round($value);
    }
    return $value;
}

function generateMoney($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $min = ($value['options']['min']) ?? 1;
    $max = ($value['options']['max']) ?? 9;
    $decimals = ($value['options']['decimals']) ?? 2;
    $prefix = ($value['options']['prefix']) ?? '';
    $separator = ($value['options']['separator']) ?? ',';
    $thousand = ($value['options']['thousand']) ?? '.';


    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }

    $scale = 10 ** $decimals;
    $randomFloat = $min + (rand() / getrandmax()) * ($max - $min);
    $randomFloat = round($randomFloat * $scale) / $scale;

    $formattedFloat = number_format($randomFloat, $decimals, $separator, $thousand);

    return $prefix . $formattedFloat;
}

function selectCustom($value)
{
    return $value[rand(1, count($value))];
}
