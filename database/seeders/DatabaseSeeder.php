<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
 
        User::factory()->create([
            'name' => 'Nazwa Fatika Lubis',
            'email' => 'nazwafl0234@gmail.com',
            'username' => 'developer',
            'is_staff' => true,
            // use Illuminate\Support\Facades\Hash;   <--import di atas
            'password' => Hash::make('12345678'),
        ]);

        $this->call([
            SubjectQuestionSeeder::class,
        ]);
    }
}