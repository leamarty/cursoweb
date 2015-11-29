<?php

namespace App\Controllers;

use Controller;
use Response;
use App\Models\Presidente;

class PresidenteController extends Controller
{
	public function get()
	{
        return Response::json(
            Presidente::all()
        );
	}

}
