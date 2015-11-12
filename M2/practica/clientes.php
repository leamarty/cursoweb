<?php

$json = file_get_contents('clientes.json');

$clientes = json_decode($json, true);

// falta comprobar que sean numeros
if (!(isset($_GET['filtro']) && isset($_GET['desde']) && isset($_GET['cantidad'])) ||
    !in_array($_GET['filtro'], array('impar', 'par')) ||
    $_GET['desde'] < 1 /* || $_GET['desde'] > count($clientes) */
) {
    if (isset($_GET['filtro']) && $_GET['filtro'] == 'impar') {
        sleep(3);
    }
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

// si ningun cliente satisface los inputs, devuelve un array vacio []
$res = array();

for ($i = $_GET['desde']; $i - $_GET['desde'] < $_GET['cantidad'] && $i - 1 < count($clientes); $i++) {
    if ($i % 2 == ($_GET['filtro'] == 'impar' ? 1 : 0)) {
        $res[] = $clientes[$i - 1];
    }
}

if ($_GET['filtro'] == 'impar') {
    sleep(3);
}

echo json_encode($res);
