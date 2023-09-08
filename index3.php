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
    return replaceRepeat($jsonOriginal);
}

function replaceRepeat($jsonOriginal)
{
    $result = [];

    foreach ($jsonOriginal as $key => $value) {
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

    return $result;
}

function repeatJsonData($json, $qtd)
{
    $result = [];
    for ($i = 1; $i <= $qtd; $i++) {
        $result[] = replaceRepeat($json);
    }
    return $result;
}
