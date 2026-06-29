<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PickersController extends Controller
{
    public function index()
    {
        // $pickers = Picker::where('activo', true)->get();
        return view('pickers.index');
    }
}
