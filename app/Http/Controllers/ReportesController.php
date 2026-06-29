<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function index()
    {
        // $stats = [...];
        return view('reportes.index');
    }
}
