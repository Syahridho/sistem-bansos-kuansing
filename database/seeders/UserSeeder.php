<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Fixed accounts ---
        User::create([
            'name'     => 'Admin Utama',
            'email'    => 'admin@spkbansos.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Operator Dinas',
            'email'    => 'operator@spkbansos.id',
            'password' => Hash::make('password'),
            'role'     => 'operator',
        ]);

        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'masyarakat@spkbansos.id',
            'password' => Hash::make('password'),
            'role'     => 'masyarakat',
        ]);

        // --- 97 random users to reach 100 total ---
        $roles = ['admin', 'operator', 'masyarakat'];
        $roleWeights = array_merge(
            array_fill(0, 5,  'admin'),      //  5 admin
            array_fill(0, 12, 'operator'),   // 12 operator
            array_fill(0, 80, 'masyarakat')  // 80 masyarakat
        );

        for ($i = 1; $i <= 97; $i++) {
            User::create([
                'name'     => fake('id_ID')->name(),
                'email'    => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role'     => fake()->randomElement($roleWeights),
            ]);
        }
    }
}
