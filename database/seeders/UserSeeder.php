<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name' => 'M. Ousmane',
                'email' => 'admin@boutique.com',
                'password' => Hash::make('motdepassefort'), 
                'role' => 'admin',
                'photo' => 'admin.jpg',
            ]);
        }
    }
}
