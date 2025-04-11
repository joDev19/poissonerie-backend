<?php

namespace Database\Seeders;

use App\Models\Marque;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Marque::factory(100)->create();
        // Marque::truncate();
        $marques = [
            [
                "name" => "Silivi",
            ],
            [
                "name" => "Poisson chat",
            ],
            [
                "name" => "Aileron",
            ],
            [
                "name" => "Tilapia",
            ],
            [
                "name" => "Saumon",
            ]
        ];
        foreach ($marques as $key => $marque) {
            Marque::create($marque);
        }
    }
}
