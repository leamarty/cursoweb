<?php

namespace App\Models;

use Eloquent;

class Presidente extends Eloquent
{
    protected $table = 'presidentes';
    public $timestamps = false;

    protected $hidden = array(
//        'id'
    );
}
