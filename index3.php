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

function replaceOthers($jsonAtual)
{
    //echo json_encode($jsonAtual);
    //Verifica se o JSON é um array, para rodar o foreach dentro dele.
    if (gettype($jsonAtual) == 'array') {
        foreach ($jsonAtual as $key => $value) {
            //Verifica se o item atual é um array, para poder chamar de forma recursiva a função.
            if (is_array($value)) {
                //Caso seja um objectId().
                if (isset($value['objectId()'])) {
                    if (is_array($value['objectId()']['options']['qtd'])) {
                        //Caso a qtd seja um array (Ou seja, outra função gerando ela), chama de forma recursiva a função para gerar o valor.
                        $value['objectId()']['options']['qtd'] = array_values(replaceOthers($value['objectId()']['options']))[0];
                    }
                    if ($value['objectId()']['options']['qtd'] == 0)
                        $value['objectId()']['options']['qtd'] = 1;

                    $value = generateRandomHash($value['objectId()']['options']['qtd']);
                }

                //Caso seja um integer().
                if (isset($value['integer()'])) {
                    //echo json_encode($value);
                    $value = generateInteger($value['integer()']);
                }

                //Caso seja um random().
                if (isset($value['random()'])) {
                    $value = $value['random()']['options'][rand(0, count($value['random()']['options']) - 1)];
                }
                $jsonAtual[$key] = replaceOthers($value);
            }
        }
    } else {
    }

    return $jsonAtual;
}

function repeatJsonData($data, $qtd)
{
    $result = [];
    for ($i = 1; $i <= $qtd; $i++) {
        $result[] = replaceRepeat($data);
    }
    return $result;
}

function generateInteger($value)
{
    $min = (isset($value['options']['min'])) ? $value['options']['min'] : 1;
    $max = (isset($value['options']['max'])) ? $value['options']['max'] : 9;
    $falsePercentage = (isset($value['options']['falsePercentage'])) ? $value['options']['falsePercentage'] : 0;
    $nullPercentage = (isset($value['options']['nullPercentage'])) ? $value['options']['nullPercentage'] : 0;

    $falseNull = false;
    if (rand(1, 100) <= $falsePercentage) {
        $falseNull = true;
        $value = false;
    }
    if (rand(1, 100) <= $nullPercentage) {
        $falseNull = true;
        $value = null;
    }
    if (!$falseNull) {
        $value = rand($min, $max);
    }
    return $value;
}

function generateRandomHash($qtd)
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
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
