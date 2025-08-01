<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // User::truncate();
        User::create([
            'name' => 'Admin name',
            'email' => 'ppapadollard@gmail.com',
            'role' => 'admin',
            'password' => 'password'
        ]);
        User::create([
            'name' => 'Admin name 2',
            'email' => 'ppapadollard2@gmail.com',
            'role' => 'admin',
            'password' => 'password'
        ]);
        // $this->call([
        //     FournisseurSeeder::class,
        //     MarqueSeeder::class,
        //     //ProductSeeder::class,
        // ]);
    }
}
