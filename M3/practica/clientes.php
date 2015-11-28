<?php

abstract class AutoLlenable {
    protected $mapper = array();

    public function __construct($datos = array()) {
        foreach ($datos as $key => $value) {
            if (isset($this->mapper[$key])) {
                $key = $this->mapper[$key];
            }
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}

class Cliente extends AutoLlenable {
    public $id;
    public $razonSocial;
    public $logo;
    public $direccion;
    public $activo;
    public $pasivo;
    public $presidente;
    public $fechaCreacion;

    protected $mapper = array(
        'razon_social' => 'razonSocial',
        'fecha_creacion' => 'fechaCreacion'
    );

    public function __construct($datos) {
        parent::__construct($datos);

        $this->direccion = new Direccion(
            array(
                'direccion_calle' => $datos['direccion_calle'],
                'direccion_altura' => $datos['direccion_altura'],
                'direccion_ciudad' => $datos['direccion_ciudad'],
                'direccion_pais' =>  $datos['direccion_pais']
            )
        );
        $this->presidente = new Persona(
            array(
                'presidente_nombre' => $datos['presidente_nombre'],
                'presidente_apellido' =>  $datos['presidente_apellido']
            )
        );
    }

    public static function buscar(mysqli $db, $cantidad, $desde, $filtro) {
        $q = 'SELECT * FROM clientes WHERE id >= ' . $desde . ' AND id % 2 = ' . ($filtro == 'par' ? 0 : 1) . ' LIMIT ' . $cantidad;
        $listaDeClientes = $db->query($q)->fetch_all(MYSQLI_ASSOC);

        $arrayFinal = array();
        foreach ($listaDeClientes as $item) {
            $cliente = new Cliente($item);
            $arrayFinal[] = $cliente;
        }
        return $arrayFinal;
    }

    /**
     * @param mysqli $db
     * @param        $id
     *
     * @return null|Cliente
     */
    public static function buscarPorId(mysqli $db, $id) {
        $cliente = Cliente::buscar($db, 1, $id, $id % 2 ? 'impar' : 'par');
        return count($cliente) ? $cliente[0] : null;
    }

    public function borrar(mysqli $db) {
        $q = 'DELETE FROM clientes WHERE id = ' . $this->id . ';';
        $db->query($q);
        // TODO chequear errores
    }

    public function agregar(){
        $db = new mysqli('localhost','root','','cursoweb');
        $q = 'INSERT INTO clientes (razon_social, logo, direccion_calle, direccion_altura, direccion_ciudad, direccion_pais, activo, pasivo, presidente_nombre, presidente_apellido, fecha_creacion
    ) VALUES ('.$this->razonSocial.','.$this->logo.','.$this->direccion->calle.','.$this->direccion->altura.','.$this->direccion->ciudad.','.$this->direccion->pais.','.$this->activo.','.$this->pasivo.','.$this->presidente->nombre.','.$this->presidente->apellido.','.','.$this->fechaCreacion.')';
        $db->query($q);
    }
}

class Direccion extends AutoLlenable {
    public $calle;
    public $altura;
    public $ciudad;
    public $pais;

    protected $mapper = array(
        'direccion_calle' => 'calle',
        'direccion_altura' => 'altura',
        'direccion_ciudad' => 'ciudad',
        'direccion_pais' =>  'pais'
    );
}

class Persona extends AutoLlenable {
    public $nombre;
    public $apellido;

    protected $mapper = array(
        'presidente_nombre' => 'nombre',
        'presidente_apellido' =>  'apellido'
    );
}

class ClienteController {
    public function get() {
        $desde = (int) $_GET['desde'];
        $cantidad = (int) $_GET['cantidad'];
        $filtro = $_GET['filtro'];

        if (!$desde || !$cantidad || !in_array($filtro, array('par', 'impar'))) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(
                array('code' => 400, 'message' => 'No se recibieron correctamente los parámetros "desde", "cantidad" y "filtro"')
            );
            exit();
        }

        echo json_encode(Cliente::buscar($this->getDb(), $cantidad, $desde, $filtro));
    }
    public function post() {
        $datos = array(
            "razonSocial" => $_POST['razonSocial'],
            "direccion_calle" => $_POST['calle'],
            "direccion_altura" => $_POST['altura'],
            "direccion_ciudad" => $_POST['ciudad'],
            "direccion_pais" => $_POST['pais'],
            "presidente_nombre" => $_POST['pres_nombre'],
            "presidente_apellido" => $_POST['pres_apellido'],
            "id" => "",
            "logo" => $_POST['logo'],
            "activo" => $_POST['activo'],
            "pasivo" => $_POST['pasivo'],
            "fechaCreacion" => date(('Y-m-d H:i:s'))
        );

        $cliente = new Cliente($datos);
        $cliente->agregar();
    }
    public function delete() {
        parse_str(file_get_contents('php://input'), $_DELETE);

        $cliente = Cliente::buscarPorId($this->getDb(), $_DELETE['id']);
        if ($cliente) {
            $cliente->borrar($this->getDb());
        } else {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(
                array('code' => 404, 'message' => 'No se encontraron clientes con ese id')
            );
        }
    }
    private function getDb() {
        return new mysqli('localhost', 'root', '', 'cursoweb');
    }
}

// El request ARRANCA acá

header('Content-Type: application/json');

$handler = new ClienteController();

$metodo = strtolower($_SERVER['REQUEST_METHOD']);
if (method_exists($handler, $metodo)) {
    $handler->$metodo();
} else {
    header('HTTP/1.1 400 Bad Request');

    echo json_encode(
        array('code' => 400, 'message' => 'No existe el método "' . $metodo . '"')
    );
}
