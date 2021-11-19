<?php 

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function index(Router $router){
        //YA HAY UNA SESION INICIADA DESDE Router.php

        //VERIFICAR QUE ESTE AUTENTICADO EL USUARIO
        isAdmin();
        $nombre = $_SESSION['nombre'];

        $fecha = $_GET['fecha'] ?? date('Y-m-d'); //FECHA DE LA URL, SI NO HAY, TOMA LA DEL SERVIDOR

        //separamos el string de la fecha en un array
        $fechas = explode('-', $fecha);

        //checkdate: devuelve true o false si existe una fecha o no
        if ( !checkdate( $fechas[1], $fechas[2], $fechas[0] ) ) {
            header('Location: /404');
        }

        //HACER LA CONSULTA A LA BD
        $consulta = "SELECT c.id, c.hora, CONCAT(u.nombre, ' ', u.apellidos) cliente, ";
        $consulta .= " u.email, u.telefono, s.nombre as servicio, s.precio  ";
        $consulta .= " FROM citas c ";
        $consulta .= " LEFT OUTER JOIN usuarios u ";
        $consulta .= " ON u.id = c.idUsuario ";
        $consulta .= " LEFT OUTER JOIN citasservicios cs ";
        $consulta .= " ON cs.idCita = c.id ";
        $consulta .= " LEFT OUTER JOIN servicios s ";
        $consulta .= " ON s.id = cs.idServicios ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        //EJECUTAR LA CONSULTA CON TODAS LAS CITAS
        $citas = AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $nombre,
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}
