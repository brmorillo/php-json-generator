<?php

namespace Rmorillo\JsonGenerator;

class Address
{
    private Util $util;
    private $address, $logradouro, $street, $bairro, $country;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->util = new Util();
        $this->logradouro();
        $this->street();
        $this->address();
        $this->bairro();
        $this->country();
    }

    /**
     * @return void
     */
    private function logradouro(): void
    {
        $logradouroList = [
            "Rua", "Avenida", "Travessa", "Beco", "Viela", "Alameda", "Praça", "Calçadão", "Largo", "Boulevard", "Rodovia", "Estrada", "Caminho", "Servidão", "Passagem", "Corredor", "Parque", "Jardim", "Via Expressa", "Viaduto", "Túnel", "Ponte", "Cais", "Porto", "Aeroporto",
            // Logradouros Rurais
            "Estrada de Terra", "Caminho de Servidão", "Trilha", "Sítio", "Fazenda", "Vale", "Morro", "Montanha", "Planalto", "Planície", "Cânion"
        ];
        $this->logradouro = $this->util->selectItemOnArray($logradouroList);
    }

    /**
     * @return void
     */
    private function street(): void
    {
        $street = [
            "Amoroso", "Safira", "Esperança", "Girassol", "Margarida", "Asteca", "Zênite", "Marte", "Vênus", "Orquídea", "Castanheira", "Cedro", "Eucalipto", "Araucária", "Junqueira", "Marfim", "Topázio", "Esmeralda", "Turquesa", "Rubí", "Zirconia", "Coral", "Sardônia", "Obsidiana", "Pedra-Sabão", "Flamingo", "Coruja", "Pardal", "Falcão", "Cisne", "Pelican", "Papagaio", "Beija-Flor", "Sabiá", "Andorinha", "Águia", "Corvo", "Condor", "Canela", "Gengibre", "Hortelã", "Manjericão", "Tomilho", "Alecrim", "Louro", "Orégano", "Sálvia", "Cometa", "Meteorito", "Galáxia", "Planeta", "Estrela", "Saturno", "Júpiter", "Mercúrio", "Netuno", "Urano", "Plutão", "Sol", "Lua", "Terra", "Ícaro", "Artemis", "Apolo", "Hércules", "Achiles", "Pandora", "Zeus", "Afrodite", "Athena", "Hermes", "Poseidon", "Deméter", "Cronos", "Dionísio", "Perseu", "Teseu", "Eros", "Nêmesis", "Anúbis", "Osíris", "Ísis", "Horus", "Thot", "Sekhmet", "Bastet", "Rá", "Maat", "Geb", "Nut", "Nephtys", "Seth", "Hator", "Mênfis", "Thebas", "Luxor", "Gizé", "Amon-Rá", "Sphinx", "Himalaia", "Everest", "Kilimanjaro", "Aconcágua", "Denali", "Elbrus", "Monte Rosa", "Vesúvio", "Fugi", "Ararat", "K2", "Monte Branco", "Atlas", "Andes", "Alpes", "Cáucaso", "Urais", "Karpatos", "Apalaches", "Sierra Nevada", "Tetons", "Ozarks", "Cordilheira", "Rocosa", "Taiga", "Tundra", "Savana", "Manguezal", "Pantanal", "Cerrado", "Mojave", "Sahara", "Kalahari", "Gobi", "Thar", "Amazônia", "Danúbio", "Nilo", "Mississipi", "Amazonas", "Yangtzé", "Ganges", "Reno", "Senegal", "Tâmisa", "Volga", "Mekong", "Loire", "Tigre", "Eufrates", "Paraná", "Colorado", "Fraser", "Rio Grande", "Pecos", "Sabine", "Yukon", "Churchill", "Orinoco", "Zambeze", "Níger", "Congo", "Tiete", "Paranapanema", "Doce", "São Francisco", "Madeira", "Purus", "Tapajós", "Xingu", "Juruá", "Guaporé", "Araguaia", "Tocantins", "Iguaçu", "Paraguai", "Uruguai", "Murray", "Darling", "Ebro", "Douro", "Guadalquivir", "Lena", "Obi", "Yenisei", "Indo", "Brahmaputra", "Amur", "Fraser", "Columbia", "Mackenzie", "Ródano", "Po", "Elba", "Oder", "Vístula", "Dniester", "Dnieper", "Don", "Pechora", "Onega", "Svir", "Volturno", "Arno", "Tejo", "Guadiana", "Duero", "Guadalquivir", "Ebro", "Douro", "Vouga", "Mondego", "Sado", "Mira", "Guadiana", "Tejo", "Dão", "Paiva", "Vouga", "Ave", "Cávado", "Lima", "Minho", "Neiva", "Âncora", "Coura", "Vez", "Lima", "Homem", "Cávado", "Ave", "Douro", "Tâmega", "Paiva", "Vouga", "Dão", "Mondego", "Ceira", "Zêzere", "Nabão", "Sertã", "Ocreza", "Ponsul", "Erges", "Sever", "Geiru", "Caia", "Xévora", "Degebe", "Sorraia", "Almansor", "Maior", "Trancão", "Içá", "Mira", "Sado", "Galé", "Coina", "Samouco", "Tejo", "Safarujo", "Jamor", "Lizandro", "Colares", "Falcão", "Magoito"
        ];
        $this->street = $this->util->selectItemOnArray($street);
    }

    /**
     * @return void
     */
    private function address(): void
    {
        $this->address = $this->logradouro . '.' . ' a';
    }

    private function bairro(): void
    {
        $bairros = [
            "Centro", "Jardim das Flores", "Vila Nova", "Bairro Alto", "São Francisco", "Morumbi", "Bela Vista", "Campos Elíseos", "Santa Tereza", "Copacabana", "Lapa", "Moema", "Liberdade", "Itaim Bibi", "Pinheiros", "Barra da Tijuca", "Vila Mariana", "Ipanema", "Santo Amaro", "Vila Madalena", "Botafogo", "Santana", "Tijuca", "Campo Belo", "Campo Grande", "São João", "Alto da Lapa", "Vila Olímpia", "Vila Leopoldina", "Cidade Jardim"
        ];
        $this->bairro = $this->util->selectItemOnArray($bairros);
    }

    private function country(): void
    {
        $country = [
            "Rússia", "Canadá", "Estados Unidos", "China", "Brasil", "Austrália", "Índia", "Argentina"
        ];
        $this->country = $this->util->selectItemOnArray($country);
    }

    //Get and Set methods.
    /**
     * @return string
     */
    public function getLogradouro(): string
    {
        return $this->logradouro;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        //TODO: Adicionar A, B, C etc ao número caso seja endereço completo.
        return $this->address;
    }

    /**
     * @return string
     */
    public function getBairro(): string
    {
        return $this->bairro;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}
