<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function index()
    {
        // $productos = Producto::all();
        return view('productos.index');
    }
}
