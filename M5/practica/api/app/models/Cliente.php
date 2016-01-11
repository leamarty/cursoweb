<?php

namespace App\Models;

use Eloquent;

class Cliente extends Eloquent
{
    protected $table = 'clientes';

    public $timestamps = false;

    protected $fillable = array(
        'razon_social',
        'logo',
        'direccion_calle',
        'direccion_altura',
        'direccion_ciudad',
        'pais_id',
        'activo',
        'pasivo',
        'presidente_id'
    );

    public function presidente() {
        return $this->belongsTo('App\\Models\\Presidente');
    }
}
