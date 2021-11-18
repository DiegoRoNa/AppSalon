<?php 

namespace Controllers;

use Model\Cita;
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

    //  /api/citas
    public static function guardar(){

        //GENERAMOS EL NUEVO OBJETO cita CON LO QUE LLEGA DESDE EL FRONTEND "function reservarCita()"
        $cita = new Cita($_POST);

        //GUARDAMOS LOS DATOS
        $resultado = $cita->guardar();

        //
        echo json_encode($resultado);
    }
}
