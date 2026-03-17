<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@sonalgaz.dz',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Archiviste User',
            'email' => 'archiviste@sonalgaz.dz',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'archiviste',
        ]);

        \App\Models\User::create([
            'name' => 'Consultant User',
            'email' => 'consultant@sonalgaz.dz',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'consultant',
        ]);
    }
}
