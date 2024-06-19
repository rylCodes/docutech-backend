<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Documentation;
use Illuminate\Database\Seeder;
use Database\Seeders\DocumentationsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'test@example.com',
            'password' => 'admin123'
        ]);

        Documentation::factory(5)->create([
            'user_id' => $user->id
        ]);

        $this->call(DocumentationsSeeder::class);
    }
}
