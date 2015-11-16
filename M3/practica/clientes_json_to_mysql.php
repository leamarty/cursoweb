<?php

$json = file_get_contents ('clientes.json');
$clientes = json_decode ($json, true);

$db = @new mysqli ('localhost', 'root', '', 'cursoweb');
if ($db -> connect_error) {
    exit ("Error ({$db -> connect_errno}) {$db -> connect_error}");
}

$query = 'DROP TABLE clientes;';
$resultado = $db -> query ($query);
if ($db -> error && $db -> errno != 1051) {
    exit ("Error ({$db -> errno}) {$db -> error}");
}

$query = 'CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  razon_social VARCHAR(255) NOT NULL,
  logo VARCHAR(255) NOT NULL,
  direccion_calle VARCHAR(255) NOT NULL,
  direccion_altura INT NOT NULL,
  direccion_ciudad VARCHAR(255) NOT NULL,
  direccion_pais VARCHAR(255) NOT NULL,
  activo VARCHAR(255) NOT NULL,
  pasivo VARCHAR(255) NOT NULL,
  presidente_nombre VARCHAR(255) NOT NULL,
  presidente_apellido VARCHAR(255) NOT NULL,
  fecha_creacion VARCHAR(255) NOT NULL
);';
$resultado = $db -> query ($query);
if ($db -> error) {
    exit ("Error ({$db -> errno}) {$db -> error}");
}

foreach ($clientes as $cliente) {
    $fecha_creacion = (new DateTime('now')) -> format ('l, F d, Y H:i A');
    $query = "INSERT INTO clientes (
        razon_social,
        logo,
        direccion_calle,
        direccion_altura,
        direccion_ciudad,
        direccion_pais,
        activo,
        pasivo,
        presidente_nombre,
        presidente_apellido,
        fecha_creacion
      ) VALUES (
        '{$cliente ['razonSocial']}',
        '{$cliente ['logo']}',
        '{$cliente ['direccion'] ['calle']}',
        '{$cliente ['direccion'] ['altura']}',
        '{$cliente ['direccion'] ['ciudad']}',
        '{$cliente ['direccion'] ['pais']}',
        '{$cliente ['activo']}',
        '{$cliente ['pasivo']}',
        '{$cliente ['presidente'] ['nombre']}',
        '{$cliente ['presidente'] ['apellido']}',
        '{$fecha_creacion}'
      );";
    $resultado = $db -> query ($query);
    if ($db -> error) {
        exit ("Error ({$db -> errno}) {$db -> error}");
    }
}

$db -> close();