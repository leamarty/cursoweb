<?php

interface ReceptorDeDanio {
    public function recibirDanio($danio);
}

abstract class Personaje implements ReceptorDeDanio {
    protected $vidaActual;
    protected $vidaTotal;
    protected $nivel;
    protected $fuerza;
    protected $defensa;

    protected abstract function calcularDanio();

    public function atacar(ReceptorDeDanio $aQuien) {
        $danio = $this->calcularDanio();

        $aQuien->recibirDanio($danio);
    }

    public function recibirDanio($cuanto) {
        $this->vidaActual -= ($cuanto - min($this->defensa, $cuanto));
    }

    public function estaMuerto() {
        return ($this->vidaActual <= 0);
    }

    public function __construct($id) {
        $db = new mysqli('localhost', 'root', '', 'cursoweb');
        $query = 'SELECT * FROM personajes WHERE id = ' . $id . ';';
        $resultado = $db->query($query);
        $personaje = $resultado->fetch_object();

        $this->nivel = $personaje->nivel;
        $this->vidaTotal = $personaje->vida_total;
        $this->vidaActual = $this->vidaTotal;
        $this->fuerza = $personaje->fuerza;
        $this->defensa = $personaje->defensa;
    }

    public function getVidaActual() {
        return $this->vidaActual;
    }
}

abstract class Monstruo extends Personaje {
    protected function calcularDanio() {
        return $this->fuerza * $this->nivel;
    }
}

class Dragon extends Monstruo {
    protected function calcularDanio() {
        return $this->fuerza * 1.5 * $this->nivel;
    }

    public function recibirDanio($cuanto) {
        $mutiplicador = 1;
        if ($this->vidaActual < (0.5 * $this->vidaTotal)) {
            $mutiplicador = 0.5;
        }
        $this->vidaActual -= ($cuanto - min($this->defensa, $cuanto)) * $mutiplicador;
    }
}

class Orco extends Monstruo {
}

class Heroe extends Personaje {
    protected $fuerzaPorItems;

    protected function calcularDanio() {
        return $this->fuerza * $this->nivel + $this->fuerzaPorItems;
    }
}

// HASTA ACÁ TERMINARON LAS CLASES

// ********************************************************************************

// EMPIEZA EL PROGRAMA

function vida(Personaje $obj) {
    echo get_class($obj) . ': ' . $obj->getVidaActual() . '<br>';
}

// PELEA

//$dragon = new Dragon(10, 4000, 40, 300);
//$heroe = new Heroe(30, 900, 55, 200);

$dragon = new Dragon(1);
$heroe = new Heroe(2);

vida($dragon);
vida($heroe);

while (!$dragon->estaMuerto() && !$heroe->estaMuerto()) {
    $heroe->atacar($dragon);
    if (!$dragon->estaMuerto()) {
        $dragon->atacar($heroe);
    }
    vida($dragon);
    vida($heroe);
}

//$dragon->atacar();