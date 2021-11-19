<?php 

namespace Controllers;

use MVC\Router;

class ServicioController{

    //  /servicios
    public static function index(Router $router){

        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAdmin();
        $nombre = $_SESSION['nombre'];

        $router->render('servicios/index', [
            'nombre' => $nombre
        ]);
    }

    public static function crear(Router $router){
        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAdmin();
        $nombre = $_SESSION['nombre'];

        

        $router->render('servicios/crear', [
            'nombre' => $nombre
        ]);
    }

    public static function actualizar(Router $router){
        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAdmin();
        $nombre = $_SESSION['nombre'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            # code...
        }

        $router->render('servicios/actualizar', [
            'nombre' => $nombre
        ]);
    }

    public static function eliminar(Router $router){
        echo 'desde eliminar';
    }
}
