<?php

//exit();

$connection = mysqli_connect('localhost', 'root', '', 'cursoweb');
$lastError = mysqli_error($connection);

if ($lastError) {
    exit($lastError);
}

$json = file_get_contents('clientes.json');

$clientes = json_decode($json, true);

foreach ($clientes as $cliente) {
    $fechaFormateada = new DateTime($cliente['fechaCreacion']);
    $fechaFormateada = $fechaFormateada->format('Y-m-d H:i:s');

    $query = 'INSERT INTO clientes (
        id, razon_social, logo, direccion_calle, direccion_altura, direccion_ciudad, direccion_pais, activo, pasivo, presidente_nombre, presidente_apellido, fecha_creacion
    ) VALUES (
        "' . mysqli_real_escape_string($connection, $cliente['id']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['razonSocial']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['logo']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['direccion']['calle']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['direccion']['altura']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['direccion']['ciudad']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['direccion']['pais']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['activo']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['pasivo']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['presidente']['nombre']) . '",
        "' . mysqli_real_escape_string($connection, $cliente['presidente']['apellido']) . '",
        "' . mysqli_real_escape_string($connection, $fechaFormateada) . '"
    )';

    $result = mysqli_query($connection, $query);

    $lastError = mysqli_error($connection);

    if ($lastError) {
        exit($lastError);
    }
}
