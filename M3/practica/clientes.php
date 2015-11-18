<?php

if (empty ($_SERVER ['REQUEST_METHOD'])) {
    header ('HTTP/1.1 401 Unauthorized');
    exit();
}

$method = $_SERVER ['REQUEST_METHOD'];

if (in_array ($method, array ('PUT', 'DELETE') )) {
    $params = null;
    parse_str (file_get_contents ('php://input'), $params);
    // Add these request vars into _REQUEST, mimicing default behavior, PUT/DELETE will override existing COOKIE/GET vars
    $_REQUEST = $params + $_REQUEST;
}

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

    public function __construct ($id, $razonSocial, $logo,
                                 $direccionCalle, $direccionAltura, $direccionCiudad, $direccionPais,
                                 $activo, $pasivo, $presidenteNombre, $presidenteApellido, $fechaCreacion)
    {
        if (isset ($id)) {
            $this -> id = $id;
        } else {
            unset ($this -> id);
        }
        $this -> razonSocial = $razonSocial;
        $this -> logo = $logo;
        $this -> direccion = new Direccion ($direccionCalle, $direccionAltura, $direccionCiudad, isset ($direccionPais) ?: null);
        $this -> activo = $activo;
        $this -> pasivo = $pasivo;
        $this -> presidente = new Presidente ($presidenteNombre, $presidenteApellido);
        if (isset ($fechaCreacion)) {
            $this -> fechaCreacion = $fechaCreacion;
        } else {
            unset ($this -> fechaCreacion);
        }
    }
}

if ($method == 'GET') {

    if (empty ($_GET ['filtro']) || empty ($_GET ['desde']) || ! isset ($_GET ['cantidad']) ) {
        if (empty ($_GET ['filtro']) || $_GET ['filtro'] == 'impar') {
            sleep(3);
        }
        header ('HTTP/1.1 401 Unauthorized');
        exit();
    }

    $filtro = $_GET ['filtro'];
    $desde = $_GET ['desde'];
    $cantidad = $_GET ['cantidad'];

    if (! in_array ($filtro, array ('impar', 'par')) ||
        ! is_numeric ($desde) || $desde < 1 ||
        ! is_numeric ($cantidad) || $cantidad < 0
    ) {
        if ($filtro == 'impar') {
            sleep(3);
        }
        header ('HTTP/1.1 401 Unauthorized');
        exit();
    }

    if ($cantidad == 0) {
        if ($filtro == 'impar') {
            sleep(3);
        }
        echo '[]';
        exit();
    }

    $db = @new mysqli ('localhost', 'root', '', 'cursoweb');
    if ($db -> connect_error) {
        if ($filtro == 'impar') {
            sleep(3);
        }
        header ('HTTP/1.1 500 Internal Server Error');
        exit();
    }

    /*
    $query = 'SELECT COUNT(id) FROM clientes;';
    $resultado = $db -> query ($query);
    if ($db -> error) {
        $db -> close();
        if ($filtro == 'impar') {
            sleep (3);
        }
        header ('HTTP/1.1 500 Internal Server Error');
        exit();
    }
    
    if ($cantidad > $resultado -> fetch_assoc() ['COUNT(id)']) {
    
    }
    */

    $paridad = $filtro == 'impar' ? 1 : 0;
    $query = "SELECT id,
                     razon_social,
                     logo,
                     direccion_calle,
                     direccion_altura,
                     direccion_ciudad,
                     activo,
                     pasivo,
                     presidente_nombre,
                     presidente_apellido
              FROM clientes WHERE id >= {$desde} AND id % 2 = {$paridad} LIMIT {$cantidad} ;";
    $resultado = $db -> query($query);
    if ($db -> error) {
        $db -> close();
        if ($filtro == 'impar') {
            sleep(3);
        }
        header ('HTTP/1.1 500 Internal Server Error');
        exit();
    }

    $db -> close();

    // si ningun cliente satisface los inputs, devuelve un array vacio []
    $res = array();
    for ($i = 0; $i < $resultado -> num_rows; $i++) {
        $o = $resultado -> fetch_object();
        $res[] = new Cliente ($o -> id, $o -> razon_social, $o -> logo, $o -> direccion_calle, $o -> direccion_altura,
            $o -> direccion_ciudad, null, $o -> activo, $o -> pasivo, $o -> presidente_nombre, $o -> presidente_apellido, null);
    }

    if ($filtro == 'impar') {
        sleep(3);
    }
    echo json_encode ($res);
    
} elseif ($method == 'POST') {
    
    if ( empty ($_POST ['razonSocial']) || empty ($_POST ['logo']) || empty ($_POST ['direccionCalle']) ||
         empty ($_POST ['direccionAltura']) || empty ($_POST ['direccionCiudad']) || empty ($_POST ['direccionPais']) ||
         ! isset ($_POST ['activo']) || ! is_numeric ($_POST ['activo']) || ! isset ($_POST ['pasivo']) ||
         ! is_numeric ($_POST ['pasivo']) || empty ($_POST ['presidenteNombre']) || empty ($_POST ['presidenteApellido']) ) {
        header ('HTTP/1.1 401 Unauthorized');
        exit();
    }

    $cliente = new Cliente (null, $_POST ['razonSocial'], $_POST ['logo'], $_POST ['direccionCalle'], $_POST ['direccionAltura'],
                            $_POST ['direccionCiudad'], $_POST ['direccionPais'], $_POST ['activo'], $_POST ['pasivo'],
                            $_POST ['presidenteNombre'], $_POST ['presidenteApellido'], (new DateTime('now')) -> format ('l, F d, Y H:i A') );

    $db = @new mysqli ('localhost', 'root', '', 'cursoweb');
    if ($db -> connect_error) {
        header ('HTTP/1.1 500 Internal Server Error');
        exit();
    }

    $query = "INSERT INTO clientes VALUES ( null,
                                            '{$cliente -> razonSocial}',
                                            '{$cliente -> logo}',
                                            '{$cliente -> direccion -> calle}',
                                            '{$cliente -> direccion -> altura}',
                                            '{$cliente -> direccion -> ciudad}',
                                            '{$cliente -> direccion -> pais}',
                                            '{$cliente -> activo}',
                                            '{$cliente -> pasivo}',
                                            '{$cliente -> presidente -> nombre}',
                                            '{$cliente -> presidente -> apellido}',
                                            '{$db -> real_escape_string ($cliente -> fechaCreacion)}' );";
    $resultado = $db -> query($query);
    if ($db -> error) {
        $db -> close();
        header ('HTTP/1.1 500 Internal Server Error');
        exit();
    }

    $db -> close();

    // devuelve 200 OK automaticamente
    
} elseif ($method == 'DELETE') {
    
    if (empty ($_REQUEST ['id'])) {
        sleep(2);
        header ('HTTP/1.1 401 Unauthorized');
        exit();
    }

    $id = $_REQUEST ['id'];

    if (! is_numeric ($id) || $id < 1) {
        sleep(2);
        header ('HTTP/1.1 401 Unauthorized');
        exit();
    }

    $db = @new mysqli ('localhost', 'root', '', 'cursoweb');
    if ($db -> connect_error) {
        sleep(2);
        header ('HTTP/1.1 500 Internal Server Error');
        exit();
    }

    $query = "DELETE FROM clientes WHERE id = {$id} ;";
    $resultado = $db -> query($query);
    if ($db -> error) {
        $db -> close();
        sleep(2);
        header ('HTTP/1.1 500 Internal Server Error');
        exit();
    }

    $db -> close();

    sleep(2);
    // devuelve 200 OK automaticamente

} else {

    header ('HTTP/1.1 401 Unauthorized');
    exit();   
}