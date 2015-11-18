<?php

class Presidente
{
    public $nombre;
    public $apellido;

    public function __construct ($nombre, $apellido)
    {
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
    }
}

class Direccion
{
    public $calle;
    public $altura;
    public $ciudad;
    public $pais;

    public function __construct ($calle, $altura, $ciudad, $pais)
    {
        $this -> calle = $calle;
        $this -> altura = $altura;
        $this -> ciudad = $ciudad;
        if (isset ($pais)) {
            $this -> pais = $pais;
        } else {
            unset ($this -> pais);
        }
    }
}

class Cliente
{
    public $id;
    public $razonSocial;
    public $logo;
    public $direccion;
    public $activo;
    public $pasivo;
    public $presidente;
    public $fechaCreacion;

    private function _prepararParaGET ($id, $razonSocial, $logo,
                                       $direccionCalle, $direccionAltura, $direccionCiudad,
                                       $activo, $pasivo, $presidenteNombre, $presidenteApellido) {
        $this -> id = $id;
        $this -> razonSocial = $razonSocial;
        $this -> logo = $logo;
        $this -> direccion = new Direccion ($direccionCalle, $direccionAltura, $direccionCiudad, null);
        $this -> activo = $activo;
        $this -> pasivo = $pasivo;
        $this -> presidente = new Presidente ($presidenteNombre, $presidenteApellido);
        unset ($this -> fechaCreacion);
    }

    private function _prepararParaPOST ($razonSocial, $logo,
                                        $direccionCalle, $direccionAltura, $direccionCiudad, $direccionPais,
                                        $activo, $pasivo, $presidenteNombre, $presidenteApellido, $fechaCreacion) {
        unset ($this -> id);
        $this -> razonSocial = $razonSocial;
        $this -> logo = $logo;
        $this -> direccion = new Direccion ($direccionCalle, $direccionAltura, $direccionCiudad, $direccionPais);
        $this -> activo = $activo;
        $this -> pasivo = $pasivo;
        $this -> presidente = new Presidente ($presidenteNombre, $presidenteApellido);
        $this -> fechaCreacion = $fechaCreacion;
    }

    private function _prepararParaDELETE ($id) {
        $this -> id = $id;
        unset ($this -> razonSocial);
        unset ($this -> logo);
        unset ($this -> direccion);
        unset ($this -> activo);
        unset ($this -> pasivo);
        unset ($this -> presidente);
        unset ($this -> fechaCreacion);
    }

    private function _conectar () {

        $db = @new mysqli ('localhost', 'root', '', 'cursoweb');
        if ($db -> connect_error) {
            return false;
        }

        return $db;
    }

    private function _consultar (mysqli $db, $query) {

        $db -> query ($query);
        if ($db -> error) {
            $db -> close();
            return false;
        }

        $db -> close();

        return true;
    }

    public function guardar() {

        if (! $db = $this -> _conectar() ) {
            return false;
        }

        $query = "INSERT INTO clientes VALUES ( null,
                                                '{$this -> razonSocial}',
                                                '{$this -> logo}',
                                                '{$this -> direccion -> calle}',
                                                '{$this -> direccion -> altura}',
                                                '{$this -> direccion -> ciudad}',
                                                '{$this -> direccion -> pais}',
                                                '{$this -> activo}',
                                                '{$this -> pasivo}',
                                                '{$this -> presidente -> nombre}',
                                                '{$this -> presidente -> apellido}',
                                                '{$db -> real_escape_string ($this -> fechaCreacion)}' );";

        if (! $this -> _consultar ($db, $query) ) {
            return false;
        }

        return true;
    }

    public function borrar() {

        if (! $db = $this -> _conectar() ) {
            return false;
        }

        $query = "DELETE FROM clientes WHERE id = {$this -> id} ;";

        if (! $this -> _consultar ($db, $query) ) {
            return false;
        }

        return true;
    }

    public function __construct ($id, $razonSocial, $logo,
                                 $direccionCalle, $direccionAltura, $direccionCiudad, $direccionPais,
                                 $activo, $pasivo, $presidenteNombre, $presidenteApellido, $fechaCreacion)
    {
        if (isset ($id) && ! isset ($razonSocial)) {

            $this -> _prepararParaDELETE ( $id );

        } elseif (isset ($id) && isset ($razonSocial)) {

            $this -> _prepararParaGET ( $id, $razonSocial, $logo,
                                        $direccionCalle, $direccionAltura, $direccionCiudad,
                                        $activo, $pasivo, $presidenteNombre, $presidenteApellido );
        } elseif (! isset ($id)) {

            $this -> _prepararParaPOST ( $razonSocial, $logo,
                                         $direccionCalle, $direccionAltura, $direccionCiudad, $direccionPais,
                                         $activo, $pasivo, $presidenteNombre, $presidenteApellido, $fechaCreacion );
        }
    }
}
