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

    protected $hidden = array(
        'presidente_id'
    );

    protected $with = array(
        'presidente'
    );

    public function presidente() {
        return $this->belongsTo('App\\Models\\Presidente');
    }
}
