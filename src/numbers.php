<?php

namespace Rmorillo\JsonGenerator;

class Numbers
{
    private Util $util;

    /**
     * __construct
     * @return void
     */
    public function __construct()
    {
        $this->util = new Util();
    }

    /**
     * @param int $min
     * @param int $max
     * @param int $falsePercentage
     * @param int $nullPercentage
     * @return float|bool|null
     */
    private function integer($min = 0, $max = 0, $falsePercentage = 0, $nullPercentage = 0): int|bool|null
    {
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }
        $this->util->trataValor($min, 'integer', 0);
        $this->util->trataValor($max, 'integer', 9);

        return rand($min, $max);
    }

    /**
     * @param int $min
     * @param int $max
     * @param int $falsePercentage
     * @param int $nullPercentage
     * @return int|bool|null
     */
    public function getInteger($min = 0, $max = 9, $falsePercentage = 0, $nullPercentage = 0): int|bool|null
    {
        return $this->integer($min, $max, $falsePercentage, $nullPercentage);
    }
}
