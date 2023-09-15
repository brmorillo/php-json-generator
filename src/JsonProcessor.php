<?php

namespace Rmorillo\JsonGenerator;

use DateTime;
use DateTimeZone;

class JsonProcessor
{
    private array $jsonOriginal;
    private Hash $hash;
    private Util $util;
    private Name $name;
    private Numbers $numbers;
    private Address $address;
    private Lorem $lorem;

    public function __construct(array $jsonOriginal)
    {
        $this->hash = new Hash();
        $this->util = new Util();
        $this->name = new Name();
        $this->numbers = new Numbers();
        $this->address = new Address();
        $this->lorem = new Lorem();
        $this->jsonOriginal = $jsonOriginal;
    }

    public function process()
    {
        if (!empty($this->jsonOriginal)) {
            $return = $this->replaceAll($this->jsonOriginal);
            http_response_code(200);
            echo json_encode($return);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Nenhum dado recebido."]);
        }
    }

    private function replaceAll($jsonOriginal)
    {
        $jsonRepeatReplaced = $this->replaceRepeat($jsonOriginal);
        return $this->replaceOthers($jsonRepeatReplaced[0]);
    }

    function replaceRepeat($jsonAtual)
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

    function repeatJsonData($data, $qtd)
    {
        $result = [];
        for ($i = 1; $i <= $qtd; $i++) {
            $result[] = $this->replaceRepeat($data);
        }
        return $result;
    }

    function replaceOthers($jsonAtual)
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

    function generateInteger(array $value)
    {
        return $this->numbers->getInteger($value['options']['min'] ?? 0, $value['options']['max'] ?? 0, $value['options']['falsePercentage'] ?? 0, $value['options']['nullPercentage'] ?? 0);
    }

    function generateGuid()
    {
        return $this->hash->getGuid();
    }

    function generateObjectId(array $length)
    {
        return $this->hash->getObjectId($length['options']['length']);
    }

    function generateBoolean(array $value)
    {
        return $this->numbers->getBoolean($value['options']['falsePercentage'] ?? 0, $value['options']['nullPercentage'] ?? 0, $value['options']['deniReturn'] ?? true);
    }

    function generateFloating($value)
    {
        return $this->numbers->getFloat($value['options']['falsePercentage'] ?? 0, $value['options']['nullPercentage'] ?? 0, $value['options']['min'] ?? 1, $value['options']['max'] ?? 9, $value['options']['decimals'] ?? 2, $value['options']['round'] ?? false);
    }

    function generateMoney($value)
    {
        //echo $value['options']['nullPercentage'] ?? 0;
        return $this->numbers->getMoney($value['options']['falsePercentage'] ?? 0, $value['options']['nullPercentage'] ?? 0, $value['options']['min'] ?? 1, $value['options']['max'] ?? 9, $value['options']['decimals'] ?? 2, $value['options']['round'] ?? false, $value['options']['prefix'] ?? 'R$ ', $value['options']['separator'] ?? '.', $value['options']['thousand'] ?? ',');
    }

    function generatePhone($value)
    {
        return $this->numbers->getPhoneNumber(
            $value['options']['falsePercentage'] ?? 0,
            $value['options']['nullPercentage'] ?? 0,
            $value['data']['ddi'] ?? '55',
            $value['data']['ddd'] ?? '17',
            $value['data']['phoneNumber'] ?? '987654321',
            $value['options']['ddiLength'] ?? 2,
            $value['options']['dddLength'] ?? 2,
            $value['options']['phoneLength'] ?? 9,
            $value['options']['plus'] ?? true,
            $value['options']['spaceAfterPlus'] ?? true,
            $value['options']['parentheses'] ?? true,
            $value['options']['spaceAfterParentheses'] ?? true,
            $value['options']['dash'] ?? true,
            $value['options']['dashBefore'] ?? 4,
            $value['options']['spaceAroundDash'] ?? false
        );
    }


    function selectCustom($value)
    {
        $rand = rand(1, count($value['data']));
        return (isset($value['data'][$rand]) ? $value['data'][$rand] : '');
    }

    function selectGender($value)
    {
        $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
        $falsePercentage = (gettype($falsePercentage) == 'integer') ? $falsePercentage : 0;

        $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
        $nullPercentage = (gettype($nullPercentage) == 'integer') ? $nullPercentage : 0;

        $falseOrNull = $this->util->falseOrNull($falsePercentage, $nullPercentage);
        if (!$falseOrNull) {
            return $falseOrNull;
        }

        if (!isset($value['data'])) {
            $value['data'] = [
                '1' => 'Male',
                '2' => 'Femeale',
                '3' => 'Others'
            ];
        }
        return $this->selectCustom($value);
    }

    function generateFirstName()
    {
        return $this->name->getFirstName();
    }

    function generateSurName()
    {
        return $this->name->getSurName();
    }

    function generateFullName()
    {
        return $this->name->getFullName();
    }

    function generateCompany($value)
    {
        $type = ($value['options']['type']) ?? false;

        $companyName = [
            "Morillos Eirelli Ltda. ME", "Loja de roupas da Debinha", "Tech Solutions Inc.", "Bela Flor Garden Center", "GreenTech Innovations", "Acme Corporation", "Sunset Electronics", "Gourmet Delights Catering", "OceanView Resorts", "Swift Logistics Group", "Global Marketing Solutions", "Express Auto Repair", "SilverLine Technologies", "HealthWise Pharmacy", "Golden Gate Consulting", "Peak Performance Fitness", "EcoFriendly Builders", "FirstClass Travel Agency", "Smart Data Analytics", "Pristine Cleaning Services", "Alpha Omega Investments", "BlueSky Adventures", "Evergreen Landscaping", "Infinite Horizons Software", "Dynamic Designs Studio", "Crystal Clear Water Solutions", "Urban Elegance Boutique", "Trinity Construction Group", "Sunrise Solar Energy", "Luxury Living Real Estate", "Starlight Entertainment", "Pacific Coast Imports", "Serenity Spa & Wellness", "MountainView Winery", "Quantum Technology Labs", "Timeless Treasures Antiques", "Green Thumb Landscapes", "Seaside Vacation Rentals", "Harmony Health Clinic", "Crimson Creative Agency", "Nature's Harmony Organic Foods", "Empire Builders Group", "Elite Event Planning", "Opulent Jewelry Creations", "Fusion Fitness Studio", "Azure Architecture & Design", "Dreamscape Travel Adventures", "Majestic Marketing Agency", "Royal Realty Group", "Silver Lining Financial Services", "Sunrise Bakery & Cafe", "Visionary Video Productions", "Creative Canvas Art Gallery", "Summit Strategies Consulting", "Tropical Paradise Vacation Rentals", "TechWizards IT Solutions", "Golden Harvest Farm", "Horizon Horizon Realty", "EcoTech Solutions", "Nature's Bounty Health Foods", "Skyline Roofing Contractors", "Rising Sun Construction", "Emerald Isle Resorts", "Wildflower Wellness Center", "Quantum Leap Software", "Everest Adventure Tours", "Crystal Clear Home Inspections", "Elite Elegance Bridal Boutique", "Stratosphere Aerospace Engineering", "Coastal Breeze Real Estate", "Pinnacle Performance Coaching", "Radiant Smiles Dentistry", "Harmony Yoga Studio", "Crimson Rose Florist", "Cityscape Architecture Group", "Golden Oak Financial Advisors", "Sapphire Skies Aviation", "Summit Fitness Center", "Horizon Tech Solutions", "Palm Paradise Resorts", "Emerald City Coffee Roasters", "Sunset Harbor Marina", "Nature's Canvas Art Studio", "Eagle Eye Surveillance", "Blue Wave Marketing", "Solaris Solar Panels", "Mountain Peak Hiking Tours", "Harmony Haven Assisted Living", "Silver Creek Winery", "Sunflower Seed Co-op", "Elite Edge Web Design", "Nova Tech Innovations", "AquaLux Pools", "Cityscape Realty", "Quantum Quilts", "Golden Meadows Retirement Community", "Crimson Leaf Legal Services", "Starfish Swim School", "Horizon Horizon Insurance", "Sky High Drone Services", "Sunrise Sushi Bar", "Terra Nova Landscapes", "EcoLuxe Fashion Boutique", "Pinnacle Properties Management", "Radiant Beauty Salon", "Harborview Estates", "Sapphire Star Jewelry", "Summit Financial Planning", "OceanFront Cafe", "Nature's Touch Massage Therapy", "Silver Stream Productions", "Sunset Ridge Golf Club", "Elite Express Couriers", "NovaStar Software Solutions", "AquaVista Aquariums", "CityScape Consulting Group", "Quantum Mechanics Auto Repair", "Golden Sands Beach Resort", "Crimson Ridge Realty", "Starstruck Entertainment", "Horizon Haven Bed and Breakfast", "Skyline View Landscaping", "Sunrise Ski Rentals", "TerraFirma Earth Sciences", "EcoLiving Home Decor", "Pinnacle Printing Services", "Radiant Health Chiropractic", "Harbor Lights Marina", "Sapphire Waters Spa", "Summit Sports Gear", "OceanView Travel Agency", "Nature's Wisdom Books", "Silver Lining Law Firm", "Sunset Serenity Yoga", "Elite Innovations Labs", "NovaTech Consulting", "AquaBlast Pressure Washing", "CityScape Architects", "Quantum Fitness Equipment", "Golden Meadows Pet Care", "Crimson Leaf Accounting", "Starstruck Photography", "Horizon Heights Apartments", "Skyline View Roofing", "Sunrise Snack Bar", "TerraNova Environmental Solutions", "EcoVenture Outdoor Adventures", "Pinnacle Properties Investments", "Radiant Skincare Clinic", "Harbor Haven Retirement Community", "Sapphire Seas Cruises", "Summit Creative Studios", "OceanFront Vacation Rentals", "Nature's Oasis Herbal Remedies", "Silver Screen Productions", "Sunset Shades Window Tinting", "Elite Edge Marketing", "NovaStar Security Solutions", "AquaGardens Landscaping", "CityScape Real Estate", "Quantum Music Academy", "Golden Sands Surf Shop", "Crimson Leaf Consulting", "Starstruck Event Planning", "Horizon Horizons Travel Agency", "Skyline View Plumbing", "Sunrise Bakery", "TerraNova Adventure Tours", "EcoVista Organic Market", "Pinnacle Properties Rentals", "Radiant Realty", "Harborview Apartments", "Sapphire Dreams Jewelry", "Summit Auto Repair", "OceanView Accounting Services", "Nature's Essence Spa", "Silver Surf Internet Cafe", "Sunset Sails Charter", "Elite Innovations Software", "NovaTech Robotics", "AquaWave Pool Services", "CityScape Law Firm", "Quantum Motorsports", "Golden Meadows Equestrian Center", "Crimson Leaf Marketing", "Starstruck Productions", "Horizon Heights Senior Living", "Skyline View Pest Control", "Sunrise Cafe & Bistro", "TerraNova Landscape Design", "EcoWise Eco-Friendly Products", "Pinnacle Plumbing", "Radiant Dental Care", "Harborview Realty Group", "Sapphire Skies Aviation", "Summit Auto Sales", "OceanView Web Design", "Nature's Best Organic Market", "Silver Stream Video Productions", "Sunset Serenity Spa", "Elite Edge Accounting", "NovaStar Web Development", "AquaLife Aquarium Services", "CityScape Marketing", "Quantum Dynamics Engineering", "Golden Sands Water Sports", "Crimson Leaf Events", "Starstruck Music Academy", "Horizon Heights Property Management", "Skyline View Painting", "Sunrise Sweets Bakery", "TerraNova Construction", "EcoTrend Eco-Friendly Fashion", "Pinnacle Pest Control", "Radiant Salon & Spa", "Harborview Property Rentals", "Sapphire Star Realty", "Summit Accounting Services", "OceanView Travel Tours", "Nature's Beauty Boutique", "Silver Surf Computer Repair", "Sunset Serenity Wellness Center", "Elite Innovations Graphic Design", "NovaTech Mobile Apps", "AquaCare Pool Maintenance", "CityScape Event Planning", "Quantum Quest Adventure Tours", "Golden Meadows Wedding Venue", "Crimson Leaf Financial Services", "Starstruck Photography Studios", "Horizon Heights Apartments", "Skyline View Roofing", "Sunrise Snack Bar", "TerraNova Environmental Solutions", "EcoVenture Outdoor Adventures", "Pinnacle Properties Investments", "Radiant Skincare Clinic", "Harbor Haven Retirement Community", "Sapphire Seas Cruises", "Summit Creative Studios", "OceanFront Vacation Rentals", "Nature's Oasis Herbal Remedies", "Silver Screen Productions", "Sunset Shades Window Tinting", "Elite Edge Marketing", "NovaStar Security Solutions", "AquaGardens Landscaping", "CityScape Real Estate", "Quantum Music Academy", "Golden Sands Surf Shop", "Crimson Leaf Consulting", "Starstruck Event Planning", "Horizon Horizons Travel Agency", "Skyline View Plumbing", "Sunrise Bakery", "TerraNova Adventure Tours", "EcoVista Organic Market", "Pinnacle Properties Rentals", "Radiant Realty", "Harborview Apartments", "Sapphire Dreams Jewelry", "Summit Auto Repair", "OceanView Accounting Services", "Nature's Essence Spa", "Silver Surf Internet Cafe", "Sunset Sails Charter", "Elite Innovations Software", "NovaTech Robotics", "AquaWave Pool Services", "CityScape Law Firm", "Quantum Motorsports", "Golden Meadows Equestrian Center", "Crimson Leaf Marketing", "Starstruck Productions", "Horizon Heights Senior Living", "Skyline View Pest Control", "Sunrise Cafe & Bistro", "TerraNova Landscape Design", "EcoWise Eco-Friendly Products", "Pinnacle Plumbing", "Radiant Dental Care", "Harborview Realty Group", "Sapphire Skies Aviation", "Summit Auto Sales", "OceanView Web Design", "Nature's Best Organic Market", "Silver Stream Video Productions", "Sunset Serenity Spa", "Elite Edge Accounting", "NovaStar Web Development", "AquaLife Aquarium Services", "CityScape Marketing", "Quantum Dynamics Engineering", "Golden Sands Water Sports", "Crimson Leaf Events", "Starstruck Music Academy", "Horizon Heights Property Management", "Skyline View Painting", "Sunrise Sweets Bakery", "TerraNova Construction", "EcoTrend Eco-Friendly Fashion", "Pinnacle Pest Control", "Radiant Salon & Spa", "Harborview Property Rentals", "Sapphire Star Realty", "Summit Accounting Services", "OceanView Travel Tours", "Nature's Beauty Boutique", "Silver Surf Computer Repair", "Sunset Serenity Wellness Center", "Elite Innovations Graphic Design", "NovaTech Mobile Apps", "AquaCare Pool Maintenance", "CityScape Event Planning", "Quantum Quest Adventure Tours", "Golden Meadows Wedding Venue", "Crimson Leaf Financial Services", "Starstruck Photography Studios"
        ];
        $companySelected = $companyName[rand(0, count($companyName) - 1)];
        if ($type == 'toUpperCase') {
            $companySelected = strtoupper($companySelected);
        } elseif ($type == 'toLowerCase') {
            $companySelected = strtolower($companySelected);
        } elseif ($type == 'capitalize') {
            $companySelected = ucwords($companySelected);
        } elseif ($type == 'camelCase') {
            $companySelected = lcfirst(str_replace(" ", "", ucwords(str_replace(".", " ", $companySelected))));
        } elseif ($type == 'slugify') {
            //TODO: Slugify pode ter mais opções. https://www.npmjs.com/package/slugify
            $companySelected = strtolower(str_replace(" ", "-", $companySelected));
        }
        return $companySelected;
    }

    function generateEmailDomain()
    {
        return $this->name->getEmailDomain();
    }

    function generateEmailName()
    {
        return $this->name->getEmail();
    }

    function generateEmail()
    {
        return $this->name->getEmail();
    }

    function generateLogradouro()
    {
        return $this->address->getLogradouro();
    }

    function generateStreet()
    {
        return $this->address->getStreet();
    }

    function generateNumber()
    {
        return $this->numbers->getInteger(1, 999999);
    }

    function generateBairro()
    {
        return $this->address->getBairro();
    }

    function generateCountry()
    {
        return $this->address->getCountry();
    }

    function generateState(int $country = 1)
    {
        return $this->address->getState($country);
    }

    function generateAddress()
    {
        return $this->address->getAddress();
    }

    function generateLorem($value)
    {
        //TODO: Adicionar alterações de texto igual de company() a todas funções que retornam texto.
        $length = ($value['options']['length']) ? $value['options']['length'] : 1;
        $length = (gettype($length) != 'integer' || $length < 1) ? 1 : $length;

        $type = ($value['options']['type']) ? $value['options']['type'] : 1;
        $type = ($value['options']['type']) ? $value['options']['type'] : 1;

        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque id fermentum ligula. Proin feugiat, nulla eget fermentum scelerisque, quam dui auctor sapien, quis luctus mi metus id sapien. Aenean consectetur libero a bibendum aliquet. Vivamus in libero a lorem facilisis tincidunt. Donec mattis eu libero sit amet blandit. Praesent varius rhoncus urna, eu iaculis nulla fermentum nec. Fusce tincidunt scelerisque ante id fermentum. Etiam finibus nec ipsum quis mattis. Sed interdum justo non augue cursus, non tincidunt libero facilisis. Sed eget semper sapien. Nulla facilisi. Sed vehicula facilisis malesuada. Phasellus dictum tincidunt dui, eu convallis ligula venenatis nec. Nullam ac nulla hendrerit, fermentum erat nec, egestas augue. Nulla facilisi. Vivamus iaculis non nunc in mattis. Sed luctus, ante quis fermentum viverra, erat erat tincidunt ex, quis fermentum nulla ligula ut mi. Integer ac nunc elit. Etiam fringilla quis nulla nec tempor. Curabitur bibendum, orci et sollicitudin tincidunt, nulla ligula elementum odio, id sodales purus libero id turpis. Sed sed metus vitae elit iaculis vehicula ut ac eros. Vestibulum vestibulum bibendum est, ut mattis sem viverra id. Vivamus porttitor blandit odio. Nullam nec felis non eros bibendum pharetra. Donec dictum, lectus in bibendum auctor, sapien erat dignissim ipsum, a sodales justo orci non nulla. Nullam feugiat elit sit amet nunc ullamcorper facilisis. Cras vel lacus odio. Nam gravida felis a odio sollicitudin tempus. In hac habitasse platea dictumst. Pellentesque facilisis odio ac sapien sodales feugiat. Pellentesque vestibulum turpis quis urna aliquam, vel vehicula elit pulvinar. Praesent bibendum, quam id dapibus feugiat, nisl urna mattis libero, eu tempus urna neque at sem. Morbi ullamcorper varius libero id vestibulum. Nunc suscipit mattis lorem, at suscipit lectus condimentum et. Fusce sit amet turpis ex. Ut at laoreet quam. Nullam ut purus massa. Maecenas quis nulla vel nulla fringilla sagittis eu a erat. Donec vitae diam luctus, gravida quam a, iaculis nulla. Aliquam erat volutpat. Integer quis nulla eget sem pharetra tincidunt. Fusce vel auctor odio, a tristique dolor. Etiam ultrices at dolor ut volutpat. Suspendisse euismod, enim in vestibulum pharetra, mi ante aliquet est, at scelerisque nisl velit quis odio. Nullam viverra tincidunt ex, vel vulputate turpis tincidunt nec. Nullam lacinia mi non purus tristique, id sagittis nulla sollicitudin. Quisque quis malesuada elit, nec facilisis purus. Nunc quis turpis eget enim lacinia bibendum. Aliquam eu sollicitudin metus, eget semper nulla. Praesent lacinia, tellus quis posuere euismod, elit sem porttitor lectus, ut viverra libero mi ut lorem. Nulla facilisi. Donec dignissim libero nec justo rhoncus, sit amet vestibulum nulla condimentum. Duis a velit mi. Nam a leo sem. Nullam ac nisi sed arcu fringilla facilisis nec a enim. Vivamus ullamcorper bibendum nunc. Integer semper, eros ut sollicitudin blandit, lorem ipsum varius turpis, a tristique nunc ante at arcu. Nam vehicula, enim a tincidunt egestas, nulla lectus aliquam sapien, sit amet dictum mauris turpis eu erat. Vestibulum eget iaculis urna, a vulputate odio. Proin dapibus nisl quis volutpat semper. Quisque malesuada eros a libero fringilla, id malesuada arcu blandit. Vivamus fermentum erat sit amet ligula aliquet, quis volutpat velit eleifend. Ut sodales massa ac urna tincidunt, quis tristique lorem varius. Sed blandit, neque id sodales dictum, odio mauris rhoncus dolor, nec rhoncus neque erat ut leo. Aliquam erat volutpat. Etiam a urna velit. Integer vestibulum ullamcorper nunc, non blandit quam condimentum ac. Suspendisse potenti. Vivamus consectetur a eros a vehicula. Aenean et libero ac enim tempus posuere id sit amet lectus. Fusce sollicitudin ipsum nec justo facilisis, vel interdum turpis cursus. Morbi sagittis libero ac elit efficitur pellentesque. Praesent eget vehicula turpis. Donec aliquet, mi non fermentum ultrices, metus metus accumsan leo, nec vulputate est turpis eu diam. Aenean vel lorem et erat vestibulum vulputate. Vestibulum eleifend hendrerit purus a cursus. Proin id auctor eros. Integer eget velit nec libero vestibulum venenatis. Cras id augue nec libero convallis venenatis. Integer a justo elit.';

        if ($type === 'words') {
            $lorem = $this->generateLoremWords($lorem, $length);
        } elseif ($type === 'sentenses') {
            $lorem = $this->generateLoremSentense($lorem, $length);
        } elseif ($type === 'paragraphs') {
            $lorem = $this->generateLoremParagraph($lorem, $length);
        }
        return trim($lorem);
    }

    function generateLoremWords($lorem, $length = 1)
    {
        $numbers = new Numbers;
        $palavras = explode(' ', $lorem);
        $primeirasPalavras = array_slice($palavras, $numbers->getInteger(1, count($palavras)), $length);
        return ucfirst(strtolower(str_replace(',', '', str_replace('.', '', implode(' ', $primeirasPalavras)))));
    }

    function generateLoremSentense($lorem, $length = 1)
    {
        $numbers = new Numbers;
        $palavras = explode('.', $lorem);
        $primeirasPalavras = array_slice($palavras, $numbers->getInteger(1, count($palavras)), $length);
        return implode('.', $primeirasPalavras) . '.';
    }

    function generateLoremParagraph($lorem, $length = 1)
    {
        $numbers = new Numbers;
        $primeirosParagrafos = [];
        $paragrafos = explode('.', $lorem);
        for ($i = 1; $i <= $length; $i++) {
            for ($j = 1; $j <= 4; $j++) {
                $primeirosParagrafos = array_merge(array_slice($paragrafos, $numbers->getInteger(1, count($paragrafos)), $length), $primeirosParagrafos);
            }
        }
        return implode('.', $primeirosParagrafos);
    }

    function generateLatitude($value)
    {
        //TODO: BUG: Apenas gerando numeros positivos.
        $minLatitude = -90.000001;
        $maxLatitude = 90.0;
        $min = ($value['options']['min']) ? $value['options']['min'] : $minLatitude;
        $min = (gettype($min) != 'float' || $min < $minLatitude) ? $minLatitude : $value['options']['min'];

        $max = ($value['options']['max']) ? $value['options']['max'] : $maxLatitude;
        $max = (gettype($max) != 'float' || $max > $maxLatitude) ? $maxLatitude : $max;
        $min = ($min > $max) ? $max : $min;

        //return $this->generateFloating(['options' => ['min' => $min, 'max' => $max]]);
    }

    function generateLongitude($value)
    {
        //TODO: BUG: Apenas gerando numeros positivos.
        $minLongitude = -180.000001;
        $maxLongitude = 180.0;
        $min = ($value['options']['min']) ? $value['options']['min'] : $minLongitude;
        $min = (gettype($min) != 'float' || $min < $minLongitude) ? -90.000001 : $value['options']['min'];

        $max = ($value['options']['max']) ? $value['options']['max'] : 90;
        $max = (gettype($max) != 'float' || $max > $maxLongitude) ? 90 : $max;
        $min = ($min > $max) ? $max : $min;

        //return $this->generateFloating(['options' => ['min' => $min, 'max' => $max]]);
    }

    function generateDate($value)
    {
        $utc = new DateTimeZone('UTC');
        $nowDateTime = new DateTime('now', $utc);

        $min = ($value['options']['min']) ? $value['options']['min'] : '01/01/1970';
        $max = ($value['options']['max']) ? $value['options']['max'] : $nowDateTime;
        $format = ($value['options']['format']) ? $value['options']['format'] : 'Y-m-d H:i:s';

        return $this->generateDateBetween($min, $max, $format);
    }

    function generateDateBetween($min, $max, $format)
    {
        $min = strtotime($min);
        $max = strtotime($max);

        $val = rand($min, $max);
        return date($format, $val);
    }
}
