<?php

namespace Database\Seeders;

use App\Models\TicketItem;
use Illuminate\Database\Seeder;

class TicketItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketItem::factory()->count(100)->create();
    }
}
