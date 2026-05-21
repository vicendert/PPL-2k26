<?php

namespace Database\Seeders;

use App\Models\Court;
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
        Court::query()->create([
            'name' => 'Lapangan A',
            'location' => 'Indoor',
            'price_per_hour' => 150000,
        ]);

        Court::query()->create([
            'name' => 'Lapangan B',
            'location' => 'Outdoor',
            'price_per_hour' => 120000,
        ]);

        User::query()->create([
            'name' => 'User Demo',
            'username' => 'user01',
            'phone' => '081234567890',
            'address' => 'Jl. Padel No. 1',
            'gender' => 'male',
            'role' => User::ROLE_USER,
            'email' => 'user01@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::query()->create([
            'name' => 'Admin Kasir Demo',
            'username' => 'kasir01',
            'phone' => '081298765432',
            'address' => 'Jl. Kasir No. 2',
            'gender' => 'female',
            'role' => User::ROLE_ADMIN_KASIR,
            'email' => 'kasir01@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::query()->create([
            'name' => 'Owner Demo',
            'username' => 'owner01',
            'phone' => '081211112222',
            'address' => 'Jl. Owner No. 3',
            'gender' => 'male',
            'role' => User::ROLE_OWNER,
            'email' => 'owner01@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
