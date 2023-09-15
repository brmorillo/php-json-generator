<?php

namespace Rmorillo\JsonGenerator;

class Numbers
{
    private Util $util;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->util = new Util();
    }

    private function integer(int $min = 0, int $max = 0, int $falsePercentage = 0, int $nullPercentage = 0): int|bool|null
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

    private function boolean($falsePercentage = 0, $nullPercentage = 0, $deniReturn = true): bool|null
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

    private function float(int $falsePercentage = 0, int $nullPercentage = 0, int $min = 0, int $max = 9, int $decimals = 2, bool $round = false): float|bool|null
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
            $decimals = 15;
        }
        if ($decimals < 1) {
            $decimals = 1;
        }

        $scale = 10 ** $decimals;
        $randomFloat = $min + (rand() / getrandmax()) * ($max - $min);
        $value = round($randomFloat * $scale) / $scale;

        if ($round) {
            $value = round($value);
        }
        return $value;
    }

    private function money(int $falsePercentage = 0, int $nullPercentage = 0, int $min = 0, int $max = 0, int $decimals = 2, bool $round = false, string $prefix = 'R$ ', string $separator = '.', $thousand = ',')
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

        $randomFloat = $this->getFloat(0, 0, $min, $max, $decimals, $round);

        $formattedFloat = number_format($randomFloat, $decimals, $separator, $thousand);

        return $prefix . $formattedFloat;
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
    public function getBoolean(int $falsePercentage = 0, int $nullPercentage = 0, bool $deniReturn = true): bool|null
    {
        return $this->boolean($falsePercentage, $nullPercentage, $deniReturn);
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
    public function getFloat(int $falsePercentage = 0, int $nullPercentage = 0, int $min = 0, int $max = 9, int $decimals = 2, bool $round = false): float|bool|null
    {
        return $this->float($falsePercentage, $nullPercentage, $min, $max, $decimals, $round);
    }

    public function getMoney(int $falsePercentage = 0, int $nullPercentage = 0, int $min = 0, int $max = 9, int $decimals = 2, bool $round = false, string $prefix = 'R$ ', string $separator = '.', string $thousand = ','): string|bool|null
    {
        return $this->money($falsePercentage, $nullPercentage, $min, $max, $decimals, $round, $prefix, $separator, $thousand);
    }

    public function getPhoneNumber(
        int $falsePercentage = 0,
        int $nullPercentage = 0,
        string $ddi = '55',
        string $ddd = '17',
        string $phoneNumber = '987654321',
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

        $this->util->trataValor($ddi, 'string', '55');
        $this->util->trataValor($ddd, 'string', '17');
        $this->util->trataValor($phoneNumber, 'string', '987654321');
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
                $phoneNumber .= $this->getInteger(0, 9);
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
                $ddd .= $this->getInteger(0, 9);
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
                $ddi .= $this->getInteger(0, 9);
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
}
