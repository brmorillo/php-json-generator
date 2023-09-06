<?php
// Configuração do cabeçalho para permitir requisições CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtenha o corpo da requisição
$jsonOriginal = json_decode(file_get_contents("php://input"), true);

// Simulação de um processamento de dados recebidos
if (!empty($jsonOriginal)) {
    //file_put_contents("log.json", json_encode($data) . ",\n", FILE_APPEND);

    //Executa todas as sustituições
    $return = replaceAll($jsonOriginal);

    // Neste exemplo, vou simplesmente devolver os dados como um JSON.
    http_response_code(200); // OK
    echo json_encode($return);
} else {
    http_response_code(400); // Requisição inválida
    echo json_encode(["message" => "Nenhum dado recebido."]);
}

function replaceAll($jsonOriginal)
{
    $jsonCompleto = [];
    $returnReplaceRepeatData = replaceRepeat($jsonOriginal, $jsonCompleto);
    return $returnReplaceRepeatData;
}

function replaceRepeat($jsonOriginal) {
    $jsonCompleto = [];

    foreach ($jsonOriginal as $key => $value) {
        if (is_array($value) && isset($value['repeat()'])) {
            $min = $value['repeat()']['options']['min'];
            $max = $value['repeat()']['options']['max'];

            $jsonRepetido = [];
            for($i = $min; $i <= $max; $i++) {
                $jsonRepetido[] = replaceRepeat($value['repeat()']['data']);  // chama recursivamente
            }
            $jsonCompleto[$key] = $jsonRepetido;
        } else {
            $jsonCompleto[$key] = $value;
        }
    }

    return $jsonCompleto;
}