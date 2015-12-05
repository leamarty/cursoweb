<?php

namespace App\Models;

use Eloquent;

class Ciudad extends Eloquent
{
    protected $table = 'ciudades';
    public $timestamps = false;

    protected $hidden = array(
//        'id',
        'pais_id'
    );

    protected $with = array(
        'pais'
    );

    public function pais()
    {
        return $this->belongsTo('App\\Models\\Pais');
    }
}
