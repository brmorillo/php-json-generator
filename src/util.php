<?php

namespace Rmorillo\JsonGenerator;

class Util
{
    public function __construct()
    {
    }

    /**
     * @param array $array
     * @param int $start
     * @param int $subtract
     * @return mixed
     */
    public function selectItemOnArray(array $array, int $start = 0, int $subtract = 1): mixed
    {
        return $array[rand($start, count($array) - $subtract)];
    }

    /**
     * @param int $falsePercentage
     * @param int $nullPercentage
     * @return bool|null
     */
    public function falseOrNull(int $falsePercentage = 0, int $nullPercentage = 0): bool|null
    {
        $this->trataValor($nullPercentage, 'integer', 0);
        $this->trataValor($falsePercentage, 'integer', 0);
        if ($falsePercentage == 0 && $nullPercentage == 0) {
            return true;
        }

        $value = true;
        if ($falsePercentage > 0) {
            if (rand(1, 100) <= $falsePercentage) {
                $value = false;
            }
        }
        if ($nullPercentage > 0) {
            if (rand(1, 100) <= $nullPercentage) {
                $value = null;
            }
        }
        return $value;
    }

    /**
     * @param mixed $valor
     * @param string $tipoEsperado
     * @param mixed $valorPadrao
     * @return void
     */
    public function trataValor(&$valor, string $tipoEsperado, $valorPadrao): void
    {
        if ($tipoEsperado === 'integer') {
            $valor = $valor ?? $valorPadrao;
            $valor = gettype($valor) === 'float' ? round($valor) : $valor;
            return;
        }
    }
}
