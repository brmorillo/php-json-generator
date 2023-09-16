<?php
require_once __DIR__ . '/vendor/autoload.php';

use Rmorillo\JsonGenerator\JsonProcessor;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$jsonOriginalToArray = json_decode(file_get_contents("php://input"), true);
//Caso não tenha parâmetros, retorna erro.
if (empty($jsonOriginalToArray)) {
    echo json_encode(['Message' => 'Erro: Nenhum parâmetro enviado']);
    exit();
}

//Usa o JSONProcessor.
$jsonProcessor = new JsonProcessor($jsonOriginalToArray);
$jsonProcessor->process();
exit();
