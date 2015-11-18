<?php

$json = file_get_contents('clientes.json');

$clientes = json_decode($json, true);

$i = 0;
$cond = $_REQUEST['cond'];
$in = $_REQUEST['in'];

foreach ($clientes as $key => $value) {
    if ($in <= $key) {
        if ($cond == 'p' && $key % 2 == 1) {
            $res[$key] = $clientes[$key];
        } elseif ($clientes[$key]['id'] == 1 && $cond == 'i' || $cond == 'i' && $clientes[$key]['id'] % 2 == 1) {
            $res[$key] = $clientes[$key];

        }
        if (++$i > 9) {
            break;
        }
    }
}
if ($cond == 'i') {
    sleep(3);
}
echo json_encode($res);
