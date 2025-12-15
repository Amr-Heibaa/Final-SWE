<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ziad Asaad',           // ← Change this to his actual name
            'email' => 'tripleAAA@gmail.com', // ← Change this
            'password' => Hash::make('password123'), // ← Change this to his actual password
            'phone' => '+01015447980',         // ← Add his phone
                        'brand_name' => '', // <-- add this

            'role' => 'super_admin',            // ← Don't change this
        ]);
    }
}
