<?php

namespace Rmorillo\JsonGenerator;

class Util
{
    public function __construct()
    {
    }

    public function selectItemOnArray(array $array): mixed
    {
        return $array[rand(0, count($array) - 1)];
    }
}
