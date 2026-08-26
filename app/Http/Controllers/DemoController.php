<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function show($numero)
    {
        return view('demo-container', compact('numero'));
    }
}
