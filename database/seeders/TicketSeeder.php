<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generamos 50 tickets. Cada uno creará automáticamente entre 2 y 6 ítems 
        // con consistencia matemática y la misma fecha gracias al Factory.
        Ticket::factory()->count(250)->create();
    }
}
