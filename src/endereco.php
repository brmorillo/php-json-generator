<?php

namespace Rmorillo\JsonGenerator;

class endereco
{
    private Util $util;
    private $endereco, $logradouro, $street;
    public function __construct()
    {
        $this->util = new Util();
        $this->logradouro();
        $this->street();
        $this->endereco();
    }

    private function logradouro(): void
    {
        $logradouroList = [
            "Rua", "Avenida", "Travessa", "Beco", "Viela", "Alameda", "Praça", "Calçadão", "Largo", "Boulevard", "Rodovia", "Estrada", "Caminho", "Servidão", "Passagem", "Corredor", "Parque", "Jardim", "Via Expressa", "Viaduto", "Túnel", "Ponte", "Cais", "Porto", "Aeroporto",
            // Logradouros Rurais
            "Estrada de Terra", "Caminho de Servidão", "Trilha", "Sítio", "Fazenda", "Vale", "Morro", "Montanha", "Planalto", "Planície", "Cânion"
        ];
        $this->logradouro = $this->util->selectItemOnArray($logradouroList);
    }

    private function street(): void
    {
        $street = [
            "Amoroso", "Safira", "Esperança", "Girassol", "Margarida", "Asteca", "Zênite", "Marte", "Vênus", "Orquídea", "Castanheira", "Cedro", "Eucalipto", "Araucária", "Junqueira", "Marfim", "Topázio", "Esmeralda", "Turquesa", "Rubí", "Zirconia", "Coral", "Sardônia", "Obsidiana", "Pedra-Sabão", "Flamingo", "Coruja", "Pardal", "Falcão", "Cisne", "Pelican", "Papagaio", "Beija-Flor", "Sabiá", "Andorinha", "Águia", "Corvo", "Condor", "Canela", "Gengibre", "Hortelã", "Manjericão", "Tomilho", "Alecrim", "Louro", "Orégano", "Sálvia", "Cometa", "Meteorito", "Galáxia", "Planeta", "Estrela", "Saturno", "Júpiter", "Mercúrio", "Netuno", "Urano", "Plutão", "Sol", "Lua", "Terra", "Ícaro", "Artemis", "Apolo", "Hércules", "Achiles", "Pandora", "Zeus", "Afrodite", "Athena", "Hermes", "Poseidon", "Deméter", "Cronos", "Dionísio", "Perseu", "Teseu", "Eros", "Nêmesis", "Anúbis", "Osíris", "Ísis", "Horus", "Thot", "Sekhmet", "Bastet", "Rá", "Maat", "Geb", "Nut", "Nephtys", "Seth", "Hator", "Mênfis", "Thebas", "Luxor", "Gizé", "Amon-Rá", "Sphinx", "Himalaia", "Everest", "Kilimanjaro", "Aconcágua", "Denali", "Elbrus", "Monte Rosa", "Vesúvio", "Fugi", "Ararat", "K2", "Monte Branco", "Atlas", "Andes", "Alpes", "Cáucaso", "Urais", "Karpatos", "Apalaches", "Sierra Nevada", "Tetons", "Ozarks", "Cordilheira", "Rocosa", "Taiga", "Tundra", "Savana", "Manguezal", "Pantanal", "Cerrado", "Mojave", "Sahara", "Kalahari", "Gobi", "Thar", "Amazônia", "Danúbio", "Nilo", "Mississipi", "Amazonas", "Yangtzé", "Ganges", "Reno", "Senegal", "Tâmisa", "Volga", "Mekong", "Loire", "Tigre", "Eufrates", "Paraná", "Colorado", "Fraser", "Rio Grande", "Pecos", "Sabine", "Yukon", "Churchill", "Orinoco", "Zambeze", "Níger", "Congo", "Tiete", "Paranapanema", "Doce", "São Francisco", "Madeira", "Purus", "Tapajós", "Xingu", "Juruá", "Guaporé", "Araguaia", "Tocantins", "Iguaçu", "Paraguai", "Uruguai", "Murray", "Darling", "Ebro", "Douro", "Guadalquivir", "Lena", "Obi", "Yenisei", "Indo", "Brahmaputra", "Amur", "Fraser", "Columbia", "Mackenzie", "Ródano", "Po", "Elba", "Oder", "Vístula", "Dniester", "Dnieper", "Don", "Pechora", "Onega", "Svir", "Volturno", "Arno", "Tejo", "Guadiana", "Duero", "Guadalquivir", "Ebro", "Douro", "Vouga", "Mondego", "Sado", "Mira", "Guadiana", "Tejo", "Dão", "Paiva", "Vouga", "Ave", "Cávado", "Lima", "Minho", "Neiva", "Âncora", "Coura", "Vez", "Lima", "Homem", "Cávado", "Ave", "Douro", "Tâmega", "Paiva", "Vouga", "Dão", "Mondego", "Ceira", "Zêzere", "Nabão", "Sertã", "Ocreza", "Ponsul", "Erges", "Sever", "Geiru", "Caia", "Xévora", "Degebe", "Sorraia", "Almansor", "Maior", "Trancão", "Içá", "Mira", "Sado", "Galé", "Coina", "Samouco", "Tejo", "Safarujo", "Jamor", "Lizandro", "Colares", "Falcão", "Magoito"
        ];
        $this->street = $this->util->selectItemOnArray($street);
    }

    private function endereco(): void
    {
        $this->endereco = $this->logradouro . " ";
    }

    //Get and Set methods.
    public function getStreet(): string
    {
        return $this->street;
    }

    public function getLogradouro(): string
    {
        return $this->logradouro;
    }

    public function getEndereco(): string
    {
        return $this->endereco;
    }
}
