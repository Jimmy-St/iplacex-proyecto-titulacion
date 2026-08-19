<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use App\Http\Requests\TicketRequest;

class TicketController extends Controller
{
    public function index()
    {
        $fecha  = request('fecha', date('Y-m-d'));
        $buscar = request('buscar');

        $tickets = Ticket::whereDate('created_at', $fecha)
            ->when($buscar, fn($q) => $q->where('ticket_number', 'LIKE', "%{$buscar}%"))
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets', 'fecha'));
    }

    public function show($ticket_number)
    {
        $ticket = Ticket::with('items')->where('ticket_number', $ticket_number)->first();
        return view('tickets.show', compact('ticket'));
    }
}
