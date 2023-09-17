<?php

namespace Rmorillo\JsonGenerator;

use DateTime;
use Exception;

class JsonProcessor
{
    private array $jsonOriginal;
    private Hash $hash;
    private Util $util;
    private Name $name;
    private Number $number;
    private Address $address;
    private Lorem $lorem;
    private Date $date;
    private Custom $custom;

    /**
     * @param array $jsonOriginal
     * @return void
     */
    public function __construct(array $jsonOriginal)
    {
        $this->hash = new Hash;
        $this->util = new Util;
        $this->name = new Name;
        $this->number = new Number;
        $this->address = new Address;
        $this->lorem = new Lorem;
        $this->date = new Date;
        $this->custom = new Custom;
        $this->jsonOriginal = $jsonOriginal;
    }

    /**
     * @return void
     */
    public function process(): void
    {
        $return = $this->replaceAll($this->jsonOriginal);
        http_response_code(200);
        echo json_encode($return);
    }

    /**
     * @param mixed $jsonOriginal
     * @return mixed
     */
    private function replaceAll(array $jsonOriginal): array
    {
        $jsonRepeatReplaced = $this->replaceRepeat($jsonOriginal);
        return $this->replaceOthers($jsonRepeatReplaced[0]);
    }

    /**
     * @param mixed $jsonAtual
     * @return array
     */
    function replaceRepeat($jsonAtual): array
    {
        $result = [];
        if (gettype($jsonAtual) == 'array') {
            foreach ($jsonAtual as $key => $value) {
                if ($key === "repeat()") {
                    //Caso o valor não seja um inteiro, define como 1.
                    $qtd = (gettype($value['options']['qtd']) == 'integer') ? $value['options']['qtd'] : 1;
                    //Caso o valor seja menor ou igual a 0, define como 1.
                    $qtd = ($qtd <= 0) ? 1 : $qtd;
                    $data = $value['data'];
                    $result = array_merge($result, $this->repeatJsonData($data, $qtd));
                } elseif (is_array($value)) {
                    $result[$key] = $this->replaceRepeat($value);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @param mixed $qtd
     * @return array
     */
    function repeatJsonData($data, $qtd): array
    {
        $result = [];
        for ($i = 1; $i <= $qtd; $i++) {
            $result[] = $this->replaceRepeat($data);
        }
        return $result;
    }

    function replaceOthers(mixed $jsonAtual): mixed
    {
        $index = 1;
        //Verifica se o JSON é um array, para rodar o foreach dentro dele.
        if (gettype($jsonAtual) == 'array') {
            foreach ($jsonAtual as $key => $value) {
                //Verifica se o item atual é um array, para poder chamar de forma recursiva a função.
                if (is_array($value)) {
                    if (isset($value['objectId()'])) {
                        $value = $this->generateObjectId($value['objectId()']);
                    } elseif (isset($value['integer()'])) {
                        $value = $this->generateInteger($value['integer()']);
                    } elseif (isset($value['boolean()'])) {
                        $value = $this->generateBoolean($value['boolean()']);
                    } elseif (isset($value['floating()'])) {
                        $value = $this->generateFloating($value['floating()']);
                    } elseif (isset($value['money()'])) {
                        $value = $this->generateMoney($value['money()']);
                    } elseif (isset($value['custom()'])) {
                        $value = $this->selectCustom($value['custom()']);
                    } elseif (isset($value['gender()'])) {
                        $value = $this->selectGender($value['gender()']);
                    } elseif (isset($value['company()'])) {
                        $value = $this->generateCompany($value['company()']);
                    } elseif (isset($value['phone()'])) {
                        $value = $this->generatePhone($value['phone()']);
                    } elseif (isset($value['stateSelected()'])) {
                        $value = $this->generateState($value['stateSelected()']['options']['country']);
                    } elseif (isset($value['lorem()'])) {
                        $value = $this->generateLorem($value['lorem()']);
                    } elseif (isset($value['latitude()'])) {
                        $value = $this->generateLatitude($value['latitude()']);
                    } elseif (isset($value['longitude()'])) {
                        $value = $this->generateLongitude($value['longitude()']);
                    } elseif (isset($value['date()'])) {
                        $value = $this->generateDate($value['date()']);
                    }
                    //TODO: Adicionar index() e substiuit a chave não o valor. Ex: Ao gerar um lorem dentro de um repeat().
                    /**
                     * "tags": {
                    "repeat()": {
                        "options": {
                            "qtd": 3
                        },
                        "data": {
                            "index()": {
                                "lorem()": {
                                    "options": {
                                        "length": 1,
                                        "type": "paragraphs"
                                    }
                                }
                            }
                        }
                    }
                },
                     */
                    //Caso seja um array chama o foreach de forma recursiva para explorar o valor.
                    $jsonAtual[$key] = $this->replaceOthers($value);
                } else {
                    if ($value === 'guid()' || $key === 'guid()') {
                        $jsonAtual[$key] = $this->generateGuid();
                    } elseif ($value === 'index()' || $key === 'index()') {
                        $jsonAtual[$key] = $index;
                        $index++;
                    } elseif ($value === 'fullName()' || $key === 'fullName()') {
                        $jsonAtual[$key] = $this->generateFullName();
                    } elseif ($value === 'firstName()' || $key === 'firstName()') {
                        $jsonAtual[$key] = $this->generateFirstName();
                    } elseif ($value === 'surName()' || $key === 'surName()') {
                        $jsonAtual[$key] = $this->generateSurName();
                    } elseif ($value === 'email()' || $key === 'email()') {
                        $jsonAtual[$key] = $this->generateEmail();
                    } elseif ($value === 'logradouro()' || $key === 'logradouro()') {
                        $jsonAtual[$key] = $this->generateLogradouro();
                    } elseif ($value === 'street()' || $key === 'street()') {
                        $jsonAtual[$key] = $this->generateStreet();
                    } elseif ($value === 'number()' || $key === 'number()') {
                        $jsonAtual[$key] = $this->generateNumber();
                    } elseif ($value === 'bairro()' || $key === 'bairro()') {
                        $jsonAtual[$key] = $this->generateBairro();
                    } elseif ($value === 'country()' || $key === 'country()') {
                        $jsonAtual[$key] = $this->generateCountry();
                    } elseif ($value === 'state()' || $key === 'state()') {
                        $jsonAtual[$key] = $this->generateState();
                    } elseif ($value === 'address()' || $key === 'address()') {
                        $jsonAtual[$key] = $this->generateAddress();
                    }
                }
            }
        }

        return $jsonAtual;
    }

    /**
     * @param array $value
     * @return int|bool|null
     */
    function generateInteger(array $value): int|bool|null
    {
        return $this->number->integer($value['options']['min'] ?? 0, $value['options']['max'] ?? 0, $value['options']['falsePercentage'] ?? 0, $value['options']['nullPercentage'] ?? 0);
    }

    /**
     * @return string
     */
    function generateGuid(): string
    {
        return $this->hash->guid();
    }

    /**
     * @param array $array
     * @return string
     */
    function generateObjectId(array $array): string
    {
        return $this->hash->objectId($array['options']['length']);
    }

    /**
     * @param array $array
     * @return bool|null
     */
    function generateBoolean(array $array): bool|null
    {
        return $this->number->boolean($array['options']['falsePercentage'] ?? 0, $array['options']['nullPercentage'] ?? 0, $array['options']['deniReturn'] ?? true);
    }

    /**
     * @param array $array
     * @return float|bool|null
     */
    function generateFloating(array $array): float|bool|null
    {
        return $this->number->float($array['options']['falsePercentage'] ?? 0, $array['options']['nullPercentage'] ?? 0, $array['options']['min'] ?? 1, $array['options']['max'] ?? 9, $array['options']['decimals'] ?? 2, $array['options']['round'] ?? false);
    }

    /**
     * @param array $array
     * @return string|bool|null
     */
    function generateMoney(array $array): string|bool|null
    {
        return $this->number->money($array['options']['falsePercentage'] ?? 0, $array['options']['nullPercentage'] ?? 0, $array['options']['min'] ?? 1, $array['options']['max'] ?? 9, $array['options']['decimals'] ?? 2, $array['options']['round'] ?? false, $array['options']['prefix'] ?? 'R$ ', $array['options']['separator'] ?? '.', $array['options']['thousand'] ?? ',');
    }

    /**
     * @param array $array
     * @return string|bool|null
     */
    function generatePhone(array $array): string|bool|null
    {
        return $this->number->phoneNumber(
            $array['options']['falsePercentage'] ?? 0,
            $array['options']['nullPercentage'] ?? 0,
            $array['data']['ddi'] ?? '',
            $array['data']['ddd'] ?? '',
            $array['data']['phoneNumber'] ?? '',
            $array['options']['ddiLength'] ?? 2,
            $array['options']['dddLength'] ?? 2,
            $array['options']['phoneLength'] ?? 9,
            $array['options']['plus'] ?? true,
            $array['options']['spaceAfterPlus'] ?? true,
            $array['options']['parentheses'] ?? true,
            $array['options']['spaceAfterParentheses'] ?? true,
            $array['options']['dash'] ?? true,
            $array['options']['dashBefore'] ?? 4,
            $array['options']['spaceAroundDash'] ?? false
        );
    }

    /**
     * @param array $array
     * @return mixed
     */
    function selectCustom(array $array): mixed
    {
        return $this->custom->custom($array['options']['falsePercentage'] ?? 0, $array['options']['nullPercentage'] ?? 0, $array['data'] ?? [], $array['options']['start'] ?? 0, $array['options']['subtract'] ?? 1);
    }

    /**
     * @param array $array
     * @return mixed
     */
    function selectGender(array $array): mixed
    {
        return $this->custom->gender($array['options']['falsePercentage'] ?? 0, $array['options']['nullPercentage'] ?? 0, $array['data'] ?? [], $array['options']['start'] ?? 0, $array['options']['subtract'] ?? 1);
    }

    function generateFirstName()
    {
        return $this->name->firstName();
    }

    function generateSurName()
    {
        return $this->name->surName();
    }

    function generateFullName()
    {
        return $this->name->fullName();
    }

    function generateCompany($value)
    {
        return $this->name->company($value['options']['type'] ?? false);
    }

    function generateEmailDomain()
    {
        return $this->name->emailDomain();
    }

    function generateEmailName()
    {
        return $this->name->email();
    }

    function generateEmail()
    {
        return $this->name->email();
    }

    function generateLogradouro()
    {
        return $this->address->logradouro();
    }

    function generateStreet()
    {
        return $this->address->street();
    }

    function generateNumber()
    {
        return $this->number->integer(1, 999999);
    }

    function generateBairro()
    {
        return $this->address->bairro();
    }

    function generateCountry()
    {
        return $this->address->country();
    }

    function generateState(int $country = 1)
    {
        return $this->address->state($country);
    }

    function generateAddress()
    {
        return $this->address->address();
    }

    function generateLorem($value)
    {
        return $this->lorem->lorem($value['options']['length'] ?? 1, $value['options']['type'] ?? 'words');
    }

    function generateLatitude($value)
    {
        return $this->number->latitude($value['options']['falsePercentage'] ?? 0, $value['options']['nullPercentage'] ?? 0, $value['options']['min'] ?? -90.000001, $value['options']['max'] ?? 90.0);
    }

    function generateLongitude($value)
    {
        return $this->number->longitude($value['options']['falsePercentage'] ?? 0, $value['options']['nullPercentage'] ?? 0, $value['options']['min'] ?? -180.000001, $value['options']['max'] ?? 180.0);
    }

    function generateDate($value)
    {
        return $this->generateDateBetween($value['options']['min'] ?? '01/01/1970', $value['options']['max'] ?? $this->date->now($this->date->utc()), $value['options']['format'] ?? 'Y-m-d H:i:s');
    }

    function generateDateBetween($min, $max, $format)
    {
        return $this->date->dateBetween($min, $max, $format);
    }
}
