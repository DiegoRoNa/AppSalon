<?php 

namespace Controllers;

use MVC\Router;

class CitaController{
    public static function index(Router $router){

        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAuth();

        $id = $_SESSION['id']; //este id lo mandamos para recogerlo desde JS para la tabla citas
        $nombre = $_SESSION['nombre'];

        $router->render('cita/index', [
            'id' => $id,
            'nombre' => $nombre
        ]);
    }
}
