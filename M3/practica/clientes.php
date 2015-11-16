<?php

$json = file_get_contents ('clientes.json');
$clientes = json_decode ($json, true);

if (! (isset ($_GET ['filtro']) && isset ($_GET ['desde']) && isset ($_GET ['cantidad']))) {
    if (isset ($_GET ['filtro']) && $_GET ['filtro'] == 'impar') {
        sleep (3);
    }
    header ('HTTP/1.1 401 Unauthorized');
    exit();
}

$filtro = $_GET ['filtro'];
$desde = $_GET ['desde'];
$cantidad = $_GET ['cantidad'];

if (! in_array ($filtro, array ('impar', 'par')) ||
    ! is_numeric ($desde) || $desde < 1 /* || $desde > count($clientes) */ ||
    ! is_numeric ($cantidad) || $cantidad < 0 || $cantidad > count($clientes)
) {
    if ($filtro == 'impar') {
        sleep (3);
    }
    header ('HTTP/1.1 401 Unauthorized');
    exit();
}

$db = @new mysqli ('localhost', 'root', '', 'cursoweb');
if ($db -> connect_error) {
    if ($filtro == 'impar') {
        sleep (3);
    }
    header ('HTTP/1.1 500 Internal Server Error');
    exit();
}

$paridad = $filtro == 'impar' ? 1 : 0;
$query = "SELECT
    id,
    razon_social,
    logo,
    direccion_calle,
    direccion_altura,
    direccion_ciudad,
    activo,
    pasivo,
    presidente_nombre,
    presidente_apellido
  FROM clientes WHERE id >= {$desde} AND id % 2 = {$paridad} LIMIT {$cantidad}
  ;";
$resultado = $db -> query ($query);
if ($db -> error && $db -> errno != 1051) {
    if ($filtro == 'impar') {
        sleep (3);
    }
    header ('HTTP/1.1 500 Internal Server Error');
    exit();
}

$db -> close();

class Presidente {
    public $nombre;
    public $apellido;

    public function __construct ($nombre, $apellido) {
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
    }
}

class Direccion {
    public $calle;
    public $altura;
    public $ciudad;

    public function __construct ($calle, $altura, $ciudad) {
        $this -> calle = $calle;
        $this -> altura = $altura;
        $this -> ciudad = $ciudad;
    }
}

class Cliente {
    public $id;
    public $razonSocial;
    public $logo;
    public $direccion;
    public $activo;
    public $pasivo;
    public $presidente;

    public function __construct ($id, $razonSocial, $logo, $direccionCalle, $direccionAltura, $direccionCiudad,
                                 $activo, $pasivo, $presidenteNombre, $presidenteApellido) {
        $this -> id = $id;
        $this -> razonSocial = $razonSocial;
        $this -> logo = $logo;
        $this -> direccion = new Direccion ($direccionCalle, $direccionAltura, $direccionCiudad);
        $this -> activo = $activo;
        $this -> pasivo = $pasivo;
        $this -> presidente = new Presidente ($presidenteNombre, $presidenteApellido);
    }
}

// si ningun cliente satisface los inputs, devuelve un array vacio []
$res = array();
for ($i = 0; $i < $resultado -> num_rows; $i++) {
    $o = $resultado -> fetch_object();
    $res[] = new Cliente ($o -> id, $o -> razon_social, $o -> logo, $o -> direccion_calle, $o -> direccion_altura,
        $o -> direccion_ciudad, $o -> activo, $o -> pasivo, $o -> presidente_nombre, $o -> presidente_apellido);
}

if ($filtro == 'impar') {
    sleep (3);
}
echo json_encode ($res);