<?php

$json = file_get_contents('clientes.json');

$clientes = json_decode($json, true);


//if ($_REQUEST['cond'] == 'p') {

$a=$_REQUEST['cond'];
$i=$_REQUEST['in'];
$c=$_REQUEST['can'];
while($i<$c){
    if ( ($i % 2) ==1 && $a=='p' ) {
        $rta[$i] = array(
            'logo' => $clientes[$i]['logo'],
            'id' => $clientes[$i]['id'],
            'razs' => $clientes[$i]['razonSocial'],
            'pres' => $clientes[$i]['presidente'],
            'patri' => "1"
        );
        }else{if( ($i%2)==0 && $a=='i') {
        $rta[$i] = array(
            'logo' => $clientes[$i]['logo'],
            'id' => $clientes[$i]['id'],
            'razs' => $clientes[$i]['razonSocial'],
            'pres' => $clientes[$i]['presidente'],
            'patri' => "1"
        );
    }
    }

$i++;

}
if($a=='i'){
    sleep(3);
}
echo json_encode($rta);






