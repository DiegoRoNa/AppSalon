<?php 

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
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

        //ALMACENAR EN LA TABLA citasServicios
        //ID de la cita creada
        $id = $resultado['id'];

        //extraemos los id de los servicios, en un array
        $idServicios = explode(',', $_POST['servicios']);//lo que llega del frontend

        //recorremos el array, para crear un registro por cada cita o servicio
        foreach ($idServicios as $idServicio) {
            $args = [
                'idCita' => $id,
                'idServicios' => $idServicio
            ];

            //INSTANCIAMOS EL MODELO, Y LE PASAMOS LOS DATOS A SU CONTRUCTOR
            $citaServicio = new CitaServicio($args);

            //ALMACENAMOS EN LA BD
            $citaServicio->guardar();
        }

        //RETORNAMOS UNA RESPUESTA
        echo json_encode(['resultado' => $resultado]);
    }


    //  /api/eliminar
    public static function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //id de la cita
            $id = $_POST['id'];

            //buscamos la cita en la BD y devuelve el objeto completo
            $cita = Cita::find($id);

            //eliminamos la cita
            $cita->eliminar();

            //redireccionamos
            header('Location:' .$_SERVER['HTTP_REFERER']);
        }
    }
}
