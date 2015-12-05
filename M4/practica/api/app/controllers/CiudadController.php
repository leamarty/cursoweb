<?php

namespace App\Controllers;

use Controller;
use Response;
use App\Models\Ciudad;

class CiudadController extends Controller
{
    public function get()
    {
        return Response::json(Ciudad::all());
    }
}
