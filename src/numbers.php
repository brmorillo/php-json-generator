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
    private function integer(int $min = 0, int $max = 0, int $falsePercentage = 0, int $nullPercentage = 0): int|bool|null
    {
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }
        $this->util->trataValor($min, 'integer', 0);
        $this->util->trataValor($max, 'integer', 9);

        return rand($min, $max);
    }

    private function boolean($falsePercentage = 0, $nullPercentage = 0, $deniReturn = true): bool|null
    {
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            if ($deniReturn) {
                $falseOrNull = !$falseOrNull;
            }
            return $falseOrNull;
        }

        $return = (rand(0, 1) === 1);
        if ($deniReturn) {
            $return = !$return;
        }
        return $return;
    }

    /**
     * @param int $min
     * @param int $max
     * @param int $falsePercentage
     * @param int $nullPercentage
     * @return int|bool|null
     */
    public function getInteger(int $min = 0, int $max = 9, int $falsePercentage = 0, int $nullPercentage = 0): int|bool|null
    {
        return $this->integer($min, $max, $falsePercentage, $nullPercentage);
    }

    /**
     * Retorna um valor boolean ou nulo de acordo com os parâmetros e aleatoriedade.
     *
     * Ao definir deniReturn como true, o retorno será o contrário do valor gerado, ou seja, se o valor gerado for null/false o retorno será true.
     * Assim, é possível escolher, ex: 99% de true, falsePercentagem/nullpercentage = 99 e deniReturn = true.
     *
     * @param mixed $falsePercentage
     * @param mixed $nullPercentage
     * @param bool $deniReturn
     * @return bool|null
     */
    public function getBoolean($falsePercentage = 0, $nullPercentage = 0, $deniReturn = true): bool|null
    {
        return $this->boolean($falsePercentage, $nullPercentage, $deniReturn);
    }
}
