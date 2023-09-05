<?php
// Configuração do cabeçalho para permitir requisições CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtenha o corpo da requisição
$data = json_decode(file_get_contents("php://input"), true);

// Simulação de um processamento de dados recebidos
if (!empty($data)) {
    //file_put_contents("log.json", json_encode($data) . ",\n", FILE_APPEND);

    //Executa todas as sustituições
    $return = replaceAll($data);

    // Neste exemplo, vou simplesmente devolver os dados como um JSON.
    http_response_code(200); // OK
    echo json_encode($return);
} else {
    http_response_code(400); // Requisição inválida
    echo json_encode(["message" => "Nenhum dado recebido."]);
}

function replaceAll($data)
{
    $returnReplaceRepeatData = replaceRepeat($data);
    return $returnReplaceRepeatData;
}

function replaceRepeat($data)
{
    // Encontrar todas as chaves "repeat()"
    $repeatKeys = [];

    if (is_array($data)) {
        findRepeatKeys($data, $repeatKeys);
        return $repeatKeys;
    } else {
        return 'false';
    }
}

function findRepeatKeys($array, &$repeatKeys)
{
    $path = [];
    foreach ($array as $key => $value) {
        $path[] = $key;
        echo json_encode($path);
        //echo "Key: $key \nValue: " . json_encode($value) . "\n\n\n";
        $isRepeat = (isset($value['repeat()']) || isset($key['repeat()']));
        if ($isRepeat) {
            $repeat['key'] = $key;
            $repeat['min'] = $value['repeat()']['options']['min'];
            $repeat['max'] = $value['repeat()']['options']['max'];
            $repeatKeys[] = $repeat;
        }
        if (gettype($value) == 'array' || gettype($value) == 'object') {
            findRepeatKeys($value, $repeatKeys);
        }
    }
}
