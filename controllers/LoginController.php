<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){

        $router->render('auth/login');
    }

    public static function logout(){
        echo 'Desde logout';
    }

    public static function olvide(Router $router){
        $router->render('auth/olvide-password');
    }

    public static function recuperar(){
        echo 'Desde recuperar';
    }

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


    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }
}


