<?php

/**
 * Este exemplo usa a biblioteca para retornar um firstName() aleatório.
 */
require_once __DIR__ . '/vendor/autoload.php';

use Rmorillo\JsonGenerator\Name as jsonGeneratorName;

//Usa o JSONProcessor.
$name = new jsonGeneratorName();
echo $name->firstName();
exit();
