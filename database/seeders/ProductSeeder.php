<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Igname', 'category' => 'Tubercules', 'unit' => 'kg'],
            ['name' => 'Manioc', 'category' => 'Tubercules', 'unit' => 'kg'],
            ['name' => 'Patate douce', 'category' => 'Tubercules', 'unit' => 'kg'],
            ['name' => 'Maïs', 'category' => 'Céréales', 'unit' => 'kg'],
            ['name' => 'Sorgho', 'category' => 'Céréales', 'unit' => 'kg'],
            ['name' => 'Mil', 'category' => 'Céréales', 'unit' => 'kg'],
            ['name' => 'Riz local', 'category' => 'Céréales', 'unit' => 'kg'],
            ['name' => 'Niébé', 'category' => 'Légumineuses', 'unit' => 'kg'],
            ['name' => 'Arachide', 'category' => 'Légumineuses', 'unit' => 'kg'],
            ['name' => 'Soja', 'category' => 'Légumineuses', 'unit' => 'kg'],
            ['name' => 'Tomate', 'category' => 'Légumes', 'unit' => 'kg'],
            ['name' => 'Piment', 'category' => 'Légumes', 'unit' => 'kg'],
            ['name' => 'Oignon', 'category' => 'Légumes', 'unit' => 'kg'],
            ['name' => 'Gombo', 'category' => 'Légumes', 'unit' => 'kg'],
            ['name' => 'Aubergine locale', 'category' => 'Légumes', 'unit' => 'kg'],
            ['name' => 'Ananas', 'category' => 'Fruits', 'unit' => 'pièce'],
            ['name' => 'Mangue', 'category' => 'Fruits', 'unit' => 'kg'],
            ['name' => 'Papaye', 'category' => 'Fruits', 'unit' => 'kg'],
            ['name' => 'Noix de cajou', 'category' => 'Fruits', 'unit' => 'kg'],
            ['name' => 'Noix de coco', 'category' => 'Fruits', 'unit' => 'pièce'],
            ['name' => 'Huile de palme', 'category' => 'Transformation', 'unit' => 'litre'],
            ['name' => 'Gari', 'category' => 'Transformation', 'unit' => 'kg'],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'category' => $product['category'],
                'unit' => $product['unit'],
                'is_active' => true,
            ]);
        }
    }
}