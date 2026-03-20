<?php

namespace Database\Seeders;

use App\Models\Farm;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;

class FarmSeeder extends Seeder
{
    public function run(): void
    {
        $farms = [
            [
                'email' => 'koffi@agril.bj',
                'name' => 'Exploitation Mensah',
                'description' => 'Producteur d\'igname et de maïs depuis 15 ans dans la région de Bohicon.',
                'latitude' => 7.1833,
                'longitude' => 2.0667,
                'address' => 'Quartier Gare, Bohicon',
                'city' => 'Bohicon',
                'department' => 'Zou',
                'stocks' => [
                    ['product' => 'Igname', 'quantity' => 500, 'price' => 350, 'unit' => 'kg'],
                    ['product' => 'Maïs', 'quantity' => 800, 'price' => 180, 'unit' => 'kg'],
                ],
            ],
            [
                'email' => 'adjoua@agril.bj',
                'name' => 'Ferme Amoussou',
                'description' => 'Spécialiste de la culture maraîchère et des légumineuses dans le Borgou.',
                'latitude' => 9.3372,
                'longitude' => 2.6283,
                'address' => 'Route de Natitingou, Parakou',
                'city' => 'Parakou',
                'department' => 'Borgou',
                'stocks' => [
                    ['product' => 'Niébé', 'quantity' => 300, 'price' => 420, 'unit' => 'kg'],
                    ['product' => 'Arachide', 'quantity' => 200, 'price' => 500, 'unit' => 'kg'],
                    ['product' => 'Tomate', 'quantity' => 150, 'price' => 280, 'unit' => 'kg'],
                ],
            ],
            [
                'email' => 'seraphin@agril.bj',
                'name' => 'Domaine Dossou',
                'description' => 'Production d\'ananas et de noix de cajou en zone côtière atlantique.',
                'latitude' => 6.4483,
                'longitude' => 2.3559,
                'address' => 'Voie de l\'université, Abomey-Calavi',
                'city' => 'Abomey-Calavi',
                'department' => 'Atlantique',
                'stocks' => [
                    ['product' => 'Ananas', 'quantity' => 600, 'price' => 150, 'unit' => 'pièce'],
                    ['product' => 'Noix de cajou', 'quantity' => 250, 'price' => 1200, 'unit' => 'kg'],
                ],
            ],
        ];

        foreach ($farms as $farmData) {
            $user = User::where('email', $farmData['email'])->first();

            $farm = Farm::create([
                'user_id' => $user->id,
                'name' => $farmData['name'],
                'description' => $farmData['description'],
                'latitude' => $farmData['latitude'],
                'longitude' => $farmData['longitude'],
                'address' => $farmData['address'],
                'city' => $farmData['city'],
                'department' => $farmData['department'],
                'is_active' => true,
            ]);

            foreach ($farmData['stocks'] as $stockData) {
                $product = Product::where('name', $stockData['product'])->first();

                Stock::create([
                    'farm_id' => $farm->id,
                    'product_id' => $product->id,
                    'quantity' => $stockData['quantity'],
                    'unit' => $stockData['unit'],
                    'price_per_unit' => $stockData['price'],
                    'available_from' => now(),
                    'status' => 'available',
                ]);
            }
        }
    }
}