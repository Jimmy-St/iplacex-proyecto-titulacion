<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Picker;

class PickerSeeder extends Seeder
{
    public function run(): void
    {
        $pickers = [
            ['first_name' => 'Alan', 'last_name' => 'Meza', 'display_name' => 'Alan M.'],
            ['first_name' => 'Antony', 'last_name' => 'Edghill', 'display_name' => 'Antony E.'],
            ['first_name' => 'Breimer', 'last_name' => 'Altamiranda', 'display_name' => 'Breimer A.'],
            ['first_name' => 'Carlos', 'last_name' => 'Fuentes', 'display_name' => 'Carlos F.'],
            ['first_name' => 'Cesar', 'last_name' => 'Castillo', 'display_name' => 'Cesar C.'],
            ['first_name' => 'Cristian', 'last_name' => 'Zambrano', 'display_name' => 'Cristian Z.'],
            ['first_name' => 'David', 'last_name' => 'Gutierrez', 'display_name' => 'David G.'],
            ['first_name' => 'Dennys', 'last_name' => 'Diaz', 'display_name' => 'Dennys D.'],
            ['first_name' => 'Diego', 'last_name' => 'Flores', 'display_name' => 'Diego F.'],
            ['first_name' => 'Edixon', 'last_name' => 'Diaz', 'display_name' => 'Edixon D.'],
            ['first_name' => 'Elveril', 'last_name' => 'Flores', 'display_name' => 'Elveril F.'],
            ['first_name' => 'Erik', 'last_name' => 'Quiñones', 'display_name' => 'Erik Q.'],
            ['first_name' => 'Eudis', 'last_name' => 'Pernea', 'display_name' => 'Eudis P.'],
            ['first_name' => 'Felix', 'last_name' => 'Silva', 'display_name' => 'Felix S.'],
            ['first_name' => 'Fernando', 'last_name' => 'Moyano', 'display_name' => 'Fernando M.'],
            ['first_name' => 'Francisco', 'last_name' => 'Arenas', 'display_name' => 'Francisco A.'],
            ['first_name' => 'Giovanni', 'last_name' => 'Arocha', 'display_name' => 'Giovanni A.'],
            ['first_name' => 'Guillermo', 'last_name' => 'Morales', 'display_name' => 'Guillermo M.'],
            ['first_name' => 'Johny', 'last_name' => 'Montoya', 'display_name' => 'Johny M.'],
            ['first_name' => 'Jonathan', 'last_name' => 'Ulloa', 'display_name' => 'Jonathan U.'],
            ['first_name' => 'Jose', 'last_name' => 'Carbonell', 'display_name' => 'Jose C.'],
            ['first_name' => 'Julian', 'last_name' => 'Lugo', 'display_name' => 'Julian L.'],
            ['first_name' => 'Justin', 'last_name' => 'Abril', 'display_name' => 'Justin A.'],
            ['first_name' => 'Leandro', 'last_name' => 'Cedeño', 'display_name' => 'Leandro C.'],
            ['first_name' => 'Marcos', 'last_name' => 'Plaza', 'display_name' => 'Marcos P.'],
            ['first_name' => 'Matias', 'last_name' => 'Alvarez', 'display_name' => 'Matias A.'],
            ['first_name' => 'Nazareth', 'last_name' => 'Hernandez', 'display_name' => 'Nazareth H.'],
            ['first_name' => 'Nixon', 'last_name' => 'Ulloa', 'display_name' => 'Nixon U.'],
            ['first_name' => 'Ronald', 'last_name' => 'Morales', 'display_name' => 'Ronald M.'],
            ['first_name' => 'Sebastian', 'last_name' => 'Arratia', 'display_name' => 'Sebastian A.'],
            ['first_name' => 'Victor', 'last_name' => 'Reyes', 'display_name' => 'Victor R.'],
            ['first_name' => 'Yhorman', 'last_name' => 'Velez', 'display_name' => 'Yhorman V.'],
            ['first_name' => 'Yoander', 'last_name' => 'Duran', 'display_name' => 'Yoander D.'],
            ['first_name' => 'Ember', 'last_name' => 'Gonzalez', 'display_name' => 'Ember G.'],
            ['first_name' => 'Enrique', 'last_name' => 'Morales', 'display_name' => 'Enrique M.'],
            ['first_name' => 'Jesus', 'last_name' => 'Vivas', 'display_name' => 'Jesus V.'],
            ['first_name' => 'Jose', 'last_name' => 'Sierra', 'display_name' => 'Jose S.'],
            ['first_name' => 'Luinger', 'last_name' => 'Garcia', 'display_name' => 'Luinger G.'],
            ['first_name' => 'Michel', 'last_name' => '', 'display_name' => 'Michel'],
            ['first_name' => 'Raul', 'last_name' => 'Torres', 'display_name' => 'Raul T.'],
        ];

        foreach ($pickers as $index => $picker) {
            Picker::updateOrCreate(
                [
                    'first_name' => $picker['first_name'],
                    'last_name'  => $picker['last_name'],
                ],
                [
                    'employee_code' => 'PCK-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'display_name'  => $picker['display_name'],
                    'is_active'     => true,
                    'status'        => 'active',
                ]
            );
        }
    }
}
