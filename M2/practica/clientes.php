<?php

$json = file_get_contents('clientes.json');

$clientes = json_decode($json, true);
$i=1;


//if ($_REQUEST['cond'] == 'p') {

    //for ($i = 0; $i < 5; $i++) {
        //if ($i != 0 && ($i % 2) == 1) {
            $pares = array(
                'logo' => $clientes[$i]['logo'],
                'id' => $clientes[$i]['id'],
                'razs' => $clientes[$i]['razonSocial'],
                'pres' => $clientes[$i]['presidente'],
                'patri' => "1",
            );
        //}
    //}

//}
echo json_encode($pares);

//}

