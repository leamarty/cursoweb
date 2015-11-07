<?php

$json = file_get_contents('clientes.json');

$clientes = json_decode($json, true);

echo json_encode($clientes);