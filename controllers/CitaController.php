<?php 

namespace Controllers;

use MVC\Router;

class CitaController{
    public static function index(Router $router){

        //YA HAY UNA SESION INICIADA DESDE Router.php

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre']
        ]);
    }
}
