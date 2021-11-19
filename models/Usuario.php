<?php 

namespace Model;

class Usuario extends ActiveRecord{

    //TABLA DE LA BD
    protected static $tabla = 'usuarios';
    //MAPEAR LA TABLA PARA ARMAR EL MODELO
    protected static $columnasDB = ['id', 'nombre', 'apellidos', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];
    
    //ATRIBUTOS
    public $id;
    public $nombre;
    public $apellidos;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //MENSAJESDE VALIACIÓN PARA LA CREACION DE UNA CUENTA
    public function validarNuevaCuenta(){
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if (!$this->apellidos) {
            self::$alertas['error'][] = 'Los apellidos son obligatorios';
        }

        if (!$this->telefono) {
            self::$alertas['error'][] = 'El teléfono es obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener mínimo 6 caracteres';
        }

        return self::$alertas;
    }


    //VALIDAR EL FORMULARIO DE LOGIN
    public function validarLogin(){
        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }


    //VALIDAR EL FORMULARIO DE PASSWORD OLVIDADO 
    public function validarEmail(){
        if (!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        return self::$alertas;
    }


    //VALIDAR EL FORMULARIO DEL NUEVO PASSWORD
    public function validarPassword(){
        if (!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe tener mínimo 6 caracteres';
        }

        return self::$alertas;
    }


    //CONSULTAR SI EL USUARIO EXISTE
    public function existeUsuario(){
        //consulta
        $query = 'SELECT * FROM ' . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        //ejecutamos consulta
        $resultado = self::$db->query($query);

        //si devuelve un registro
        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'Este usuario ya existe';
        }

        //retornamos el resultado
        return $resultado;
    }


    //HASHEAR LA CONTRASEÑA
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }


    //GENEAR TOKEN
    public function crearToken(){
        $this->token = uniqid();
    }


    //VERIFICAR QUE LA CONTRASEÑA EXISTE Y ESTE CONFIRMADO EL USUARIO
    public function comprobarPasswordAndVerficiado($password){
        $resultado = password_verify($password, $this->password);//comparar password

        //verificar la confirmacion
        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Contraseña incorrecta o tu cuenta no ha sido confirmada';
        }else {
            return true;
        }
    }


    
}
