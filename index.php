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
    $data = substr(json_encode($data), 1, -1);
    file_put_contents("log.json", $data . ",\n", FILE_APPEND);

    //Executa todas as bustituições
    replaceAll();

    // Neste exemplo, vou simplesmente devolver os dados como um JSON.
    http_response_code(200); // OK
    echo json_encode(["message" => "Dados recebidos com sucesso.", "data" => $data]);
} else {
    http_response_code(400); // Requisição inválida
    echo json_encode(["message" => "Nenhum dado recebido."]);
}

function replaceAll() {
    replaceRepeat();
}

function replaceRepeat() {
    echo "repeat";
}
?>
