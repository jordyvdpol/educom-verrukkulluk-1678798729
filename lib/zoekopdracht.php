<?php

require_once("database.php");
require_once("artikel.php");
require_once("user.php");
require_once("keuken_type.php"); 
require_once("ingredient.php");
require_once("gerecht_info.php");
require_once("gerecht.php");
require_once("boodschappenlijst.php");

class zoeken {

    private $connection;
    private $gerecht;

    public function __construct($connection) {
        $this -> connection = $connection;
        $this -> gerecht = new gerecht($connection);
    }

    private function ophalenDataGerechten(){
        $data = $this -> gerecht -> selecteerGerechten();
        
        return($data);
    }

    public function zoekopdracht($searchTerm){

        $zoekData = $this -> ophalenDataGerechten();
        $searchTerm = $this->connection->real_escape_string($searchTerm);

        $zoekResultaat = [];
        foreach ($zoekData as $subArray) {
            $json = json_encode($subArray);
            if (strpos($json, $searchTerm) !== false) {
                $zoekResultaat[] = $subArray["titel"];
                // var_dump($subArray["titel"]);
                // die;
               
        }
    }
    // var_dump($zoekResultaat);
    // die;

        header('Content-Type: application/json');
        echo json_encode($zoekResultaat);
    }   
}

require_once("database.php");
$db = new database();
$gerecht = new gerecht($db -> getConnection());
$handler = new zoeken($db -> getConnection());

if (isset($_POST['searchTerm'])) {
    $handler->zoekopdracht($_POST['searchTerm']);
}

?>