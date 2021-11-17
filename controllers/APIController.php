<?php 

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class APIController{

    //  /api/servicios
    public static function index(){

        //CONSULTAMOS TODOS LOS SERVICIOS EN LA BD
        $servicios = Servicio::all();

        //DEVOLVEMOS EL ARREGLO DE RESULTADOS EN UN JSON
        echo json_encode($servicios);

    }
}
