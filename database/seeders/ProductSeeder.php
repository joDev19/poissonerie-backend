<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                "name" => "Silivi 20+",
                "marque_id" => 1,
                "price_kilo" => 1100,
                "price_carton" => 10000,
            ],
            [
                "name" => "Silivi 25+",
                "marque_id" => 1,
                "price_kilo" => 1200,
                "price_carton" => 11000,
            ],
            [
                "name" => "Poisson chat fretin",
                "marque_id" => 2,
                "price_kilo" => 800,
                "price_carton" => 7500,
            ],
            [
                "name" => "Poisson chat gros",
                "marque_id" => 2,
                "price_kilo" => 1000,
                "price_carton" => 9000,
            ],
            [
                "name" => "Aileron de poulet",
                "marque_id" => 3,
                "price_kilo" => 1300,
                "price_carton" => 12500,
            ],
            [
                "name" => "Aileron de dinde",
                "marque_id" => 3,
                "price_kilo" => 1500,
                "price_carton" => 13000,
            ],
            [
                "name" => "Cuisson rapide",
                "marque_id" => 3,
                "price_kilo" => 1350,
                "price_carton" => 13000,
            ],
            [
                "name" => "Tilapia petit",
                "marque_id" => 4,
                "price_kilo" => 2500,
                "price_carton" => 23000,
            ],
            [
                "name" => "Tilapia moyen",
                "marque_id" => 4,
                "price_kilo" => 2800,
                "price_carton" => 25000,
            ],
            [
                "name" => "Tilapia grand",
                "marque_id" => 4,
                "price_kilo" => 3000,
                "price_carton" => 28500,
            ],
            [
                "name" => "Saumon petit",
                "marque_id" => 5,
                "price_kilo" => 1500,
                "price_carton" => 14000,
            ],
            [
                "name" => "Saumon grand",
                "marque_id" => 5,
                "price_kilo" => 2100,
                "price_carton" => 20000,
            ],
        ];
        foreach ($products as $key => $product) {
            Product::create($product);
        }
    }
}
