<?php

namespace Rmorillo\JsonGenerator;

use DateTimeZone;

class Date
{
    private $timeZone;
    /**
     * @return void
     */
    public function __construct()
    {
        $this->timeZone = new \DateTimeZone('UTC');
    }

    /**
     * Gera uma data aleatória entre duas datas.
     * @param string $min
     * @param string $max
     * @param string $format
     * @return string
     */
    public function dateBetween(string $min = '1970-01-01 00:00:00', string $max = '2023-09-16 01:58:00', string $format = 'Y-m-d H:i:s'): string|bool
    {
        $min = strtotime($min);
        $max = strtotime($max);

        $val = rand($min, $max);
        return date($format, $val);
    }

    /**
     * @return DateTimeZone
     */
    public function getUtc(): \DateTimeZone
    {
        return $this->timeZone;
    }

    /**
     * @param DateTimeZone $timeZone
     * @return mixed
     */
    public function getNow(DateTimeZone $timeZone = new \DateTimeZone('UTC')): mixed
    {
        return new \DateTime('now', $timeZone);
    }
}
