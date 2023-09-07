<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$jsonOriginal = json_decode(file_get_contents("php://input"), true);

if (!empty($jsonOriginal)) {
    $index = 1;
    $return = replaceAll($jsonOriginal, $index);

    http_response_code(200);
    echo json_encode($return);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Nenhum dado recebido."]);
}

function keyType($valorAtual)
{
    if (isset($valorAtual['repeat()'])) {
        return 1;
    }
    return 0;
}

function replaceAll($jsonAtual, &$index)
{
    $localIndex = $index;
    foreach ($jsonAtual as $key => &$value) {
        if (is_array($value)) {
            if (keyType($value) == 1) {
                $repeatIndex = 1;
                $value = replaceRepeat($value['repeat()'], $repeatIndex);
            } else {
                $subIndex = 1; // reinicialize o índice para este nível
                $value = replaceAll($value, $subIndex);
            }
        } else {
            if ($value === 'index()') {
                $value = $localIndex;
                $localIndex++;
            }
        }
    }
    $index = $localIndex;
    return $jsonAtual;
}

function replaceRepeat($value, &$index)
{
    $min = $value['options']['min'];
    $max = $value['options']['max'];
    $jsonAtual = [];
    $localIndex = $index;
    for ($i = 1; $i <= rand($min, $max); $i++) {
        $jsonAtual[] = replaceAll($value['data'], $localIndex);
    }
    $index = $localIndex;
    return $jsonAtual;
}
