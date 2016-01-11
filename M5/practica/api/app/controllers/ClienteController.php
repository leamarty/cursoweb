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
        sleep(1);
        $clientes = Cliente::all();

        return Response::json($clientes);
    }

    public function getOne($id)
    {
        sleep(1);
        return Response::json(Cliente::findOrFail($id));
    }

    public function delete($id)
    {
        sleep(2);
        /** @var $cliente Cliente */
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return Response::json(array('id' => $cliente->id));
    }

    public function post()
    {
        sleep(2);
        return Response::json(Cliente::create(Input::all()));
    }

    public function put($id)
    {
        sleep(2);
        $cliente = Cliente::findOrFail($id);
        $cliente->fill(Input::all());
        $cliente->save();

        return Response::json($cliente);
    }
}
