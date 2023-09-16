<?php

namespace Rmorillo\JsonGenerator;

class Address
{
    private Util $util;
    private Number $number;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->util = new Util;
        $this->number = new Number;
    }

    /**
     * @return string
     */
    private function logradouro(): string
    {
        $logradouroList = [
            "Rua", "Avenida", "Travessa", "Beco", "Viela", "Alameda", "Praça", "Calçadão", "Largo", "Boulevard", "Rodovia", "Estrada", "Caminho", "Servidão", "Passagem", "Corredor", "Parque", "Jardim", "Via Expressa", "Viaduto", "Túnel", "Ponte", "Cais", "Porto", "Aeroporto",
            // Logradouros Rurais
            "Estrada de Terra", "Caminho de Servidão", "Trilha", "Sítio", "Fazenda", "Vale", "Morro", "Montanha", "Planalto", "Planície", "Cânion"
        ];
        return $this->util->selectItemOnArray($logradouroList);
    }

    /**
     * @return string
     */
    private function street(): string
    {
        $street = [
            "Amoroso", "Safira", "Esperança", "Girassol", "Margarida", "Asteca", "Zênite", "Marte", "Vênus", "Orquídea", "Castanheira", "Cedro", "Eucalipto", "Araucária", "Junqueira", "Marfim", "Topázio", "Esmeralda", "Turquesa", "Rubí", "Zirconia", "Coral", "Sardônia", "Obsidiana", "Pedra-Sabão", "Flamingo", "Coruja", "Pardal", "Falcão", "Cisne", "Pelican", "Papagaio", "Beija-Flor", "Sabiá", "Andorinha", "Águia", "Corvo", "Condor", "Canela", "Gengibre", "Hortelã", "Manjericão", "Tomilho", "Alecrim", "Louro", "Orégano", "Sálvia", "Cometa", "Meteorito", "Galáxia", "Planeta", "Estrela", "Saturno", "Júpiter", "Mercúrio", "Netuno", "Urano", "Plutão", "Sol", "Lua", "Terra", "Ícaro", "Artemis", "Apolo", "Hércules", "Achiles", "Pandora", "Zeus", "Afrodite", "Athena", "Hermes", "Poseidon", "Deméter", "Cronos", "Dionísio", "Perseu", "Teseu", "Eros", "Nêmesis", "Anúbis", "Osíris", "Ísis", "Horus", "Thot", "Sekhmet", "Bastet", "Rá", "Maat", "Geb", "Nut", "Nephtys", "Seth", "Hator", "Mênfis", "Thebas", "Luxor", "Gizé", "Amon-Rá", "Sphinx", "Himalaia", "Everest", "Kilimanjaro", "Aconcágua", "Denali", "Elbrus", "Monte Rosa", "Vesúvio", "Fugi", "Ararat", "K2", "Monte Branco", "Atlas", "Andes", "Alpes", "Cáucaso", "Urais", "Karpatos", "Apalaches", "Sierra Nevada", "Tetons", "Ozarks", "Cordilheira", "Rocosa", "Taiga", "Tundra", "Savana", "Manguezal", "Pantanal", "Cerrado", "Mojave", "Sahara", "Kalahari", "Gobi", "Thar", "Amazônia", "Danúbio", "Nilo", "Mississipi", "Amazonas", "Yangtzé", "Ganges", "Reno", "Senegal", "Tâmisa", "Volga", "Mekong", "Loire", "Tigre", "Eufrates", "Paraná", "Colorado", "Fraser", "Rio Grande", "Pecos", "Sabine", "Yukon", "Churchill", "Orinoco", "Zambeze", "Níger", "Congo", "Tiete", "Paranapanema", "Doce", "São Francisco", "Madeira", "Purus", "Tapajós", "Xingu", "Juruá", "Guaporé", "Araguaia", "Tocantins", "Iguaçu", "Paraguai", "Uruguai", "Murray", "Darling", "Ebro", "Douro", "Guadalquivir", "Lena", "Obi", "Yenisei", "Indo", "Brahmaputra", "Amur", "Fraser", "Columbia", "Mackenzie", "Ródano", "Po", "Elba", "Oder", "Vístula", "Dniester", "Dnieper", "Don", "Pechora", "Onega", "Svir", "Volturno", "Arno", "Tejo", "Guadiana", "Duero", "Guadalquivir", "Ebro", "Douro", "Vouga", "Mondego", "Sado", "Mira", "Guadiana", "Tejo", "Dão", "Paiva", "Vouga", "Ave", "Cávado", "Lima", "Minho", "Neiva", "Âncora", "Coura", "Vez", "Lima", "Homem", "Cávado", "Ave", "Douro", "Tâmega", "Paiva", "Vouga", "Dão", "Mondego", "Ceira", "Zêzere", "Nabão", "Sertã", "Ocreza", "Ponsul", "Erges", "Sever", "Geiru", "Caia", "Xévora", "Degebe", "Sorraia", "Almansor", "Maior", "Trancão", "Içá", "Mira", "Sado", "Galé", "Coina", "Samouco", "Tejo", "Safarujo", "Jamor", "Lizandro", "Colares", "Falcão", "Magoito"
        ];
        return $this->util->selectItemOnArray($street);
    }

    /**
     * @return int
     */
    private function number(): int
    {
        return $this->number->getInteger(1, 9999);
    }

    /**
     * @return string
     */
    private function bairro(): string
    {
        $bairros = [
            "Centro", "Jardim das Flores", "Vila Nova", "Bairro Alto", "São Francisco", "Morumbi", "Bela Vista", "Campos Elíseos", "Santa Tereza", "Copacabana", "Lapa", "Moema", "Liberdade", "Itaim Bibi", "Pinheiros", "Barra da Tijuca", "Vila Mariana", "Ipanema", "Santo Amaro", "Vila Madalena", "Botafogo", "Santana", "Tijuca", "Campo Belo", "Campo Grande", "São João", "Alto da Lapa", "Vila Olímpia", "Vila Leopoldina", "Cidade Jardim"
        ];
        return $this->util->selectItemOnArray($bairros);
    }

    /**
     * @param int $country
     * @return string
     */
    private function state(int $country = 1): string
    {
        $country = $this->util->trataValor($country, 'integer', 1);

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

        return $this->util->selectItemOnArray($estado);
    }

    /**
     * @return string
     */
    private function country(int $country = 0): string
    {
        $countryList = [
            "Brasil", "Rússia", "Canadá", "Estados Unidos", "China", "Austrália", "Índia", "Argentina"
        ];
        if ($country > 0 && $country < 9) {
            return $countryList[$country];
        }
        return $this->util->selectItemOnArray($countryList);
    }

    /**
     * @return string
     */
    private function address(): string
    {
        //String final: "{$logradouro}. {$street}, {$number} - {$bairro}, {$state}, {$country}";
        return $this->logradouro() . '. ' . $this->street() . ', ' . $this->number() . ' - ' . $this->bairro() . ', ' . $this->state() . ', ' . $this->country();
    }

    //Get and Set methods.
    /**
     * @return string
     */
    public function getLogradouro(): string
    {
        return $this->logradouro();
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street();
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number();
    }

    /**
     * @return string
     */
    public function getBairro(): string
    {
        return $this->bairro();
    }

    public function getCountry($country = 1): string
    {
        return $this->country($country = 1);
    }

    public function getState(int $country = 1): string
    {
        return $this->state($country);
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        //TODO: Adicionar A, B, C etc ao número caso seja endereço completo.
        return $this->address();
    }
}
