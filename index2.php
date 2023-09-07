<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$jsonOriginal = json_decode(file_get_contents("php://input"), true);

if (!empty($jsonOriginal)) {
    $index = 1;
    $return = replaceAll($jsonOriginal, $index);

    http_response_code(200);
    echo json_encode($return);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Nenhum dado recebido."]);
}

function keyType($valorAtual)
{
    if (isset($valorAtual['repeat()'])) {
        return 1;
    }
    if (isset($valorAtual['bool()'])) {
        return 2;
    }
    if (isset($valorAtual['floating()'])) {
        return 3;
    }
    if (isset($valorAtual['integer()'])) {
        return 4;
    }
    if (isset($valorAtual['custom()'])) {
        return 5;
    }
    return 0;
}

function replaceAll($jsonAtual, &$index)
{
    $localIndex = $index;
    foreach ($jsonAtual as $key => &$value) {
        if (is_array($value)) {
            $keyType = keyType($value);
            if ($keyType == 1) {
                //Substitui o repeat().
                $repeatIndex = 1;
                $value = replaceRepeat($value['repeat()'], $repeatIndex);
            } elseif ($keyType == 2) {
                //Substitui o bool().
                //Gera um valor de 1 a 100, caso o valor seja menor ou igual ao falsePercentage, o valor será false, caso seja menor ou igual ao nullPercentage, o valor será null, caso contrário, o valor será true.
                //A chance de ser nulo depende de não ser false e é gerado 2 numeros random, assim o false não é anulado pelo null caso os 2 sejam 10% por exemplo.
                if (rand(1, 100) <= $jsonAtual[$key]['bool()']['options']['falsePercentage']) {
                    $jsonAtual[$key] = false;
                } elseif (rand(1, 100) <= $jsonAtual[$key]['bool()']['options']['nullPercentage']) {
                    $jsonAtual[$key] = null;
                } else {
                    $jsonAtual[$key] = true;
                }
            } elseif ($keyType == 3) {
                //Substitui o floating().
                $min = $value['floating()']['options']['min'];
                $max = $value['floating()']['options']['max'];
                $decimals = $value['floating()']['options']['decimals'];
                $thousand = $value['floating()']['options']['thousand'];
                $separator = $value['floating()']['options']['separator'];
                $prefix = $value['floating()']['options']['prefix'];

                $result = generateFormattedRandomFloat($min, $max, $decimals, $thousand, $separator, $prefix);

                $jsonAtual[$key] = $result;
            } elseif ($keyType == 4) {
                //Substitui o integer().
                $min = $value['integer()']['options']['min'];
                $max = $value['integer()']['options']['max'];

                if (rand(1, 100) <= $jsonAtual[$key]['integer()']['options']['nullPercentage']) {
                    $jsonAtual[$key] = null;
                } else {
                    $jsonAtual[$key] = rand($jsonAtual[$key]['integer()']['options']['min'], $jsonAtual[$key]['integer()']['options']['max']);
                }
            } elseif ($keyType == 5) {
                $list = $jsonAtual[$key]['custom()']['options'];
                $jsonAtual[$key] = $list[rand(1, count($list))];
            } else {
                $subIndex = 1; // reinicialize o índice para este nível
                $value = replaceAll($value, $subIndex);
            }
        } else {
            if ($value === 'index()') {
                $value = $localIndex;
                $localIndex++;
            }
            if ($value === 'objectId()') {
                $value = generateRandomHash();
            }
            if ($value === 'guid()') {
                $value = generateGuid();
            }
            if ($value === 'bool()') {
                $value = rand(0, 1) === 1;
            }
            if ($value === 'firstName()') {
                $jsonAtual[$key] = firstName();
            }
            if ($value === 'surname()') {
                $jsonAtual[$key] = surname();
            }
        }
    }
    $index = $localIndex;
    return $jsonAtual;
}

function replaceRepeat($value, &$index)
{
    $min = $value['options']['min'];
    $max = $value['options']['max'];
    $jsonAtual = [];
    $localIndex = $index;
    for ($i = 1; $i <= rand($min, $max); $i++) {
        $jsonAtual[] = replaceAll($value['data'], $localIndex);
    }
    $index = $localIndex;
    return $jsonAtual;
}

function generateRandomHash()
{
    return md5(uniqid(rand(), true));
    //return bin2hex(random_bytes(16)); // gera um hash aleatório
}

function generateGuid()
{
    if (function_exists('com_create_guid')) {
        return trim(com_create_guid(), '{}');
    } else {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}

function generateFormattedRandomFloat($min, $max, $decimals, $thousand, $separator, $prefix)
{
    // Gerar número float aleatório
    $scale = pow(10, $decimals);
    $randomFloat = mt_rand($min * $scale, $max * $scale) / $scale;

    // Formatar o número float
    $formattedFloat = number_format($randomFloat, $decimals, $separator, $thousand);

    // Adicionar o prefixo
    return $prefix . ' ' . $formattedFloat;
}

function firstName()
{
    $firstName = [
        "Maria", "João", "Ana", "José", "Lucas", "Emily", "Sophia", "William", "Isabella", "Pedro", "Gabriel", "Carlos", "Daniel", "Camila", "Fernanda", "Beatriz", "Clara", "Paulo", "Luiza", "Arthur", "Mateus", "Amanda", "Marcelo", "Eduardo", "Alice", "David", "Mariana", "Ricardo", "Felipe", "Raquel", "Júlia", "Victor", "Catarina", "Isabel", "Luis", "Diego", "Bruno", "Alex", "Vitoria", "Francisco", "Rafael", "Samantha", "Sofia", "Henrique", "Adriana", "Roberto", "Miguel", "Elena", "Marcos", "Juan", "Leonardo", "Thomas", "Samuel", "Carolina", "Cristina", "Igor", "Marina", "Lara", "Patricia", "Sara", "Natalia", "Fábio", "Juliana", "Matthew", "Sarah", "Rodrigo", "Aline", "Flávia", "Letícia", "Adriano", "Grace", "Augusto", "Regina", "Diana", "Isaac", "Gustavo", "Viviane", "Tiago", "Fernando", "Michelle", "Tatiana", "Otávio", "Nina", "Simone", "Jorge", "Giovanna", "Laura", "Ariana", "Luciana", "Elaine", "Maya", "Leila", "Filipe", "Ester", "Benjamin", "Inês", "Marco", "Yasmin", "Adam", "Ian", "Kelly", "Julia", "Rosa", "Alexandra", "Leandro", "Chris", "Nelson", "Vinícius", "Daniele", "Nathalia", "Erick", "Lucia", "Silvia", "Roger", "Jake", "Sérgio", "Paola", "Gisele", "Antonio", "Rita", "Cecilia", "Xavier", "Marta", "Caio", "Hugo", "Bianca", "Fiona", "Jonathan", "Jason", "Mauro", "Olivia", "Emma", "Valentina", "Leo", "Larissa", "Andrea", "Sophie", "Kyle", "Renata", "Esther", "Alana", "Bernardo", "Elisa", "Paula", "Taylor", "Peter", "Tony", "Ivan", "Angela", "Zoe", "George", "Joana", "Rafaela", "Irene", "Edgar", "Nancy", "Vera", "Martin", "Aaron", "Oscar", "Violet", "Celine", "Nick", "Ryan", "Lia", "Cassandra", "Madison", "Jesse", "Vivian", "Teresa", "Ellen", "Bryan", "Melissa", "Ivy", "Blake", "Lily", "Albert", "Noah", "Isa", "Jade", "Carmen", "Keith", "Nora", "Louise", "Sean", "Dylan", "Skyler", "Gloria", "Luke", "Eva", "Joy", "Jordan", "Andre", "Cristiano", "Faye", "Cole", "Andres", "Charlotte", "Zara", "Denise", "Beverly", "Mila", "Sam", "Jean", "Kylie", "Justin", "Wesley", "Claudia", "Bruce", "Alberto", "Gwen", "Owen", "Hannah", "Eric", "Henry", "Lorraine", "Philip", "Molly", "Saul", "Jackie", "Elliot", "Michele", "Max", "Wanda", "Mauricio", "Gael", "Isadora", "Lena", "Eleanor", "Graham", "Kirk", "Allan", "Valeria", "Mabel", "Abigail", "Neil", "Omar", "Flora", "Deborah", "Eugene", "Leticia", "Lana", "Hazel", "Riley", "Oliver", "Cody", "Ashley", "Alan", "Javier", "Silas", "Vivienne", "Isla", "Walter", "Scott", "Gene", "Dennis", "Evelyn", "Daisy", "Rex", "Ramona", "Tiffany", "Nathaniel", "Roy", "Olive", "Marie", "Pearl", "Ray", "Carl", "Pamela", "Penelope", "Mandy", "Stephanie", "Eliza", "Rosie", "Kayla", "Eve", "Edith", "Vanessa", "Juliet", "Mae", "Andy", "Renee", "June", "Agnes", "Harold", "Lola", "Stanley", "Caleb", "Rosemary", "Lester", "Priscilla", "Armando", "Clifford", "Rebecca", "Vicki", "Winnie", "Myra", "Lydia", "Kathryn", "Floyd", "Veronica", "Jill", "Monica", "Tina", "Ben", "Jon", "Isaiah", "Todd", "Marion", "Cynthia", "Kent", "Lyle", "Sheila", "Kathy", "Shirley", "Sylvia", "Sandy", "Cheryl", "Sonia", "Mercedes", "Dorothy", "Joel", "Ismael", "Claire", "Colin", "Helen", "Nigel", "Curtis", "Darren", "Morgan", "Norman", "Ruby", "Cara", "Troy", "Jasmine", "Travis", "Clyde", "Penny", "Vicky", "Randy", "Eunice", "Lillian", "Trent", "Dale", "Perry", "Daryl", "Ellis", "Leigh", "Enrique", "Manny", "Terri", "Brad", "Jeanne", "Lauren", "Shane", "Colleen", "Terry", "Lindsey", "Robin", "Neal", "Cecil", "Anita", "Beth", "Brett", "Garry", "Conrad", "Greg", "Lyndon", "Dwight", "Iris", "Bernadette", "Janet", "Grant", "Quincy", "Roland", "Brent", "Stuart", "Audrey", "Raul", "Candace", "Mack", "Debbie", "Maureen", "Freddie", "Cora", "Edwin", "Russell", "Quinn", "Spencer", "Rhonda", "Cedric", "Vernon", "Arturo", "Lou", "Olga", "Darnell", "Viola", "Naomi", "Wilma", "Benny", "Bridget", "Nadine", "Elmer", "Lonnie", "Angelo", "Becky", "Geneva", "Kerry", "Tabitha", "Alfredo", "Billie", "Loretta", "Miriam", "Laurie", "Janice", "Constance", "Wes", "Belinda", "Duane", "Gerard", "Trina", "Delia", "Suzette", "Milton", "Melody", "Patsy", "Reginald", "Sylvester", "Elias", "Wendy", "Harvey", "Ollie", "Harrison", "Rose", "Marshall", "Clark", "Rosalind", "Charlene", "Amos", "Tommy", "Elton", "Gina", "Sherri", "Roderick", "Misty", "Nell", "Warren", "Muriel", "Lynn", "Ginger", "Donna", "Carla", "Eloise", "Dixie", "Phyllis", "Lynne", "Roscoe", "Kim", "Polly", "Ned", "Dora", "Rod", "Rachael", "Carole", "Maxine", "Franklin", "Guy", "Cleo", "Cornelius", "Dina", "Bert", "Dewayne", "Mona", "Melba", "Seth", "Edna", "Sybil", "Dolores", "Doris", "Harriet", "Glen", "Rosetta", "Hattie", "Bertha", "Leona", "Hope", "Ira", "Willis", "Minnie", "Eula", "Wade", "Meredith", "Pearlie", "Elvira", "Jeannie", "Alton", "Delbert", "Clarence", "Luther", "Nellie", "Effie", "Wallace", "Isiah", "Genevieve", "Adele", "Beulah", "Blanche", "Iva", "Gertie", "Gwendolyn", "Homer", "Myrtle", "Elliott", "Percy", "Rufus", "Chester", "Cecelia", "Elnora", "Fannie", "Lucille", "Mattie", "Flossie", "Lila", "Maggie", "Adelaide", "Mable", "Stella", "Hollie", "Lyman", "Ida", "Fern", "Susie", "Gladys", "Alma", "Opal", "Sally", "Ruben", "Pete", "Rosalie", "Tillie", "Hester", "Addie", "Lottie", "Lela", "Johnnie", "Maude", "Agatha", "Geraldine", "Lulu", "Neva", "Frieda", "Aurelia", "Goldie", "Lenora", "Nelle", "Celia", "Maud", "Verna", "Inez", "Elma", "Luella", "Della", "Thelma", "Henrietta", "Ora", "Cornelia", "Willa", "Ethel", "Adeline", "Lina", "Zella", "Ina", "Lelia", "Josie", "Harriett", "Essie", "Sue", "Sadie", "Johanna", "Mina", "Kitty", "Birdie", "Lillie", "Lizzie", "Mollie", "Elva", "Avis", "Louisa", "Eugenia", "Maudie", "Hanna", "Florine", "Dolly", "Pauline", "Louella", "Adela", "Gussie", "Nettie", "Freda", "Lucile", "Alta", "Marian", "Janie", "Marguerite", "Zora", "Leora", "Jennie", "Fanny", "Edythe", "Etta", "Sallie", "Myrtie", "Mamie", "Theresa", "Ada", "Zula", "Winifred", "Madge", "Ola", "Lucy", "Susan", "Barbara", "Amelia", "Nola", "Lorena", "Ophelia", "Kathleen", "Lettie", "Roxie", "Estelle", "Millie", "Katharine", "Ella", "Elsie", "Annie", "Hilda", "Josephine", "Bessie", "Isabelle", "Anastasia", "Dulce", "Hernan", "Agustin", "Julio", "Cesar", "Ignacio", "Vicente", "Emilio", "Alejandro", "Manuel", "Guillermo", "Jose", "Sergio", "Lorenzo", "Sebastian", "Gerardo", "Esteban", "Adolfo", "Julian", "Benito", "Ramiro", "Gonzalo", "Joaquin", "Mario", "Alvaro", "Felix", "Horacio", "Juanita", "Ines", "Alba", "Susana", "Alicia", "Lourdes", "Juana", "Rocio", "Martha", "Norma", "Luz", "Magdalena", "Esperanza", "Blanca", "Rebeca", "Fabiola", "Matilde", "Yolanda", "Graciela", "Frida", "Antonia", "Amalia", "Isidora", "Soledad", "Concepcion", "Rosalinda", "Angeles", "Elsa", "Martina", "Violeta", "Rosario", "Victoria", "Gabriela", "Agustina", "Estela", "Margarita", "Carlota", "Alejandra", "Sol", "Liliana", "Inmaculada", "Felisa", "Maribel", "Luisa", "Anahi", "Araceli", "Azucena", "Georgina", "Hortensia", "Julieta", "Rosalba", "Delfina", "Celeste", "Macarena", "Bernarda", "Paloma", "Guadalupe", "Ruth", "Beatrice", "Mildred", "Virginia", "Marjorie", "Margaret", "Florence", "Caroline", "Catherine", "Lula", "Gertrude", "Katherine", "Lucinda", "Jessie", "Jane", "Estella", "Bess", "Georgia", "Betty", "Frances", "Katie", "Alberta", "Mary", "Matilda", "Madeline", "Phoebe", "Bonnie", "May", "Anne"
    ];
    return $firstName[rand(0, count($firstName) - 1)];
}

function surname() {
    $surname = [
        "Silva", "Smith", "Johnson", "Garcia", "Rodriguez", "Martinez", "Williams", "Brown", "Jones", "Pereira", "Hernandez", "Lee", "Gonzalez", "Perez", "Lopez", "Murphy", "Anderson", "Costa", "Kim", "Davis", "Wilson", "Taylor", "Thomas", "Moore", "Santos", "Clark", "Thompson", "Lima", "Sanchez", "Harris", "Nelson", "Evans", "Adams", "Scott", "Cook", "Bailey", "Fernandes", "Hall", "Campbell", "Mitchell", "Roberts", "Young", "Gomes", "Wright", "Martins", "Hill", "Green", "King", "Carter", "Fisher", "Ribeiro", "Turner", "Phillips", "Allen", "Torres", "Parker", "Collins", "Ramirez", "Almeida", "Freitas", "Morris", "Hughes", "Reed", "Flores", "Edwards", "Kelly", "Howard", "Olson", "Cooper", "Ferreira", "Jenkins", "Ross", "Simmons", "Diaz", "Powell", "Graham", "Rogers", "Ward", "James", "Foster", "Barnes", "Bell", "Murray", "Moreira", "Rivera", "Morgan", "Stevens", "Meyer", "Wallace", "Mello", "Ramos", "Woods", "Long", "Ford", "Chen", "Price", "Watson", "Butler", "Jensen", "Bennett", "Reyes", "Wells", "Castro", "Coelho", "Perry", "Peterson", "West", "Hunt", "Stewart", "Fields", "Hoffman", "Gibson", "Gray", "Marques", "Ruiz", "Vasquez", "Daniels", "Harper", "Arnold", "Schmidt", "Boyd", "Warren", "Medeiros", "Fox", "Jordan", "Hayes", "Harvey", "Beck", "Cole", "Black", "Hunter", "Webb", "Guerra", "Morrison", "Ryan", "Carvalho", "Baker", "Vargas", "Oliveira", "Cruz", "Dunn", "Gutierrez", "Mills", "Nguyen", "Matthews", "Alexander", "Spencer", "Sullivan", "Shaw", "Lambert", "Weaver", "Reid", "Bishop", "Fowler", "Nogueira", "Knight", "Gilbert", "Rhodes", "Day", "Simons", "Lawson", "Ortiz", "Jennings", "Wheeler", "Romero", "Dixon", "Dean", "Cunningham", "Snyder", "Schneider", "Saunders", "Byrne", "Douglas", "Monteiro", "Santiago", "Carpenter", "Franklin", "Frazier", "Armstrong", "Gordon", "Mcdonald", "Patterson", "Harrison", "Rose", "Machado", "Barrett", "Lawrence", "Elliott", "Jacobs", "Stevenson", "Vieira", "Porter", "Maxwell", "Craig", "Cohen", "Hansen", "Keller", "Neal", "Klein", "Bradley", "Mendes", "Page", "Parsons", "Marsh", "Boone", "Hale", "Curry", "Lynch", "Lowell", "Nash", "Mueller", "Erickson", "Barros", "Mccoy", "May", "Caldwell", "Leon", "Poole", "Borges", "Atkinson", "Fuller", "Christensen", "Casey", "Frank", "Sharp", "Freeman", "Tucker", "Hawkins", "Nichols", "Glover", "Cameron", "Shepherd", "Mckinney", "Barbosa", "Wolf", "Hoover", "Finch", "Lowe", "Wilkins", "Goodman", "Rice", "Frye", "Norton", "Mckay", "Barker", "Miles", "Crawford", "Norris", "Griffin", "Blair", "Bowers", "Baxter", "Mann", "Booth", "Clarke", "Stephens", "Brady", "Welch", "Brewer", "Solomon", "Pena", "Mcgrath", "Ingram", "Forbes", "Schwartz", "Combs", "Winters", "Dickson", "Nunes", "Clements", "Noble", "Vega", "Cooke", "Bates", "Branch", "Meier", "Huff", "Wong", "Dudley", "Mckenzie", "Moss", "Orr", "Conway", "Newman", "Stokes", "Randall", "Cline", "Hobbs", "Pratt", "Sherman", "Macias", "Monroe", "Hays", "Holt", "Barton", "Blanchard", "Dalton", "Crane", "Pugh", "Guimaraes", "Mccarthy", "Hardy", "Mcclain", "Whitney", "Powers", "Buckley", "Fitzgerald", "Sims", "Collier", "Bruce", "Chambers", "Eaton", "Sloan", "York", "Cortez", "Mclean", "Conner", "Livingston", "Nielsen", "Braun", "Todd", "Sutton", "Kirk", "Burnett", "Kramer", "Graves", "Hodge", "Lyons", "Baldwin", "Araujo", "Parks", "Mcdowell", "Flynn", "Marks", "Munoz", "Donaldson", "Carson", "Gould", "Villanueva", "Preston", "Hines", "Mcmahon", "Stuart", "Estrada", "Wiggins", "Gallagher", "Key", "Bass", "Gallardo", "Osborne", "Madden", "Bean", "Tate", "Kaufman", "Friedman", "Haley", "Davies", "Brock", "Osorio", "Stein", "Farrell", "Mercer", "Glenn", "Lucas", "Bridges", "Short", "Serrano", "Waller", "Mcclure", "Carrillo", "Morrow", "Christian", "Pickett", "Duffy", "Briggs", "Hatfield", "Bowen", "Calderon", "Burgess", "Pollard", "Oneil", "Skinner", "Avery", "Bright", "Underwood", "Cash", "Savage", "Novak", "Bryant", "Wilder", "Buck", "Munro", "Pittman", "Wu", "Humphrey", "Leblanc", "Fuentes", "Dailey", "Kemp", "Mcintyre", "Lutz", "Archer", "Hutchinson", "Sweeney", "Ho", "Joyce", "Merritt", "Chase", "Benson", "Mcneil", "Jewell", "Maddox", "Forrest", "Church", "Vaughan", "Wilkinson", "Landry", "Clayton", "Middleton", "Fry", "Davila", "Rivas", "Mack", "Cochran", "Lang", "Mcguire", "Zimmerman", "Dillard", "Pham", "Shea", "Roy", "Conrad", "Melton", "Vance", "Rocha", "Mcgee", "Beasley", "Finley", "Prince", "Hutchins", "Hammond", "Swanson", "Mejia", "Valenzuela", "Walton", "Mayer", "Barlow", "Rich", "Cisneros", "Reilly", "Benjamin", "Levy", "Schultz", "Drake", "Potter", "Potts", "Robinson", "Holmes", "Kent", "Blackburn", "Compton", "Koch", "Bartlett", "Gallegos", "Fleming", "Shields", "Mcintosh", "Bray", "Sherwood", "Wyatt", "Farmer", "Cantrell", "Justice", "Moses", "Lott", "Beard", "Small", "Meadows", "Colvin", "Rowland", "Best", "Proctor", "Bradshaw", "Glass", "Decker", "Stanton", "Sweet", "Donovan", "Gamble", "Krause", "Nolan", "Boucher", "Travis", "Luna", "Roth", "Pope", "Vogel", "Boyce", "Dorsey", "Downs", "Mays", "Waters", "Nixon", "House", "Kaiser", "Garrison", "Duran", "Hampton", "Dougherty", "Fraser", "Holder", "Trevino", "English", "Gates", "Quinn", "Navarro", "Valentine", "Wilkerson", "Shelton", "Cherry", "Wolfe", "Prado", "Vang", "Cowan", "Vazquez", "Bond", "Pace", "Frost", "Lake", "Carney", "Levine", "Massey", "Dejesus", "Paul", "Sparks", "Alves", "Cabrera", "Webster", "Britton", "Hull", "Burch", "Russell", "Correa", "Puckett", "Coffey", "Woodward", "Hewitt", "Delgado", "Larsen", "Mcknight", "Whitley", "Dillon", "Cardenas", "Raymond", "Boyle", "Foley", "Ewing", "Hinton", "Walls", "Barrera", "Horne", "Olsen", "Sykes", "Riggs", "Zamora", "Abbott", "Gill", "Valdez", "Schaefer", "Gilmore", "Whitfield", "Knapp", "Mahoney", "Mcfarland", "Becker", "Rosario", "Weeks", "Franco", "Brennan", "David", "Mcmillan", "Fitzpatrick", "Oconnor", "Blankenship", "Witt", "Summers", "Dotson", "Russo", "Case", "Duke", "Hebert", "Ochoa", "Griffith", "Giles", "Mcgowan", "Ellison", "Barry", "Holcomb", "Vaughn", "Merrill", "Bonner", "Hess", "Moon", "Wang", "Mcdaniel", "Herring", "Dunlap", "Leach", "Mullen", "Browning", "Stark", "Randolph", "Kirkland", "Reeves", "Cote", "Walsh", "Stout", "Oneal", "Figueroa", "Hensley", "Heath", "Conley", "Rowe", "Allison", "Grimes", "Parrish", "Hodges", "Nunez", "Bauer", "Morse", "Huffman", "Chavez", "Sampson", "Weber", "Pruitt", "Deleon", "Santana", "Mullins", "Barber", "Cervantes", "Aguilar", "Cantu", "Robles"
    ];
    return $surname[rand(0, count($surname) - 1)];
}