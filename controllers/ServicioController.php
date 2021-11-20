<?php 

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController{

    //  /servicios
    public static function index(Router $router){

        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAdmin();
        $nombre = $_SESSION['nombre'];

        $alertas = [];

        //TRAEMOS TODOS LOS SERVICIOS DE LA BD
        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'nombre' => $nombre,
            'servicios' => $servicios,
            'alertas' => $alertas
        ]);
    }

    //  /servicios/crear
    public static function crear(Router $router){
        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAdmin();
        $nombre = $_SESSION['nombre'];

        $servicio = new Servicio;
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //sincronizamos el modelo con el objeto en memoria(POST), para que no se borre lo que escribio el usuario en caso de error
            $servicio->sincronizar($_POST);

            //VALIDAMOS EL FORMULARIO
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                //GUARDAMOS EN LA BD
                $servicio->guardar();

                //REDIRECCIONAMOS
                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $nombre,
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    //  /servicios/actualizar
    public static function actualizar(Router $router){
        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAdmin();
        $nombre = $_SESSION['nombre'];

        //ID por get, validar que es INT
        $id = $_GET['id'];
        if(!is_numeric($id)) return;

        //traemos el servicio de la BD
        $servicio = Servicio::find($id);

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //sincronizamos el modelo con el objeto en memoria(POST), para que no se borre lo que escribio el usuario en caso de error
            $servicio->sincronizar($_POST);

            //VALIDAMOS EL FORMULARIO
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                //GUARDAMOS EN LA BD
                $servicio->guardar();

                //REDIRECCIONAMOS
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $nombre,
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    //  /servicios/eliminar
    public static function eliminar(){
        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //id del servicio
            $id = $_POST['id'];

            //traemos el servicio de la BD
            $servicio = Servicio::find($id);

            //eliminamos de la BD
            $servicio->eliminar();

            //redireccionamos
            header('Location: /servicios');
        }
    }
}
