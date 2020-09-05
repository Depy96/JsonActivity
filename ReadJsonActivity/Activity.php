<?php

/**
 * Description of Activity (MODEL)
 *
 * @author Giuse
 */
class Activity {

    private $type = "";
    private $name = "";
    private $cood1 = 0;
    private $cood2 = 0;

    //costruttore
    public function __construct($type, $name, $cood1, $cood2) {
        $this->type = $type;
        $this->name = $name;
        $this->cood1 = $cood1;
        $this->cood2 = $cood2;
    }

    //metodi
    public function getJson($lat,$lon) {
        $myObj->name = $this->getName();
        $myObj->type = $this->getType();
        $myObj->cood1 = $this->getCood1();
        $myObj->cood2 = $this->getCood2();
        $myObj->distance = $this->getDistance($lat,$lon);

        $myJSON = json_encode($myObj);
        return $myJSON;
    }

    public function getDistance($lat,$lon) {

            $latitude1 = $this->getCood1() ;
            $longitude1 = $this->getCood2() ;
            $latitude2 = $lat;
            $longitude2 = $lon;

            $theta = $longitude1 - $longitude2;
            $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
            $miles = acos($miles);
            $miles = rad2deg($miles);
            $miles = $miles * 60 * 1.1515;
            $feet = $miles * 5280;
            $yards = $feet / 3;
            $kilometers = $miles * 1.609344;
            $meters = explode(".", ($kilometers * 1000));
            
            return $meters[0];  

    }

    public function getName() {
        return ucwords(strtolower(trim($this->name)));
    }

    public function getCood1() {
        return $this->cood1;
    }

    public function getCood2() {
        return $this->cood2;
    }

    public function getType() {
        return $this->type;
    }

}
