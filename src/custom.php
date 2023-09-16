<?php

namespace Rmorillo\JsonGenerator;

class Custom
{
    private Util $util;
    public function __construct()
    {
        $this->util = new Util;
    }

    public function custom(int $falsePercentage = 0, int $nullPercentage = 0, array $array = [], int $start = 0, int $subtract = 1): mixed
    {
        echo json_encode($array);
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }
        return $this->util->selectItemOnArray($array, $start, $subtract);
    }

    /**
     * @param int $falsePercentage
     * @param int $nullPercentage
     * @param array $array
     * @param int $start
     * @param int $subtract
     * @return mixed
     */
    public function gender(int $falsePercentage = 0, int $nullPercentage = 0, array $array = [], int $start = 0, int $subtract = 1): mixed
    {
        if (empty($array)) {
            $array = [
                '1' => 'Male',
                '2' => 'Femeale',
                '3' => 'Others'
            ];
        }
        return $this->custom($falsePercentage, $nullPercentage, $array, $start, $subtract);
    }
}
