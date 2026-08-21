<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Andrea',
                'username' => 'andrea',
                'email'    => 'andrea@pfau.cl',
                'password' => Hash::make('andrea'),
            ],
            [
                'name'     => 'Cristobal',
                'username' => 'cristobal',
                'email'    => 'cristobal@pfau.cl',
                'password' => Hash::make('cristobal'),
            ],
            [
                'name'     => 'Fabiana',
                'username' => 'fabiana',
                'email'    => 'fabiana@pfau.cl',
                'password' => Hash::make('fabiana'),
            ],
            [
                'name'     => 'Juan Ignacio',
                'username' => 'jignacio',
                'email'    => 'jignacio@pfau.cl',
                'password' => Hash::make('jignacio'),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
