<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fournisseur::truncate();
        $names = ["Fournisseur de silivi", "Fournisseur de saumon", "Fournisseur de tilapia", 'Fournisseur de tchawa', 'Fournisseur de viande'];
        foreach ($names as $name) {
            Fournisseur::create([
                "name" => $name,
            ]);
        }
        // Fournisseur::factory(100)->create();
    }
}
