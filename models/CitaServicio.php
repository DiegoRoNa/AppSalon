<?php 

namespace Model;

class CitaServicio extends ActiveRecord{
    //TABLA DE LA BD
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id', 'idCita', 'idServicios'];

    public $id;
    public $idCita;
    public $idServicios;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->idCita = $args['idCita'] ?? '';
        $this->idServicios = $args['idServicios'] ?? '';
    }
}
