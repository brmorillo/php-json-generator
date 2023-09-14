<?php

namespace Rmorillo\JsonGenerator;

use Exception;

class hash
{
    private string $guid, $objectId;

    /**
     * @param int $length
     * @return void
     */
    public function __construct(int $length = 1)
    {
        $this->guid();
        $this->objectId($length);
    }

    /**
     * @param mixed $length
     * @return string
     */
    private function generateRandomhash($length)
    {
        //Caso length não exista é definido como 1.
        $length = ($length) ?? 1;
        //Caso o valor seja float, arredonda ele.
        $length = gettype($length) == 'float' ? round($length) : $length;

        //Caso o valor seja 0 ou menor, ou outro tipo não esperado não é possível gerar um hash, então ele é definido como 1.
        if ($length < 1 || gettype($length) != 'integer') {
            $length = 1;
        }

        if ($length > 50) {
            $length = 50;
        }

        //return md5(uniqid(rand(), true));
        return bin2hex(random_bytes($length));
    }

    /**
     * @return void
     */
    private function guid(): void
    {
        if (function_exists('com_create_guid')) {
            $this->guid = trim(com_create_guid(), '{}');
        } else {
            $this->guid = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                rand(0, 0xffff),
                rand(0, 0xffff),
                rand(0, 0xffff),
                rand(0, 0x0fff) | 0x4000,
                rand(0, 0x3fff) | 0x8000,
                rand(0, 0xffff),
                rand(0, 0xffff),
                rand(0, 0xffff)
            );
        }
    }

    /**
     * @param mixed $length
     * @return void
     */
    private function objectId($length): void
    {
        /*
        * TODO: Chamar de forma recursiva deve ser implementado em outra User Story.
        if (is_array($length)) {
            //Caso a qtd seja um array (Ou seja, outra função gerando ela), chama de forma recursiva a função para gerar o valor.
            $length = array_values($this->replaceOthers($value['options']))[0];
        }
        */
        $this->objectId = $this->generateRandomHash($length);
    }

    //Get and Set methods.
    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }
}
