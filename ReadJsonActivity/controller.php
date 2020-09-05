<?php
 error_reporting(E_ALL & ~E_WARNING);
require_once("Activity.php");
/**
 * Controller
 *
 * @author Giuse
 */

if(!empty($_POST['ope'])){
    
    $recordActivity ;
    $File_Json="https://dati.regione.campania.it/catalogo/resources/Elenco-Farmacie.geojson";
    switch (strtolower(trim($_POST['ope']))) {
        case "read_json":
            $File_Json = json_decode(file_get_contents($File_Json));
            $i=0;
            foreach ($File_Json->features as $record){
                $recordActivity = new Activity($record->type,$record->properties->Descrizione , $record->geometry->coordinates[1], $record->geometry->coordinates[0]);
                $JSON[$i++]= $recordActivity->getJson(substr($_REQUEST['lat'], 0,8), substr($_REQUEST['lon'], 0,8));
            }
            echo json_encode($JSON);
            //var_dump($JSON->features);
            break;
    }
    
    
}