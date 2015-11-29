<?php

namespace App\Controllers;

use App\Models\Cliente;
use Controller;
use Input;
use Response;

class ClienteController extends Controller
{
    public function get()
    {
        $clientes = Cliente::where('id', '>=', Input::get('desde', 1))
            ->whereRaw('id % 2 = ' . (Input::get('filtro') == 'par' ? 0 : 1))
            ->limit(Input::get('cantidad', 5))
            ->get();

        return Response::json($clientes);
    }

    public function delete($id)
    {
        /** @var $cliente Cliente */
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return Response::json(array('id' => $cliente->id));
    }

    public function post()
    {
        return Response::json(Cliente::create(Input::all()));
    }
}
