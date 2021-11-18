<?php 

namespace Model;

class Cita extends ActiveRecord{
    //TABLA DE LA BD
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'idUsuario', 'fecha', 'hora'];

    public $id;
    public $idUsuario;
    public $fecha;
    public $hora;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->idUsuario = $args['idUsuario'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
    }
}
