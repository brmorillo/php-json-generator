<?php
// Configuração do cabeçalho para permitir requisições CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtenha o corpo da requisição
$data = json_decode(file_get_contents("php://input"));

// Simulação de um processamento de dados recebidos
if (!empty($data)) {
    file_put_contents("log.json", json_encode($data) . ",\n", FILE_APPEND);

    //Executa todas as sustituições
    $return = replaceAll($data);

    // Neste exemplo, vou simplesmente devolver os dados como um JSON.
    http_response_code(200); // OK
    echo json_encode(["message" => "Dados recebidos com sucesso.", "return" => $return]);
} else {
    http_response_code(400); // Requisição inválida
    echo json_encode(["message" => "Nenhum dado recebido."]);
}

function replaceAll($data) {
    $returnReplaceRepeatData = replaceRepeat($data);
    return $returnReplaceRepeatData;
}

function replaceRepeat($data) {
    if(is_array($data)) {
        return json_encode($data);
    } else {
        return 'false';
    }
}
?>
