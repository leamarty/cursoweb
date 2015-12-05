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
        'ciudad_id',
        'activo',
        'pasivo',
        'presidente_id'
    );

    protected $hidden = array(
        'ciudad_id',
        'presidente_id'
    );

    protected $with = array(
        'ciudad',
        'presidente'
    );

    public function ciudad()
    {
        return $this->belongsTo('App\\Models\\Ciudad');
    }

    public function presidente()
    {
        return $this->belongsTo('App\\Models\\Presidente');
    }
}
