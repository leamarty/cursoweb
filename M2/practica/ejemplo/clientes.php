<?php

header('Content-Type: application/json');

$desde = (int) @$_GET['desde'];
$cantidad = (int) @$_GET['cantidad'];
$filtro = @$_GET['filtro'];


if (!$desde || !$cantidad || !in_array($filtro, array('par', 'impar'))) {
    header('HTTP/1.1 400 Bad Request');

    echo json_encode(
        array('code' => 400, 'message' => 'No se recibieron correctamente los parámetros "desde", "cantidad" y "filtro"')
    );
} else {
    $jsonClientes = file_get_contents('clientes.json');

    $listaClientes = json_decode($jsonClientes, true);

    $listaClientesAcotada = array_slice($listaClientes, $desde - 1, $cantidad);

    $lista = array();
    foreach ($listaClientesAcotada as $cliente) {
        if (($cliente['id'] % 2) == ($filtro == 'par' ? 0 : 1)) {
            $lista[] = $cliente;
        }
    }

    if ($filtro == 'impar') {
        sleep(3);
    }

    echo json_encode($lista);
}


?>