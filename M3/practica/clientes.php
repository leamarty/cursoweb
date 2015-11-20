<?php

$json = file_get_contents('clientes.json');

$clientes = json_decode($json, true);

$corte = 0;
$cond = $_REQUEST['cond'];
$in = $_REQUEST['in'];
for ($i = $in; $i < count($clientes); $i++) {

    if ($cond == 'p' && $i % 2 == 1) {
        $res[$i] = $clientes[$i];
    } elseif ($clientes[$i]['id'] == 1 && $cond == 'i' || $cond == 'i' && $clientes[$i]['id'] % 2 == 1) {
        $res[$i] = $clientes[$i];

    }
    if (++$corte > 9) {
        break;
    }
}
if ($cond == 'i') {
    sleep(3);
}
if ($in > count($clientes)) {
    $res = 1;
}
echo json_encode($res);
