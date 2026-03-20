<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin AgriL',
            'email' => 'admin@agril.bj',
            'password' => 'password',
            'role' => 'admin',
            'phone' => '+22961000000',
            'is_active' => true,
        ]);

        $farmers = [
            [
                'name' => 'Koffi Mensah',
                'email' => 'koffi@agril.bj',
                'phone' => '+22961111111',
                'city' => 'Bohicon',
                'department' => 'Zou',
            ],
            [
                'name' => 'Adjoua Amoussou',
                'email' => 'adjoua@agril.bj',
                'phone' => '+22962222222',
                'city' => 'Parakou',
                'department' => 'Borgou',
            ],
            [
                'name' => 'Séraphin Dossou',
                'email' => 'seraphin@agril.bj',
                'phone' => '+22963333333',
                'city' => 'Abomey-Calavi',
                'department' => 'Atlantique',
            ],
        ];

        foreach ($farmers as $farmer) {
            User::create([
                'name' => $farmer['name'],
                'email' => $farmer['email'],
                'password' => 'password',
                'role' => 'farmer',
                'phone' => $farmer['phone'],
                'is_active' => true,
            ]);
        }

        $buyers = [
            [
                'name' => 'Restaurant Le Cocotier',
                'email' => 'cocotier@agril.bj',
                'phone' => '+22964444444',
            ],
            [
                'name' => 'Supermarché Maison',
                'email' => 'supermarche@agril.bj',
                'phone' => '+22965555555',
            ],
        ];

        foreach ($buyers as $buyer) {
            User::create([
                'name' => $buyer['name'],
                'email' => $buyer['email'],
                'password' => 'password',
                'role' => 'buyer',
                'phone' => $buyer['phone'],
                'is_active' => true,
            ]);
        }
    }
}