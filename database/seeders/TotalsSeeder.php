<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TotalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        $dates = ['2026-09-14', '2026-09-15', '2026-09-16'];

        foreach ($dates as $date) {
            $totalTickets = rand(50, 100);
            $totalItems = rand(500, 1500);
            $totalAmount = rand(5000000, 20000000);

            DB::table('total_day')->insert([
                'date'          => $date,
                'total_tickets' => $totalTickets,
                'total_items'   => $totalItems,
                'total_amount'  => $totalAmount,
            ]);

            $pickerIds = range(1, 30);
            shuffle($pickerIds);
            $selectedPickers = array_slice($pickerIds, 0, rand(8, 15));

            $accumulatedTickets = 0;
            $accumulatedItems = 0;
            $accumulatedAmount = 0;

            foreach ($selectedPickers as $index => $pickerId) {
                $isLast = ($index === count($selectedPickers) - 1);

                $tasks = $isLast ? ($totalTickets - $accumulatedTickets) : rand(1, max(1, (int)($totalTickets / count($selectedPickers))));
                $items = $isLast ? ($totalItems - $accumulatedItems) : rand(10, max(10, (int)($totalItems / count($selectedPickers))));
                $amount = $isLast ? ($totalAmount - $accumulatedAmount) : rand(100000, max(100000, (int)($totalAmount / count($selectedPickers))));

                $accumulatedTickets += $tasks;
                $accumulatedItems += $items;
                $accumulatedAmount += $amount;

                DB::table('picker_total_day')->insert([
                    'picker_id'    => $pickerId,
                    'date'         => $date,
                    'total_tasks'  => $tasks,
                    'total_items'  => $items,
                    'total_amount' => $amount,
                ]);
            }
        }
    }
}
