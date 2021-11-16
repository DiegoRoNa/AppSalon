<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    //  7login
    public static function login(Router $router){

        //Errores
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST); //objeto de usuario con el correo y contraseña

            //VALIDAR EL FORMULARIO
            $alertas = $auth->validarLogin();

            //SI PASA LA VALIDACION
            if (empty($alertas)) {
                // VERIFICAR QUE EL USUARIO EXISTA, USANDO EL CORREO
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    //CONFIRMAR QUE LA CONTRASEÑA ES CORRECTA
                    if($usuario->comprobarPasswordAndVerficiado($auth->password)){
                        //AUTENTICAR AL USUARIO
                        //YA EXISTE LA SESION INICIADA DESDE Router.php

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre.' '.$usuario->apellidos;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //VERIFICAR SI ES ADMIN O CLIENTE Y REDIRIGIR RESPECTIVAMENTE
                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else {
                            header('Location: /cita');
                        }
                    }
                }else {
                    Usuario::setAlerta('error', 'Esta cuenta no existe, crea una');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }


    //  /logout
    public static function logout(){
        echo 'Desde logout';
    }


    //  /olvide
    public static function olvide(Router $router){

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST); //objeto de usuario con el correo

            //VALIDAR EL FORMULARIO
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                //VALIDAR QUE EL CORREO EXISTE
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado === '1') {
                    //GENERAR UN NUEVO TOKEN 
                    $usuario->crearToken();

                    //GUARDAR EL OBJETO EN LA BD
                    $usuario->guardar();

                    //ENVIAR UN EMAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Correcto, revisa tu email');

                }else {
                    Usuario::setAlerta('error', 'El correo es incorrecto o la cuenta no ha sido confirmada');
                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }


    //  /recuperar
    public static function recuperar(Router $router){

        $alertas = [];
        $error = false;

        //TOKEN DE LA URL
        $token = s($_GET['token']);

        //BUSCAR AL USUARIO EN LA BD POR EL TOKEN
        $usuario = Usuario::where('token', $token); //objeto del usuario

        if (empty($usuario)) {//si no existe el token
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //CREAR LA NUEVA CONTRASEÑA Y GUARDARLA

            //TOMAR EL VALOR
            $password = new Usuario($_POST);

            //VALIDAR EL FORMULARIO DE NUEVO PASSWORD
            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                //BORRAMOS EL PASSWORD DE LA BD
                $usuario->password = null;

                //ASIGNAMOS EL NUEVO PASSWORD
                $usuario->password = $password->password;

                //HASHEAR EL PASSWORD
                $usuario->hashPassword();

                //BORRAR EL TOKEN CREADO
                $usuario->token = null;

                //GUARDAR EN LA BD
                $resultado = $usuario->guardar();

                if ($resultado) {
                    header('Location: /');
                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }


    //  /crear-cuenta
    public static function crear(Router $router){

        $usuario = new Usuario;

        //ALERTA DE ERRORES
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //REVISAR QUE LAS ALERTAS DE ERRORES ESTÉ VACIP
            if (empty($alertas)) {
                //VERIFICAR QUE EL USUARIO NO EXISTE, ATRAVÉS DEL CORREO
                $resultado = $usuario->existeUsuario();

                //EN CASO DE QUE EXISTA EL USUARIO
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else {
                    //EN CASO DE QUE NO EXISTA EL USUARIO

                    //HASHEAR LA CONTRASEÑA
                    $usuario->hashPassword();

                    //GENERAR EL TOKEN UNICO
                    $usuario->crearToken();

                    //ENVIAR EL EMAIL DE CONFIRMACIÓN
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);//datos para el email
                    $email->enviarConfirmacion();

                    //CREAR EL USUARIO
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }

                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    //  /mensaje
    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }


    //  /confirmar
    public static function confirmar(Router $router){

        $alertas = [];

        //LEER EL TOKEN DE LA URL
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);//usuario con su token

        if (empty($usuario)) {
            //MANDAR UN MENSAJE DE ERROR SI EL USUARIO CON ESE TOKEN NO EXISTE
            Usuario::setAlerta('error', 'Token no válido');
        }else {
            //MODIFICAR EL USUARIO, EN LA COLUMNA confirmado
            $usuario->confirmado = '1'; //confirmado actualizado en el objeto Usuario
            $usuario->token = null; //Token eliminado en el objeto Usuario

            //ACTUALIZAR EL USUARIO EN LA DB
            $usuario->guardar();

            //MANDAR MENSAJE DE ÉXITO
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}


