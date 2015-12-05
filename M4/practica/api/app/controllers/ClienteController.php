<?php

namespace App\Controllers;

use Controller;
use Response;
use App\Models\Cliente;
use Input;
use DateTime;

class ClienteController extends Controller
{
    public function get($id = false, $cantidad = 1)
    {
        $cantidad = $cantidad > 5 ? 5 : $cantidad;

        if ($id === false) {
            return Response::json(
                Cliente::all()
            );

        } else {
            $clientes = Cliente
                ::where('id', '>=', $id)
                ->whereRaw('id % 2 = ?', array($id & 1))
                ->limit($cantidad)
                ->get();
            sleep($id & 1 ? 3 : 0);
            return Response::json($cantidad == 1 ? array($clientes) : $clientes);
        }
    }

    public function delete($id)
    {
        /** @var $cliente Cliente */
        if (!is_null($cliente = Cliente::find($id))) {
            $cliente->delete();
        }
        sleep(2);
        return Response::json(array('id' => $id), is_null($cliente) ? 404 : 200);
    }

    public function post()
    {
        $datos = Input::all();
        if (!$this->validar_post($datos)) {
            return Response::json(array(), 400);
        }
        $datos['fecha_creacion'] = (new DateTime('now'))->format('l, F d, Y H:i A');
        $cliente = Cliente::create($datos);
        return Response::json($cliente);
    }

    private function validar_post($datos)
    {
        if (!count($datos)) {
            return false;
        }
        foreach ($datos as $key => $value) {
            if (empty($value)) {
                return false;
            }
        }
        return true;
    }
}
