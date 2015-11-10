<?php

$json = file_get_contents("clientes.json");

$clientes = json_decode($json, true);

// falta comprobar que sean numeros
if (!(isset($_GET["filtro"]) && isset($_GET["desde"]) && isset($_GET["cantidad"])) ||
    ($_GET["filtro"] != "impar" && $_GET["filtro"] != "par") ||
    $_GET["desde"] < 1 || $_GET["desde"] > count($clientes)
) {
    $res = array(
        "resultado" => "error"
    );
    exit(json_encode($res));
}

$res = array(
    "resultado" => "success"
);

for ($i = $_GET["desde"]; $i - $_GET["desde"] < $_GET["cantidad"] && $i - 1 < count($clientes); $i++) {
    if ($i % 2 == ($_GET["filtro"] == "impar" ? 1 : 0)) {
        $res["clientes"][] = $clientes[$i - 1];
    }
}

if ($_GET["filtro"] == "impar") {
    sleep(3);
}

echo json_encode($res);
