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
    }
}
