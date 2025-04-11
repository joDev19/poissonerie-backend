<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entrers = [
            [
                "date" => now(),
                "product_id" => 1,
                "box_quantity" => 3,
                "kilo_quantity" => 30,
                "price" => 27000,
                "fournisseur_id" => 1
            ],
            [
                "date" => now(),
                "product_id" => 1,
                "box_quantity" => 3,
                "kilo_quantity" => 30,
                "price" => 27000,
                "fournisseur_id" => 1
            ],
        ];
    }
}
