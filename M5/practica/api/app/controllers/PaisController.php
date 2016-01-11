<?php

namespace App\Controllers;

use Controller;
use Response;
use App\Models\Pais;

class PaisController extends Controller
{
	public function get()
	{
        return Response::json(
            Pais::all()
        );
	}

}
