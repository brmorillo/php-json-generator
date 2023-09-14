<?php

namespace Rmorillo\JsonGenerator;

class Util
{
    public function __construct()
    {
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function selectItemOnArray(array $array): mixed
    {
        return $array[rand(0, count($array) - 1)];
    }

    /**
     * @param int $falsePercentage
     * @param int $nullPercentage
     * @return bool|null
     */
    public function falseOrNull(int $falsePercentage = 0, int $nullPercentage = 0): bool|null
    {
        if ($falsePercentage === 0 && $nullPercentage === 0) {
            return true;
        }

        $this->trataValor($nullPercentage, 'integer', 0);
        $this->trataValor($falsePercentage, 'integer', 0);

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
     * @param mixed $tipoEsperado
     * @param mixed $valorPadrao
     * @return void
     */
    public function trataValor(&$valor, $tipoEsperado, $valorPadrao)
    {
        if ($tipoEsperado === 'integer') {
            $valor = $valor ?? $valorPadrao;
            $valor = gettype($valor) === 'float' ? round($valor) : $valor;
        }
    }
}
