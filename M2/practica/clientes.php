<?php

$json = file_get_contents("clientes.json");

$clientes = json_decode($json, true);

if (! (isset($_GET["filtro"]) && isset($_GET["desde"]) && isset($_GET["cantidad"]))) {
    exit();
}

$r = "[";

$i = $_GET["desde"];
if ($_GET["filtro"] == "impar" && !($i & 1)) {
    $i++;
} elseif ($_GET["filtro"] == "par" && $i & 1) {
    $i++;
}

for (; $i - $_GET["desde"] < 10 && $i - 1 < count($clientes); $i += 2) {
    $r .= json_encode($clientes[$i - 1]) . ",";
}

if (substr($r, -1, 1) == ",") {
    $r = substr($r, 0, -1);
}

$r .= "]";

if ($_GET["filtro"] == "impar") {
    sleep(3);
}

echo $r;
