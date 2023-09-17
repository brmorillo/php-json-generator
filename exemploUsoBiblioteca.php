<?php

/**
 * Este exemplo usa a biblioteca para retornar um firstName() aleatório.
 */
require_once __DIR__ . '/vendor/autoload.php';

use Rmorillo\JsonGenerator\JsonProcessor;
use Rmorillo\JsonGenerator\Name;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Usa o JSONProcessor.
$name = new Name();
echo $name->firstName();
exit();
