<?php

namespace Rmorillo\JsonGenerator;

class Number
{
    private Util $util;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->util = new Util;
    }

    /**
     * @param int $min
     * @param int $max
     * @param int $falsePercentage
     * @param int $nullPercentage
     * @return int|bool|null
     */
    public function integer(int $min = 0, int $max = 9, int $falsePercentage = 0, int $nullPercentage = 0): int|bool|null
    {
        $this->util->trataValor($falsePercentage, 'integer', 0);
        $this->util->trataValor($nullPercentage, 'integer', 0);
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }
        $this->util->trataValor($min, 'integer', 0);
        $this->util->trataValor($max, 'integer', 9);

        return rand($min, $max);
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
    public function getBoolean(int $falsePercentage = 0, int $nullPercentage = 0, bool $deniReturn = true): bool|null
    {
        $this->util->trataValor($falsePercentage, 'integer', 0);
        $this->util->trataValor($nullPercentage, 'integer', 0);
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
     * @param bool $falsePercentage
     * @param bool $nullPercentage
     * @param int $min
     * @param int $max
     * @param int $decimals
     * @param bool $round
     * @return float|bool|null
     */
    public function float(int $falsePercentage = 0, int $nullPercentage = 0, int|float $min = 0, int|float $max = 9, int $decimals = 2, bool $round = false): float|bool|null
    {
        $this->util->trataValor($falsePercentage, 'integer', 0);
        $this->util->trataValor($nullPercentage, 'integer', 0);
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }

        $this->util->trataValor($min, 'float', 0);
        $this->util->trataValor($max, 'float', 9);
        $this->util->trataValor($decimals, 'integer', 2);
        $this->util->trataValor($round, 'boolean', false);

        if ($decimals > 14) {
            $decimals = 14;
        }
        if ($decimals < 1) {
            $decimals = 1;
        }

        $scale = pow(10, $decimals);
        $randomFloat = $min + (mt_rand() / mt_getrandmax()) * ($max - $min);
        $randomFloat = round($randomFloat * $scale) / $scale;

        if ($round) {
            $randomFloat = round($randomFloat);
        }
        return $randomFloat;
    }

    public function money(int $falsePercentage = 0, int $nullPercentage = 0, int $min = 0, int $max = 9, int $decimals = 2, bool $round = false, string $prefix = 'R$ ', string $separator = '.', string $thousand = ','): string|bool|null
    {
        $this->util->trataValor($falsePercentage, 'integer', 0);
        $this->util->trataValor($nullPercentage, 'integer', 0);
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }

        $this->util->trataValor($min, 'integer', 0);
        $this->util->trataValor($max, 'integer', 9);
        $this->util->trataValor($decimals, 'integer', 2);
        $this->util->trataValor($round, 'boolean', false);
        $this->util->trataValor($prefix, 'string', 'R$');
        $this->util->trataValor($separator, 'string', '.');
        $this->util->trataValor($thousand, 'string', ',');

        $randomFloat = $this->float(0, 0, $min, $max, $decimals, $round);

        $formattedFloat = number_format($randomFloat, $decimals, $separator, $thousand);

        return $prefix . $formattedFloat;
    }

    /**
     * @param int $falsePercentage
     * @param int $nullPercentage
     * @param string $ddi
     * @param string $ddd
     * @param string $phoneNumber
     * @param int $ddiLength
     * @param int $dddLength
     * @param int $phoneLength
     * @param bool $plus
     * @param bool $spaceAfterPlus
     * @param bool $parentheses
     * @param bool $spaceAfterParentheses
     * @param bool $dash
     * @param int $dashBefore
     * @param bool $spaceAroundDash
     * @return string|bool|null
     */
    public function phoneNumber(
        int $falsePercentage = 0,
        int $nullPercentage = 0,
        string $ddi = '',
        string $ddd = '',
        string $phoneNumber = '',
        int $ddiLength = 2,
        int $dddLength = 2,
        int $phoneLength = 9,
        bool $plus = true,
        bool $spaceAfterPlus = true,
        bool $parentheses = true,
        $spaceAfterParentheses = true,
        bool $dash = true,
        int $dashBefore = 4,
        bool $spaceAroundDash = false
    ): string|bool|null {
        //TODO: Não está adicionando o hash no número.
        $this->util->trataValor($falsePercentage, 'integer', 0);
        $this->util->trataValor($nullPercentage, 'integer', 0);
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }

        $this->util->trataValor($ddi, 'string', '');
        $this->util->trataValor($ddd, 'string', '');
        $this->util->trataValor($phoneNumber, 'string', '');
        $this->util->trataValor($ddiLength, 'integer', 2);
        $this->util->trataValor($dddLength, 'integer', 2);
        $this->util->trataValor($plus, 'boolean', true);
        $this->util->trataValor($spaceAfterPlus, 'boolean', true);
        $this->util->trataValor($parentheses, 'boolean', true);
        $this->util->trataValor($spaceAfterParentheses, 'boolean', true);
        $this->util->trataValor($dash, 'boolean', true);
        $this->util->trataValor($dashBefore, 'integer', 4);
        $this->util->trataValor($spaceAroundDash, 'boolean', false);

        //Define o máximo do DDI.
        $ddiLength = $ddiLength > 9 ? 9 : $ddiLength;
        //Caso o DDI esteja abaixo do mínimo, define como 2.
        $ddiLength = $ddiLength < 1 ? 2 : $ddiLength;

        //Define o máximo do DDD.
        $dddLength = $dddLength > 9 ? 9 : $dddLength;
        //Caso o DDD esteja abaixo do mínimo, define como 2.
        $dddLength = $dddLength < 1 ? 2 : $dddLength;

        //Caso não tenha sido definido o número de telefone, gera um número aleatório.
        if (!$phoneNumber) {
            //Define o máximo do número de telefone.
            $phoneLength = ($phoneLength >= 15) ? 15 : $phoneLength;
            //Caso o número de telefone esteja abaixo do mínimo, define como 9.
            $phoneLength = ($phoneLength < 1) ? 9 : $phoneLength;
        } else {
            $phoneLength = strlen($phoneNumber);
        }

        $dash = ($dashBefore < 1 || $dashBefore >= $phoneLength) ? false : $dash;

        if (!$phoneNumber) {
            for ($i = 1; $i <= $phoneLength; $i++) {
                $phoneNumber .= $this->integer(0, 9);
            }
            if ($i == 1 && $phoneNumber == 0) {
                $phoneNumber = 9;
            }

            //Apenas adiciona o dash caso o número seja maior que o número de caracteres para se colocar o dash.
            if ($dash && $phoneLength > $dashBefore) {
                $position = $phoneLength - $dashBefore;
                $phoneNumber = substr($phoneNumber, 0, $position) . $spaceAroundDash . substr($phoneNumber, $position);
            }
        } else {
            //Apenas adiciona o dash caso o número seja maior que o número de caracteres para se colocar o dash.
            if ($phoneLength >= $dashBefore) {
                $position = $phoneLength - $dashBefore;
                $phoneNumber = substr($phoneNumber, 0, $position) . $spaceAroundDash . substr($phoneNumber, $position);
            }
        }
        if (!$ddd) {
            for ($i = 1; $i <= $dddLength; $i++) {
                $ddd .= $this->integer(0, 9);
            }
            if ($parentheses) {
                $ddd = '(' . $ddd . ')';
                if ($spaceAfterParentheses) {
                    $ddd .= ' ';
                }
            }
            $phoneNumber = $ddd . $phoneNumber;
        } else {
            if ($parentheses) {
                $ddd = '(' . $ddd . ')';
            }
            if ($spaceAfterParentheses) {
                $ddd .= ' ';
            }
            $phoneNumber = $ddd . $phoneNumber;
        }
        if (!$ddi) {
            for ($i = 1; $i <= $ddiLength; $i++) {
                $ddi .= $this->integer(0, 9);
                if ($i == 1 && $ddi == 0) {
                    $ddi = 1;
                }
            }
            if ($plus) {
                $ddi = '+' . $ddi;
                if ($spaceAfterPlus) {
                    $ddi .= ' ';
                }
            }
            $phoneNumber = $ddi . $phoneNumber;
        } else {
            if ($plus) {
                $ddi = '+' . $ddi;
                if ($spaceAfterPlus) {
                    $ddi .= ' ';
                }
            }
            $phoneNumber = $ddi . $phoneNumber;
        }
        return $phoneNumber;
    }

    public function longitude(int $falsePercentage = 0, int $nullPercentage = 0, float $min = -180.000001, float $max = 180.0): float
    {
        return $this->latOrLng($falsePercentage, $nullPercentage, $min, $max);
    }

    public function latitude(int $falsePercentage = 0, int $nullPercentage = 0, float $min = -90.000001, float $max = 90.0): float
    {
        return $this->latOrLng($falsePercentage, $nullPercentage, $min, $max);
    }

    public function latOrLng(int $falsePercentage = 0, int $nullPercentage = 0, float $min = -90.000001, float $max = 90.0): float
    {
        $this->util->trataValor($falsePercentage, 'integer', 0);
        $this->util->trataValor($nullPercentage, 'integer', 0);
        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }
        $this->util->trataValor($min, 'float', $min);
        $this->util->trataValor($max, 'float', $max);
        $min = ($min < $min) ? $min : $min;
        $max = ($max > $max) ? $max : $max;
        $min = ($min > $max) ? $max : $min;
        return $this->float($falsePercentage, $nullPercentage, $min, $max, 6, false);
    }
}
