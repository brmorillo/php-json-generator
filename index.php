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
    $parentIndex = 0;
    $depth = 0;
    $replaceRepeatData = replaceRepeat($jsonOriginal, $parentIndex, $depth);
    return $replaceRepeatData;
}

function replaceRepeat($jsonOriginal, $parentIndex = 0, $depth = 0) {
    $jsonCompleto = [];
    $currentIndex = $parentIndex;

    foreach ($jsonOriginal as $key => $value) {
        if (is_array($value)) {
            if (isset($value['repeat()'])) {
                $min = $value['repeat()']['options']['min'];
                $max = $value['repeat()']['options']['max'];

                $jsonRepetido = [];
                for ($i = 1; $i <= rand($min, $max); $i++) {
                    $jsonRepetido[] = replaceRepeat($value['repeat()']['data'], $i, $depth);
                }
                $jsonCompleto[$key] = $jsonRepetido;
            } else {
                $jsonCompleto[$key] = replaceRepeat($value, $currentIndex, $depth);
            }
        } else {
            if ($value === "objectId()") {
                $jsonCompleto[$key] = generateRandomHash();
            } elseif ($value === "guid()") {
                $jsonCompleto[$key] = generateGuid(); // adiciona suporte para guid
            } elseif ($key === "index()" || $value === "index()") {
                $jsonCompleto[$key] = $currentIndex + $depth - 1;
            } elseif ($key === "bool()" || $value === "bool()") {
                $jsonCompleto[$key] = rand(0, 1) === 1;
            } else {
                $jsonCompleto[$key] = $value;
            }
        }
    }

    return $jsonCompleto;
}


function generateRandomHash()
{
    return md5(uniqid(rand(), true));
    //return bin2hex(random_bytes(16)); // gera um hash aleatório
}

function generateGuid() {
    if (function_exists('com_create_guid')) {
        return trim(com_create_guid(), '{}');
    } else {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}