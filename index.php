<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$jsonOriginal = json_decode(file_get_contents("php://input"), true);

if (!empty($jsonOriginal)) {
    $return = replaceAll($jsonOriginal);
    http_response_code(200);
    echo json_encode($return);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Nenhum dado recebido."]);
}

function replaceAll($jsonOriginal)
{
    $jsonRepeatReplaced = replaceRepeat($jsonOriginal);
    return replaceOthers($jsonRepeatReplaced[0]);
}

function replaceRepeat($jsonAtual)
{
    $result = [];

    if (gettype($jsonAtual) == 'array') {
        foreach ($jsonAtual as $key => $value) {
            if ($key === "repeat()") {
                $qtd = $value['options']['qtd'];
                $data = $value['data'];
                $result = array_merge($result, repeatJsonData($data, $qtd));
            } elseif (is_array($value)) {
                $result[$key] = replaceRepeat($value);
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
        $result[] = replaceRepeat($data);
    }
    return $result;
}


function falseOrNull($falsePercentage, $nullPercentage)
{
    $value = true;
    if ($falsePercentage != 0 || $nullPercentage != 0) {
        if ($falsePercentage) {
            if (rand(1, 100) <= $falsePercentage) {
                $value = false;
            }
        }
        if ($nullPercentage) {
            if (rand(1, 100) <= $nullPercentage) {
                $value = null;
            }
        }
    }
    return $value;
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
                    $value = generateObjectId($value['objectId()']);
                } elseif (isset($value['integer()'])) {
                    $value = generateInteger($value['integer()']);
                } elseif (isset($value['boolean()'])) {
                    $value = generateBoolean($value['boolean()']);
                } elseif (isset($value['floating()'])) {
                    $value = generateFloating($value['floating()']);
                } elseif (isset($value['money()'])) {
                    $value = generateMoney($value['money()']);
                } elseif (isset($value['custom()'])) {
                    $value = selectCustom($value['custom()']);
                } elseif (isset($value['gender()'])) {
                    $value = selectGender($value['gender()']);
                } elseif (isset($value['company()'])) {
                    $value = generateCompany($value['company()']);
                } elseif (isset($value['phone()'])) {
                    $value = generatePhone($value['phone()']);
                } elseif (isset($value['stateSelected()'])) {
                    $value = generateState($value['stateSelected()']['options']['country']);
                } elseif (isset($value['lorem()'])) {
                    $value = generateLorem($value['lorem()']);
                } elseif (isset($value['latitude()'])) {
                    $value = generateLatitude($value['latitude()']);
                } elseif (isset($value['longitude()'])) {
                    $value = generateLongitude($value['longitude()']);
                } elseif (isset($value['date()'])) {
                    $value = generateDate($value['date()']);
                }
                //Caso seja um array chama o foreach de forma recursiva para explorar o valor.
                $jsonAtual[$key] = replaceOthers($value);
            } else {
                if ($value === 'guid()' || $key === 'guid()') {
                    $jsonAtual[$key] = generateGuid();
                } elseif ($value === 'index()' || $key === 'index()') {
                    $jsonAtual[$key] = $index;
                    $index++;
                } elseif ($value === 'fullName()' || $key === 'fullName()') {
                    $jsonAtual[$key] = generateFullName();
                } elseif ($value === 'firstName()' || $key === 'firstName()') {
                    $jsonAtual[$key] = generateFirstName();
                } elseif ($value === 'surName()' || $key === 'surName()') {
                    $jsonAtual[$key] = generateSurName();
                } elseif ($value === 'email()' || $key === 'email()') {
                    $jsonAtual[$key] = generateEmail();
                } elseif ($value === 'logradouro()' || $key === 'logradouro()') {
                    $jsonAtual[$key] = generateLogradouro();
                } elseif ($value === 'street()' || $key === 'street()') {
                    $jsonAtual[$key] = generateStreet();
                } elseif ($value === 'number()' || $key === 'number()') {
                    $jsonAtual[$key] = generateNumber();
                } elseif ($value === 'bairro()' || $key === 'bairro()') {
                    $jsonAtual[$key] = generateBairro();
                } elseif ($value === 'country()' || $key === 'country()') {
                    $jsonAtual[$key] = generateCountry();
                } elseif ($value === 'state()' || $key === 'state()') {
                    $jsonAtual[$key] = generateState();
                } elseif ($value === 'address()' || $key === 'address()') {
                    $jsonAtual[$key] = generateAddress();
                }
            }
        }
    }

    return $jsonAtual;
}

function generateInteger($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $min = ($value['options']['min']) ?? 1;
    $max = ($value['options']['max']) ?? 9;

    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }

    if ($min > $max) {
        $min = $max;
    }
    return rand($min, $max);
}

function generateRandomHash($length = 1)
{
    //return md5(uniqid(rand(), true));
    return bin2hex(random_bytes($length));
}

function generateGuid()
{
    if (function_exists('com_create_guid')) {
        return trim(com_create_guid(), '{}');
    } else {
        return sprintf(
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

function generateObjectId($value)
{
    //TODO: Adicionar validações como esta dentro de todos os tipos de arrays para garantir recursividade até mesmo dentro das funções. Vale a pena para todos?
    if (is_array($value['options']['length'])) {
        //Caso a qtd seja um array (Ou seja, outra função gerando ela), chama de forma recursiva a função para gerar o valor.
        $value['options']['length'] = array_values(replaceOthers($value['options']))[0];
    }

    $value['options']['length'] = ($value['options']['length']) ?? 1;

    //Caso o valor seja 0 não é possível gerar um hash, então ele é definido como 1.
    if ($value['options']['length'] == 0)
        $value['options']['length'] = 1;

    return generateRandomHash($value['options']['length']);
}

function generateBoolean($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }

    return rand(0, 1) === 1;
}

function generateFloating($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }

    $min = ($value['options']['min']) ?? 1;
    $max = ($value['options']['max']) ?? 9;
    $decimals = ($value['options']['decimals']) ?? 2;
    if ($decimals > 15) {
        $decimals = 15;
    }
    $round = ($value['options']['round']) ?? false;

    $scale = 10 ** $decimals;
    $randomFloat = $min + (rand() / getrandmax()) * ($max - $min);
    $value = round($randomFloat * $scale) / $scale;
    if ($round) {
        $value = round($value);
    }
    return $value;
}

function generateMoney($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }

    $min = ($value['options']['min']) ?? 1;
    $max = ($value['options']['max']) ?? 9;
    $decimals = ($value['options']['decimals']) ?? 2;
    $prefix = ($value['options']['prefix']) ?? '';
    $separator = ($value['options']['separator']) ?? '';
    $thousand = ($value['options']['thousand']) ?? '';

    $scale = 10 ** $decimals;
    $randomFloat = $min + (rand() / getrandmax()) * ($max - $min);
    $randomFloat = round($randomFloat * $scale) / $scale;

    $formattedFloat = number_format($randomFloat, $decimals, $separator, $thousand);

    return $prefix . $formattedFloat;
}

function generatePhone($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
    if (!$falseOrNull) {
        return $falseOrNull;
    }

    $ddi = ($value['data']['ddi']) ?? false;
    $ddd = ($value['data']['ddd']) ?? false;
    $phoneNumber = ($value['data']['phoneNumber']) ?? false;

    $ddiLength = ($value['options']['ddiLength']) ?? 2;
    //Define o máximo do DDI.
    $ddiLength = ($ddiLength > 10) ? 9 : $ddiLength;
    //Caso o DDI esteja abaixo do mínimo, define como 2.
    $ddiLength = ($ddiLength != 0) ? $ddiLength : 2;

    $dddLength = ($value['options']['dddLength']) ?? 2;
    //Define o máximo do DDD.
    $dddLength = ($dddLength > 10) ? 9 : $dddLength;
    //Caso o DDD esteja abaixo do mínimo, define como 2.
    $dddLength = ($dddLength != 0) ? $dddLength : 2;

    $plus = ($value['options']['plus']) ?? false;
    $spaceAfterPlus = ($value['options']['spaceAfterPlus']) ?? false;

    $parentheses = ($value['options']['parentheses']) ?? false;
    $spaceAfterParentheses = ($value['options']['spaceAfterParentheses']) ?? false;

    $dash = ($value['options']['dash']) ?? false;
    $dashBefore = ($value['options']['dashBefore']) ?? 4;
    $dash = ($dashBefore == 0) ? false : $dash;
    $spaceAroundDash = ($value['options']['spaceAroundDash']) ? ' - ' : '-';

    $phoneLength = ($value['options']['phoneLength']) ?? 9;
    //Define o máximo do número de telefone.
    $phoneLength = ($phoneLength > 15) ? 15 : $phoneLength;
    //Caso o número de telefone esteja abaixo do mínimo, define como 9.
    $phoneLength = ($phoneLength < 1) ? 9 : $phoneLength;

    if (!$phoneNumber) {
        for ($i = 1; $i <= $phoneLength; $i++) {
            $phoneNumber .= generateInteger([
                'options' => [
                    'min' => 0,
                    'max' => 9
                ]
            ]);
        }
        if ($i == 1 && $phoneNumber == 0) {
            $phoneNumber = 9;
        }

        $strLenPhoneNumber = strlen($phoneNumber);
        //Apenas adiciona o dash caso o número seja maior que o número de caracteres para se colocar o dash.
        if ($dash && $strLenPhoneNumber > $dashBefore) {
            $position = $strLenPhoneNumber - $dashBefore;
            $phoneNumber = substr($phoneNumber, 0, $position) . $spaceAroundDash . substr($phoneNumber, $position);
            //$phoneNumber = substr_replace($phoneNumber, $usarSpaceAroundDash, $position, 0);
        }
    }
    if (!$ddd) {
        for ($i = 1; $i <= $dddLength; $i++) {
            $ddd .= generateInteger([
                'options' => [
                    'min' => 0,
                    'max' => 9
                ]
            ]);
        }
        if ($parentheses) {
            $ddd = '(' . $ddd . ')';
            if ($spaceAfterParentheses) {
                $ddd .= ' ';
            }
        }
        $phoneNumber = $ddd . $phoneNumber;
    }
    if (!$ddi) {
        for ($i = 1; $i <= $ddiLength; $i++) {
            $ddi .= generateInteger([
                'options' => [
                    'min' => 0,
                    'max' => 9
                ]
            ]);
        }
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


function selectCustom($value)
{
    return $value['data'][rand(1, count($value['data']))];
}

function selectGender($value)
{
    $falsePercentage = ($value['options']['falsePercentage']) ?? 0;
    $nullPercentage = ($value['options']['nullPercentage']) ?? 0;
    $falseOrNull = falseOrNull($falsePercentage, $nullPercentage);
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
    return selectCustom($value);
}

function generateFirstName()
{
    $firstName = [
        "Maria", "João", "Ana", "José", "Lucas", "Emily", "Sophia", "William", "Isabella", "Pedro", "Gabriel", "Carlos", "Daniel", "Camila", "Fernanda", "Beatriz", "Clara", "Paulo", "Luiza", "Arthur", "Mateus", "Amanda", "Marcelo", "Eduardo", "Alice", "David", "Mariana", "Ricardo", "Felipe", "Raquel", "Júlia", "Victor", "Catarina", "Isabel", "Luis", "Diego", "Bruno", "Alex", "Vitoria", "Francisco", "Rafael", "Samantha", "Sofia", "Henrique", "Adriana", "Roberto", "Miguel", "Elena", "Marcos", "Juan", "Leonardo", "Thomas", "Samuel", "Carolina", "Cristina", "Igor", "Marina", "Lara", "Patricia", "Sara", "Natalia", "Fábio", "Juliana", "Matthew", "Sarah", "Rodrigo", "Aline", "Flávia", "Letícia", "Adriano", "Grace", "Augusto", "Regina", "Diana", "Isaac", "Gustavo", "Viviane", "Tiago", "Fernando", "Michelle", "Tatiana", "Otávio", "Nina", "Simone", "Jorge", "Giovanna", "Laura", "Ariana", "Luciana", "Elaine", "Maya", "Leila", "Filipe", "Ester", "Benjamin", "Inês", "Marco", "Yasmin", "Adam", "Ian", "Kelly", "Julia", "Rosa", "Alexandra", "Leandro", "Chris", "Nelson", "Vinícius", "Daniele", "Nathalia", "Erick", "Lucia", "Silvia", "Roger", "Jake", "Sérgio", "Paola", "Gisele", "Antonio", "Rita", "Cecilia", "Xavier", "Marta", "Caio", "Hugo", "Bianca", "Fiona", "Jonathan", "Jason", "Mauro", "Olivia", "Emma", "Valentina", "Leo", "Larissa", "Andrea", "Sophie", "Kyle", "Renata", "Esther", "Alana", "Bernardo", "Elisa", "Paula", "Taylor", "Peter", "Tony", "Ivan", "Angela", "Zoe", "George", "Joana", "Rafaela", "Irene", "Edgar", "Nancy", "Vera", "Martin", "Aaron", "Oscar", "Violet", "Celine", "Nick", "Ryan", "Lia", "Cassandra", "Madison", "Jesse", "Vivian", "Teresa", "Ellen", "Bryan", "Melissa", "Ivy", "Blake", "Lily", "Albert", "Noah", "Isa", "Jade", "Carmen", "Keith", "Nora", "Louise", "Sean", "Dylan", "Skyler", "Gloria", "Luke", "Eva", "Joy", "Jordan", "Andre", "Cristiano", "Faye", "Cole", "Andres", "Charlotte", "Zara", "Denise", "Beverly", "Mila", "Sam", "Jean", "Kylie", "Justin", "Wesley", "Claudia", "Bruce", "Alberto", "Gwen", "Owen", "Hannah", "Eric", "Henry", "Lorraine", "Philip", "Molly", "Saul", "Jackie", "Elliot", "Michele", "Max", "Wanda", "Mauricio", "Gael", "Isadora", "Lena", "Eleanor", "Graham", "Kirk", "Allan", "Valeria", "Mabel", "Abigail", "Neil", "Omar", "Flora", "Deborah", "Eugene", "Leticia", "Lana", "Hazel", "Riley", "Oliver", "Cody", "Ashley", "Alan", "Javier", "Silas", "Vivienne", "Isla", "Walter", "Scott", "Gene", "Dennis", "Evelyn", "Daisy", "Rex", "Ramona", "Tiffany", "Nathaniel", "Roy", "Olive", "Marie", "Pearl", "Ray", "Carl", "Pamela", "Penelope", "Mandy", "Stephanie", "Eliza", "Rosie", "Kayla", "Eve", "Edith", "Vanessa", "Juliet", "Mae", "Andy", "Renee", "June", "Agnes", "Harold", "Lola", "Stanley", "Caleb", "Rosemary", "Lester", "Priscilla", "Armando", "Clifford", "Rebecca", "Vicki", "Winnie", "Myra", "Lydia", "Kathryn", "Floyd", "Veronica", "Jill", "Monica", "Tina", "Ben", "Jon", "Isaiah", "Todd", "Marion", "Cynthia", "Kent", "Lyle", "Sheila", "Kathy", "Shirley", "Sylvia", "Sandy", "Cheryl", "Sonia", "Mercedes", "Dorothy", "Joel", "Ismael", "Claire", "Colin", "Helen", "Nigel", "Curtis", "Darren", "Morgan", "Norman", "Ruby", "Cara", "Troy", "Jasmine", "Travis", "Clyde", "Penny", "Vicky", "Randy", "Eunice", "Lillian", "Trent", "Dale", "Perry", "Daryl", "Ellis", "Leigh", "Enrique", "Manny", "Terri", "Brad", "Jeanne", "Lauren", "Shane", "Colleen", "Terry", "Lindsey", "Robin", "Neal", "Cecil", "Anita", "Beth", "Brett", "Garry", "Conrad", "Greg", "Lyndon", "Dwight", "Iris", "Bernadette", "Janet", "Grant", "Quincy", "Roland", "Brent", "Stuart", "Audrey", "Raul", "Candace", "Mack", "Debbie", "Maureen", "Freddie", "Cora", "Edwin", "Russell", "Quinn", "Spencer", "Rhonda", "Cedric", "Vernon", "Arturo", "Lou", "Olga", "Darnell", "Viola", "Naomi", "Wilma", "Benny", "Bridget", "Nadine", "Elmer", "Lonnie", "Angelo", "Becky", "Geneva", "Kerry", "Tabitha", "Alfredo", "Billie", "Loretta", "Miriam", "Laurie", "Janice", "Constance", "Wes", "Belinda", "Duane", "Gerard", "Trina", "Delia", "Suzette", "Milton", "Melody", "Patsy", "Reginald", "Sylvester", "Elias", "Wendy", "Harvey", "Ollie", "Harrison", "Rose", "Marshall", "Clark", "Rosalind", "Charlene", "Amos", "Tommy", "Elton", "Gina", "Sherri", "Roderick", "Misty", "Nell", "Warren", "Muriel", "Lynn", "Ginger", "Donna", "Carla", "Eloise", "Dixie", "Phyllis", "Lynne", "Roscoe", "Kim", "Polly", "Ned", "Dora", "Rod", "Rachael", "Carole", "Maxine", "Franklin", "Guy", "Cleo", "Cornelius", "Dina", "Bert", "Dewayne", "Mona", "Melba", "Seth", "Edna", "Sybil", "Dolores", "Doris", "Harriet", "Glen", "Rosetta", "Hattie", "Bertha", "Leona", "Hope", "Ira", "Willis", "Minnie", "Eula", "Wade", "Meredith", "Pearlie", "Elvira", "Jeannie", "Alton", "Delbert", "Clarence", "Luther", "Nellie", "Effie", "Wallace", "Isiah", "Genevieve", "Adele", "Beulah", "Blanche", "Iva", "Gertie", "Gwendolyn", "Homer", "Myrtle", "Elliott", "Percy", "Rufus", "Chester", "Cecelia", "Elnora", "Fannie", "Lucille", "Mattie", "Flossie", "Lila", "Maggie", "Adelaide", "Mable", "Stella", "Hollie", "Lyman", "Ida", "Fern", "Susie", "Gladys", "Alma", "Opal", "Sally", "Ruben", "Pete", "Rosalie", "Tillie", "Hester", "Addie", "Lottie", "Lela", "Johnnie", "Maude", "Agatha", "Geraldine", "Lulu", "Neva", "Frieda", "Aurelia", "Goldie", "Lenora", "Nelle", "Celia", "Maud", "Verna", "Inez", "Elma", "Luella", "Della", "Thelma", "Henrietta", "Ora", "Cornelia", "Willa", "Ethel", "Adeline", "Lina", "Zella", "Ina", "Lelia", "Josie", "Harriett", "Essie", "Sue", "Sadie", "Johanna", "Mina", "Kitty", "Birdie", "Lillie", "Lizzie", "Mollie", "Elva", "Avis", "Louisa", "Eugenia", "Maudie", "Hanna", "Florine", "Dolly", "Pauline", "Louella", "Adela", "Gussie", "Nettie", "Freda", "Lucile", "Alta", "Marian", "Janie", "Marguerite", "Zora", "Leora", "Jennie", "Fanny", "Edythe", "Etta", "Sallie", "Myrtie", "Mamie", "Theresa", "Ada", "Zula", "Winifred", "Madge", "Ola", "Lucy", "Susan", "Barbara", "Amelia", "Nola", "Lorena", "Ophelia", "Kathleen", "Lettie", "Roxie", "Estelle", "Millie", "Katharine", "Ella", "Elsie", "Annie", "Hilda", "Josephine", "Bessie", "Isabelle", "Anastasia", "Dulce", "Hernan", "Agustin", "Julio", "Cesar", "Ignacio", "Vicente", "Emilio", "Alejandro", "Manuel", "Guillermo", "Jose", "Sergio", "Lorenzo", "Sebastian", "Gerardo", "Esteban", "Adolfo", "Julian", "Benito", "Ramiro", "Gonzalo", "Joaquin", "Mario", "Alvaro", "Felix", "Horacio", "Juanita", "Ines", "Alba", "Susana", "Alicia", "Lourdes", "Juana", "Rocio", "Martha", "Norma", "Luz", "Magdalena", "Esperanza", "Blanca", "Rebeca", "Fabiola", "Matilde", "Yolanda", "Graciela", "Frida", "Antonia", "Amalia", "Isidora", "Soledad", "Concepcion", "Rosalinda", "Angeles", "Elsa", "Martina", "Violeta", "Rosario", "Victoria", "Gabriela", "Agustina", "Estela", "Margarita", "Carlota", "Alejandra", "Sol", "Liliana", "Inmaculada", "Felisa", "Maribel", "Luisa", "Anahi", "Araceli", "Azucena", "Georgina", "Hortensia", "Julieta", "Rosalba", "Delfina", "Celeste", "Macarena", "Bernarda", "Paloma", "Guadalupe", "Ruth", "Beatrice", "Mildred", "Virginia", "Marjorie", "Margaret", "Florence", "Caroline", "Catherine", "Lula", "Gertrude", "Katherine", "Lucinda", "Jessie", "Jane", "Estella", "Bess", "Georgia", "Betty", "Frances", "Katie", "Alberta", "Mary", "Matilda", "Madeline", "Phoebe", "Bonnie", "May", "Anne"
    ];
    return $firstName[rand(0, count($firstName) - 1)];
}

function generateSurName()
{
    $surName = [
        "Silva", "Smith", "Johnson", "Garcia", "Rodriguez", "Martinez", "Williams", "Brown", "Jones", "Pereira", "Hernandez", "Lee", "Gonzalez", "Perez", "Lopez", "Murphy", "Anderson", "Costa", "Kim", "Davis", "Wilson", "Taylor", "Thomas", "Moore", "Santos", "Clark", "Thompson", "Lima", "Sanchez", "Harris", "Nelson", "Evans", "Adams", "Scott", "Cook", "Bailey", "Fernandes", "Hall", "Campbell", "Mitchell", "Roberts", "Young", "Gomes", "Wright", "Martins", "Hill", "Green", "King", "Carter", "Fisher", "Ribeiro", "Turner", "Phillips", "Allen", "Torres", "Parker", "Collins", "Ramirez", "Almeida", "Freitas", "Morris", "Hughes", "Reed", "Flores", "Edwards", "Kelly", "Howard", "Olson", "Cooper", "Ferreira", "Jenkins", "Ross", "Simmons", "Diaz", "Powell", "Graham", "Rogers", "Ward", "James", "Foster", "Barnes", "Bell", "Murray", "Moreira", "Rivera", "Morgan", "Stevens", "Meyer", "Wallace", "Mello", "Ramos", "Woods", "Long", "Ford", "Chen", "Price", "Watson", "Butler", "Jensen", "Bennett", "Reyes", "Wells", "Castro", "Coelho", "Perry", "Peterson", "West", "Hunt", "Stewart", "Fields", "Hoffman", "Gibson", "Gray", "Marques", "Ruiz", "Vasquez", "Daniels", "Harper", "Arnold", "Schmidt", "Boyd", "Warren", "Medeiros", "Fox", "Jordan", "Hayes", "Harvey", "Beck", "Cole", "Black", "Hunter", "Webb", "Guerra", "Morrison", "Ryan", "Carvalho", "Baker", "Vargas", "Oliveira", "Cruz", "Dunn", "Gutierrez", "Mills", "Nguyen", "Matthews", "Alexander", "Spencer", "Sullivan", "Shaw", "Lambert", "Weaver", "Reid", "Bishop", "Fowler", "Nogueira", "Knight", "Gilbert", "Rhodes", "Day", "Simons", "Lawson", "Ortiz", "Jennings", "Wheeler", "Romero", "Dixon", "Dean", "Cunningham", "Snyder", "Schneider", "Saunders", "Byrne", "Douglas", "Monteiro", "Santiago", "Carpenter", "Franklin", "Frazier", "Armstrong", "Gordon", "Mcdonald", "Patterson", "Harrison", "Rose", "Machado", "Barrett", "Lawrence", "Elliott", "Jacobs", "Stevenson", "Vieira", "Porter", "Maxwell", "Craig", "Cohen", "Hansen", "Keller", "Neal", "Klein", "Bradley", "Mendes", "Page", "Parsons", "Marsh", "Boone", "Hale", "Curry", "Lynch", "Lowell", "Nash", "Mueller", "Erickson", "Barros", "Mccoy", "May", "Caldwell", "Leon", "Poole", "Borges", "Atkinson", "Fuller", "Christensen", "Casey", "Frank", "Sharp", "Freeman", "Tucker", "Hawkins", "Nichols", "Glover", "Cameron", "Shepherd", "Mckinney", "Barbosa", "Wolf", "Hoover", "Finch", "Lowe", "Wilkins", "Goodman", "Rice", "Frye", "Norton", "Mckay", "Barker", "Miles", "Crawford", "Norris", "Griffin", "Blair", "Bowers", "Baxter", "Mann", "Booth", "Clarke", "Stephens", "Brady", "Welch", "Brewer", "Solomon", "Pena", "Mcgrath", "Ingram", "Forbes", "Schwartz", "Combs", "Winters", "Dickson", "Nunes", "Clements", "Noble", "Vega", "Cooke", "Bates", "Branch", "Meier", "Huff", "Wong", "Dudley", "Mckenzie", "Moss", "Orr", "Conway", "Newman", "Stokes", "Randall", "Cline", "Hobbs", "Pratt", "Sherman", "Macias", "Monroe", "Hays", "Holt", "Barton", "Blanchard", "Dalton", "Crane", "Pugh", "Guimaraes", "Mccarthy", "Hardy", "Mcclain", "Whitney", "Powers", "Buckley", "Fitzgerald", "Sims", "Collier", "Bruce", "Chambers", "Eaton", "Sloan", "York", "Cortez", "Mclean", "Conner", "Livingston", "Nielsen", "Braun", "Todd", "Sutton", "Kirk", "Burnett", "Kramer", "Graves", "Hodge", "Lyons", "Baldwin", "Araujo", "Parks", "Mcdowell", "Flynn", "Marks", "Munoz", "Donaldson", "Carson", "Gould", "Villanueva", "Preston", "Hines", "Mcmahon", "Stuart", "Estrada", "Wiggins", "Gallagher", "Key", "Bass", "Gallardo", "Osborne", "Madden", "Bean", "Tate", "Kaufman", "Friedman", "Haley", "Davies", "Brock", "Osorio", "Stein", "Farrell", "Mercer", "Glenn", "Lucas", "Bridges", "Short", "Serrano", "Waller", "Mcclure", "Carrillo", "Morrow", "Christian", "Pickett", "Duffy", "Briggs", "Hatfield", "Bowen", "Calderon", "Burgess", "Pollard", "Oneil", "Skinner", "Avery", "Bright", "Underwood", "Cash", "Savage", "Novak", "Bryant", "Wilder", "Buck", "Munro", "Pittman", "Wu", "Humphrey", "Leblanc", "Fuentes", "Dailey", "Kemp", "Mcintyre", "Lutz", "Archer", "Hutchinson", "Sweeney", "Ho", "Joyce", "Merritt", "Chase", "Benson", "Mcneil", "Jewell", "Maddox", "Forrest", "Church", "Vaughan", "Wilkinson", "Landry", "Clayton", "Middleton", "Fry", "Davila", "Rivas", "Mack", "Cochran", "Lang", "Mcguire", "Zimmerman", "Dillard", "Pham", "Shea", "Roy", "Conrad", "Melton", "Vance", "Rocha", "Mcgee", "Beasley", "Finley", "Prince", "Hutchins", "Hammond", "Swanson", "Mejia", "Valenzuela", "Walton", "Mayer", "Barlow", "Rich", "Cisneros", "Reilly", "Benjamin", "Levy", "Schultz", "Drake", "Potter", "Potts", "Robinson", "Holmes", "Kent", "Blackburn", "Compton", "Koch", "Bartlett", "Gallegos", "Fleming", "Shields", "Mcintosh", "Bray", "Sherwood", "Wyatt", "Farmer", "Cantrell", "Justice", "Moses", "Lott", "Beard", "Small", "Meadows", "Colvin", "Rowland", "Best", "Proctor", "Bradshaw", "Glass", "Decker", "Stanton", "Sweet", "Donovan", "Gamble", "Krause", "Nolan", "Boucher", "Travis", "Luna", "Roth", "Pope", "Vogel", "Boyce", "Dorsey", "Downs", "Mays", "Waters", "Nixon", "House", "Kaiser", "Garrison", "Duran", "Hampton", "Dougherty", "Fraser", "Holder", "Trevino", "English", "Gates", "Quinn", "Navarro", "Valentine", "Wilkerson", "Shelton", "Cherry", "Wolfe", "Prado", "Vang", "Cowan", "Vazquez", "Bond", "Pace", "Frost", "Lake", "Carney", "Levine", "Massey", "Dejesus", "Paul", "Sparks", "Alves", "Cabrera", "Webster", "Britton", "Hull", "Burch", "Russell", "Correa", "Puckett", "Coffey", "Woodward", "Hewitt", "Delgado", "Larsen", "Mcknight", "Whitley", "Dillon", "Cardenas", "Raymond", "Boyle", "Foley", "Ewing", "Hinton", "Walls", "Barrera", "Horne", "Olsen", "Sykes", "Riggs", "Zamora", "Abbott", "Gill", "Valdez", "Schaefer", "Gilmore", "Whitfield", "Knapp", "Mahoney", "Mcfarland", "Becker", "Rosario", "Weeks", "Franco", "Brennan", "David", "Mcmillan", "Fitzpatrick", "Oconnor", "Blankenship", "Witt", "Summers", "Dotson", "Russo", "Case", "Duke", "Hebert", "Ochoa", "Griffith", "Giles", "Mcgowan", "Ellison", "Barry", "Holcomb", "Vaughn", "Merrill", "Bonner", "Hess", "Moon", "Wang", "Mcdaniel", "Herring", "Dunlap", "Leach", "Mullen", "Browning", "Stark", "Randolph", "Kirkland", "Reeves", "Cote", "Walsh", "Stout", "Oneal", "Figueroa", "Hensley", "Heath", "Conley", "Rowe", "Allison", "Grimes", "Parrish", "Hodges", "Nunez", "Bauer", "Morse", "Huffman", "Chavez", "Sampson", "Weber", "Pruitt", "Deleon", "Santana", "Mullins", "Barber", "Cervantes", "Aguilar", "Cantu", "Robles"
    ];
    return $surName[rand(0, count($surName) - 1)];
}

function generateFullName()
{
    return generateFirstName() . ' ' . generateSurName();
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
    $emailDomain = [
        // Domínios Internacionais
        "gmail.com", "yahoo.com", "hotmail.com", "aol.com", "outlook.com", "icloud.com", "mail.ru", "yandex.ru", "zoho.com", "protonmail.com", "msn.com", "live.com", "comcast.net", "sbcglobal.net", "verizon.net", "ymail.com", "me.com", "gmx.com", "fastmail.com", "web.de", "hushmail.com", "inbox.com", "rediffmail.com", "rocketmail.com", "earthlink.net", "mail.com", "excite.com", "cox.net", "juno.com", "mindspring.com", "laposte.net", "blueyonder.co.uk", "shaw.ca", "ntlworld.com", "sympatico.ca", "lycos.com",
        // Domínios Franceses
        "orange.fr", "free.fr", "wanadoo.fr",
        // Domínios do Reino Unido
        "hotmail.co.uk", "yahoo.co.uk", "btinternet.com", "virginmedia.com", "talktalk.net",
        // Domínios Alemães
        "t-online.de", "web.de", "gmx.de", "freenet.de",
        // Domínios Italianos
        "alice.it", "libero.it", "virgilio.it", "tiscali.it",
        // Domínios Espanhóis
        "hotmail.es", "yahoo.es",
        // Domínios Brasileiros
        "hotmail.com.br", "bol.com.br", "terra.com.br", "uol.com.br", "ig.com.br",
        // Domínios Asiáticos
        "qq.com", "163.com", "126.com", "sina.com", "sohu.com", "yeah.net", "yahoo.co.jp", "naver.com", "hanmail.net", "daum.net", "korea.com",
        // Outros Domínios Europeus
        "seznam.cz", "centrum.cz", "mail.bg", "abv.bg", "rambler.ru", "ukr.net",
        // Outros
        "optonline.net", "telus.net", "rogers.com", "xtra.co.nz", "bigpond.com", "optusnet.com.au", "telstra.com", "iinet.net.au",
        // Domínios Empresariais (exemplos, estes podem variar muito)
        "ibm.com", "microsoft.com", "google.com", "amazon.com"
    ];
    return $emailDomain[rand(0, count($emailDomain) - 1)];
}

function generateEmailName()
{
    return strtolower(str_replace(" ", ".", generateFullName()));
}

function generateEmail()
{
    return generateEmailName() . "@" . generateEmailDomain();
}

function generateLogradouro()
{
    $logradouro = [
        "Rua", "Avenida", "Travessa", "Beco", "Viela", "Alameda", "Praça", "Calçadão", "Largo", "Boulevard", "Rodovia", "Estrada", "Caminho", "Servidão", "Passagem", "Corredor", "Parque", "Jardim", "Via Expressa", "Viaduto", "Túnel", "Ponte", "Cais", "Porto", "Aeroporto",
        // Logradouros Rurais
        "Estrada de Terra", "Caminho de Servidão", "Trilha", "Sítio", "Fazenda", "Vale", "Morro", "Montanha", "Planalto", "Planície", "Cânion"
    ];
    return $logradouro[rand(0, count($logradouro) - 1)];
}

function generateStreet()
{
    $street = [
        "Amoroso", "Safira", "Esperança", "Girassol", "Margarida", "Asteca", "Zênite", "Marte", "Vênus", "Orquídea", "Castanheira", "Cedro", "Eucalipto", "Araucária", "Junqueira", "Marfim", "Topázio", "Esmeralda", "Turquesa", "Rubí", "Zirconia", "Coral", "Sardônia", "Obsidiana", "Pedra-Sabão", "Flamingo", "Coruja", "Pardal", "Falcão", "Cisne", "Pelican", "Papagaio", "Beija-Flor", "Sabiá", "Andorinha", "Águia", "Corvo", "Condor", "Canela", "Gengibre", "Hortelã", "Manjericão", "Tomilho", "Alecrim", "Louro", "Orégano", "Sálvia", "Cometa", "Meteorito", "Galáxia", "Planeta", "Estrela", "Saturno", "Júpiter", "Mercúrio", "Netuno", "Urano", "Plutão", "Sol", "Lua", "Terra", "Ícaro", "Artemis", "Apolo", "Hércules", "Achiles", "Pandora", "Zeus", "Afrodite", "Athena", "Hermes", "Poseidon", "Deméter", "Cronos", "Dionísio", "Perseu", "Teseu", "Eros", "Nêmesis", "Anúbis", "Osíris", "Ísis", "Horus", "Thot", "Sekhmet", "Bastet", "Rá", "Maat", "Geb", "Nut", "Nephtys", "Seth", "Hator", "Mênfis", "Thebas", "Luxor", "Gizé", "Amon-Rá", "Sphinx", "Himalaia", "Everest", "Kilimanjaro", "Aconcágua", "Denali", "Elbrus", "Monte Rosa", "Vesúvio", "Fugi", "Ararat", "K2", "Monte Branco", "Atlas", "Andes", "Alpes", "Cáucaso", "Urais", "Karpatos", "Apalaches", "Sierra Nevada", "Tetons", "Ozarks", "Cordilheira", "Rocosa", "Taiga", "Tundra", "Savana", "Manguezal", "Pantanal", "Cerrado", "Mojave", "Sahara", "Kalahari", "Gobi", "Thar", "Amazônia", "Danúbio", "Nilo", "Mississipi", "Amazonas", "Yangtzé", "Ganges", "Reno", "Senegal", "Tâmisa", "Volga", "Mekong", "Loire", "Tigre", "Eufrates", "Paraná", "Colorado", "Fraser", "Rio Grande", "Pecos", "Sabine", "Yukon", "Churchill", "Orinoco", "Zambeze", "Níger", "Congo", "Tiete", "Paranapanema", "Doce", "São Francisco", "Madeira", "Purus", "Tapajós", "Xingu", "Juruá", "Guaporé", "Araguaia", "Tocantins", "Iguaçu", "Paraguai", "Uruguai", "Murray", "Darling", "Ebro", "Douro", "Guadalquivir", "Lena", "Obi", "Yenisei", "Indo", "Brahmaputra", "Amur", "Fraser", "Columbia", "Mackenzie", "Ródano", "Po", "Elba", "Oder", "Vístula", "Dniester", "Dnieper", "Don", "Pechora", "Onega", "Svir", "Volturno", "Arno", "Tejo", "Guadiana", "Duero", "Guadalquivir", "Ebro", "Douro", "Vouga", "Mondego", "Sado", "Mira", "Guadiana", "Tejo", "Dão", "Paiva", "Vouga", "Ave", "Cávado", "Lima", "Minho", "Neiva", "Âncora", "Coura", "Vez", "Lima", "Homem", "Cávado", "Ave", "Douro", "Tâmega", "Paiva", "Vouga", "Dão", "Mondego", "Ceira", "Zêzere", "Nabão", "Sertã", "Ocreza", "Ponsul", "Erges", "Sever", "Geiru", "Caia", "Xévora", "Degebe", "Sorraia", "Almansor", "Maior", "Trancão", "Içá", "Mira", "Sado", "Galé", "Coina", "Samouco", "Tejo", "Safarujo", "Jamor", "Lizandro", "Colares", "Falcão", "Magoito"
    ];
    return $street[rand(0, count($street) - 1)];
}

function generateNumber()
{
    //TODO: Adicionar A, B, C etc.
    return generateInteger(['options' => ['min' => 1, 'max' => 999999]]);
}

function generateBairro()
{
    $bairros = [
        "Centro", "Jardim das Flores", "Vila Nova", "Bairro Alto", "São Francisco", "Morumbi", "Bela Vista", "Campos Elíseos", "Santa Tereza", "Copacabana", "Lapa", "Moema", "Liberdade", "Itaim Bibi", "Pinheiros", "Barra da Tijuca", "Vila Mariana", "Ipanema", "Santo Amaro", "Vila Madalena", "Botafogo", "Santana", "Tijuca", "Campo Belo", "Campo Grande", "São João", "Alto da Lapa", "Vila Olímpia", "Vila Leopoldina", "Cidade Jardim"
    ];
    return $bairros[rand(0, count($bairros) - 1)];
}

function generateCountry()
{
    $paises = [
        "Rússia", "Canadá", "Estados Unidos", "China", "Brasil", "Austrália", "Índia", "Argentina"
    ];
    return $paises[rand(0, count($paises) - 1)];
}

function generateState($country = 1)
{
    if ($country == 1) {
        $estadosBrasil = [
            "Acre", "Alagoas", "Amapá", "Amazonas", "Bahia", "Ceará", "Distrito Federal", "Espírito Santo", "Goiás", "Maranhão", "Mato Grosso", "Mato Grosso do Sul", "Minas Gerais", "Pará", "Paraíba", "Paraná", "Pernambuco", "Piauí", "Rio de Janeiro", "Rio Grande do Norte", "Rio Grande do Sul", "Rondônia", "Roraima", "Santa Catarina", "São Paulo", "Sergipe", "Tocantins"
        ];
        $estado = $estadosBrasil;
    }

    if ($country == 2) {
        $estadosEUA = [
            "Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"
        ];
        $estado = $estadosEUA;
    }

    if ($country == 3) {
        $provinciasTerritoriosCanada = [
            "Alberta", "Colúmbia Britânica", "Ilha do Príncipe Eduardo", "Manitoba", "Nova Brunswick", "Nova Escócia", "Nunavut", "Ontário", "Quebec", "Saskatchewan", "Terra Nova e Labrador", "Territórios do Noroeste", "Yukon"
        ];
        $estado = $provinciasTerritoriosCanada;
    }

    if ($country == 4) {
        $distritosFederaisRussia = [
            "Distrito Federal Central", "Distrito Federal do Extremo Oriente", "Distrito Federal do Norte do Cáucaso", "Distrito Federal do Noroeste", "Distrito Federal do Volga", "Distrito Federal dos Urais", "Distrito Federal da Sibéria", "Distrito Federal do Sul"
        ];
        $estado = $distritosFederaisRussia;
    }

    if ($country == 5) {
        $divisoesChina = [
            "Anhui", "Pequim (Beijing)", "Chongqing", "Fujian", "Gansu", "Cantão (Guangdong)", "Guangxi", "Guizhou", "Hainan", "Hebei", "Heilongjiang", "Henan", "Hong Kong", "Hubei", "Hunan", "Jiangsu", "Jiangxi", "Jilin", "Liaoning", "Macau", "Xinjiang", "Ningxia", "Qinghai", "Shandong", "Xangai (Shanghai)", "Shanxi", "Sichuan", "Taiwan",  // Nota: Taiwan é considerada uma província da China pela RPC, mas é autogovernada e considera-se separada por muitos.
            "Tibete", "Yunnan", "Zhejiang"
        ];
        $estado = $divisoesChina;
    }

    if ($country == 6) {
        $estadosTerritoriosAustralia = [
            "Nova Gales do Sul", "Queensland", "Austrália Meridional", "Tasmânia", "Victoria", "Austrália Ocidental", "Território do Norte", "Território da Capital Australiana"
        ];
        $estado = $estadosTerritoriosAustralia;
    }

    if ($country == 7) {
        $estadosTerritoriosUniaoIndia = [
            "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh", "Uttarakhand", "Bengala Ocidental",
            // Territórios da União
            "Andaman e Nicobar", "Chandigarh", "Dadra e Nagar Haveli e Daman e Diu", "Lakshadweep", "Delhi", "Puducherry", "Ladakh", "Jammu e Caxemira"
        ];
        $estado = $estadosTerritoriosUniaoIndia;
    }

    if ($country == 8) {
        $provinciasArgentina = [
            "Buenos Aires", "Catamarca", "Chaco", "Chubut", "Cidade Autônoma de Buenos Aires", "Córdoba", "Corrientes", "Entre Ríos", "Formosa", "Jujuy", "La Pampa", "La Rioja", "Mendoza", "Misiones", "Neuquén", "Río Negro", "Salta", "San Juan", "San Luis", "Santa Cruz", "Santa Fe", "Santiago del Estero", "Tierra del Fuego", "Tucumán"
        ];
        $estado = $provinciasArgentina;
    }

    return $estado[rand(0, count($estado) - 1)];
}

function generateAddress()
{
    //TODO: Gerar estados apenas de seus países.
    $address = '';
    $address .= generateLogradouro();
    $address .= '. ' . generateStreet();
    $address .= ', ' . generateNumber();
    $address .= ' - ' . generateBairro();
    $address .= ', ' . generateState();
    $address .= ', ' . generateCountry();
    return $address;

    //String final: "{$logradouro}. {$street}, {$number} - {$bairro}, {$state}, {$country}";
}

function generateLorem($value)
{
    $length = ($value['options']['length']) ? $value['options']['length'] : 1;
    $type = ($value['options']['type']) ? $value['options']['type'] : 1;

    $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque id fermentum ligula. Proin feugiat, nulla eget fermentum scelerisque, quam dui auctor sapien, quis luctus mi metus id sapien. Aenean consectetur libero a bibendum aliquet. Vivamus in libero a lorem facilisis tincidunt. Donec mattis eu libero sit amet blandit. Praesent varius rhoncus urna, eu iaculis nulla fermentum nec. Fusce tincidunt scelerisque ante id fermentum. Etiam finibus nec ipsum quis mattis. Sed interdum justo non augue cursus, non tincidunt libero facilisis. Sed eget semper sapien. Nulla facilisi. Sed vehicula facilisis malesuada. Phasellus dictum tincidunt dui, eu convallis ligula venenatis nec. Nullam ac nulla hendrerit, fermentum erat nec, egestas augue. Nulla facilisi. Vivamus iaculis non nunc in mattis. Sed luctus, ante quis fermentum viverra, erat erat tincidunt ex, quis fermentum nulla ligula ut mi. Integer ac nunc elit. Etiam fringilla quis nulla nec tempor. Curabitur bibendum, orci et sollicitudin tincidunt, nulla ligula elementum odio, id sodales purus libero id turpis. Sed sed metus vitae elit iaculis vehicula ut ac eros. Vestibulum vestibulum bibendum est, ut mattis sem viverra id. Vivamus porttitor blandit odio. Nullam nec felis non eros bibendum pharetra. Donec dictum, lectus in bibendum auctor, sapien erat dignissim ipsum, a sodales justo orci non nulla. Nullam feugiat elit sit amet nunc ullamcorper facilisis. Cras vel lacus odio. Nam gravida felis a odio sollicitudin tempus. In hac habitasse platea dictumst. Pellentesque facilisis odio ac sapien sodales feugiat. Pellentesque vestibulum turpis quis urna aliquam, vel vehicula elit pulvinar. Praesent bibendum, quam id dapibus feugiat, nisl urna mattis libero, eu tempus urna neque at sem. Morbi ullamcorper varius libero id vestibulum. Nunc suscipit mattis lorem, at suscipit lectus condimentum et. Fusce sit amet turpis ex. Ut at laoreet quam. Nullam ut purus massa. Maecenas quis nulla vel nulla fringilla sagittis eu a erat. Donec vitae diam luctus, gravida quam a, iaculis nulla. Aliquam erat volutpat. Integer quis nulla eget sem pharetra tincidunt. Fusce vel auctor odio, a tristique dolor. Etiam ultrices at dolor ut volutpat. Suspendisse euismod, enim in vestibulum pharetra, mi ante aliquet est, at scelerisque nisl velit quis odio. Nullam viverra tincidunt ex, vel vulputate turpis tincidunt nec. Nullam lacinia mi non purus tristique, id sagittis nulla sollicitudin. Quisque quis malesuada elit, nec facilisis purus. Nunc quis turpis eget enim lacinia bibendum. Aliquam eu sollicitudin metus, eget semper nulla. Praesent lacinia, tellus quis posuere euismod, elit sem porttitor lectus, ut viverra libero mi ut lorem. Nulla facilisi. Donec dignissim libero nec justo rhoncus, sit amet vestibulum nulla condimentum. Duis a velit mi. Nam a leo sem. Nullam ac nisi sed arcu fringilla facilisis nec a enim. Vivamus ullamcorper bibendum nunc. Integer semper, eros ut sollicitudin blandit, lorem ipsum varius turpis, a tristique nunc ante at arcu. Nam vehicula, enim a tincidunt egestas, nulla lectus aliquam sapien, sit amet dictum mauris turpis eu erat. Vestibulum eget iaculis urna, a vulputate odio. Proin dapibus nisl quis volutpat semper. Quisque malesuada eros a libero fringilla, id malesuada arcu blandit. Vivamus fermentum erat sit amet ligula aliquet, quis volutpat velit eleifend. Ut sodales massa ac urna tincidunt, quis tristique lorem varius. Sed blandit, neque id sodales dictum, odio mauris rhoncus dolor, nec rhoncus neque erat ut leo. Aliquam erat volutpat. Etiam a urna velit. Integer vestibulum ullamcorper nunc, non blandit quam condimentum ac. Suspendisse potenti. Vivamus consectetur a eros a vehicula. Aenean et libero ac enim tempus posuere id sit amet lectus. Fusce sollicitudin ipsum nec justo facilisis, vel interdum turpis cursus. Morbi sagittis libero ac elit efficitur pellentesque. Praesent eget vehicula turpis. Donec aliquet, mi non fermentum ultrices, metus metus accumsan leo, nec vulputate est turpis eu diam. Aenean vel lorem et erat vestibulum vulputate. Vestibulum eleifend hendrerit purus a cursus. Proin id auctor eros. Integer eget velit nec libero vestibulum venenatis. Cras id augue nec libero convallis venenatis. Integer a justo elit.';

    if ($type === 'words') {
        $lorem = generateLoremWords($lorem, $length);
    } elseif ($type === 'sentenses') {
        $lorem = generateLoremSentense($lorem, $length);
    } elseif ($type === 'paragraphs') {
        $lorem = generateLoremParagraph($lorem, $length);
    }
    return trim($lorem);
}

function generateLoremWords($lorem, $length = 1)
{
    $palavras = explode(' ', $lorem);
    $primeirasPalavras = array_slice($palavras, generateInteger(['options' => ['min' => 1, 'max' => count($palavras)]]), $length);
    return ucfirst(strtolower(str_replace(',', '', str_replace('.', '', implode(' ', $primeirasPalavras)))));
}

function generateLoremSentense($lorem, $length = 1)
{
    $palavras = explode('.', $lorem);
    $primeirasPalavras = array_slice($palavras, generateInteger(['options' => ['min' => 1, 'max' => count($palavras)]]), $length);
    return implode('.', $primeirasPalavras) . '.';
}

function generateLoremParagraph($lorem, $length = 1)
{
    $primeirosParagrafos = [];
    $paragrafos = explode('.', $lorem);
    for ($i = 1; $i <= $length; $i++) {
        for ($j = 1; $j <= 4; $j++) {
            $primeirosParagrafos = array_merge(array_slice($paragrafos, generateInteger(['options' => ['min' => 1, 'max' => count($paragrafos)]]), $length), $primeirosParagrafos);
        }
    }
    return implode('.', $primeirosParagrafos);
}

function generateLatitude($value)
{
    $min = ($value['options']['min']) ? $value['options']['min'] : -90.000001;
    $max = ($value['options']['max']) ? $value['options']['max'] : 90;
    return generateFloating(['options' => ['min' => $min, 'max' => $max]]);
}

function generateLongitude($value)
{
    $min = ($value['options']['min']) ? $value['options']['min'] : -180.000001;
    $max = ($value['options']['max']) ? $value['options']['max'] : 180;
    return generateFloating(['options' => ['min' => $min, 'max' => $max]]);
}

function generateDate($value)
{
    $utc = new DateTimeZone('UTC');
    $nowDateTime = new DateTime('now', $utc);

    $min = ($value['options']['min']) ? $value['options']['min'] : '01/01/1970';
    $max = ($value['options']['max']) ? $value['options']['max'] : $nowDateTime;
    $format = ($value['options']['format']) ? $value['options']['format'] : 'Y-m-d H:i:s';

    return generateDateBetween($min, $max, $format);
}

function generateDateBetween($min, $max, $format)
{
    $min = strtotime($min);
    $max = strtotime($max);

    $val = rand($min, $max);
    return date($format, $val);
}
