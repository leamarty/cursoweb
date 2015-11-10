<?php

$json = file_get_contents('clientes.json');

$clientes = json_decode($json, true);

if($_REQUEST['id']==0){

}




echo json_encode($clientes[$indice-1]);