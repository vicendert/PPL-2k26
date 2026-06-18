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
    public function run(): void
    {
        Court::query()->updateOrCreate(['name' => 'Lapangan A'], [
            'location' => 'Indoor',
            'price_per_hour' => 300000,
        ]);

        Court::query()->updateOrCreate(['name' => 'Lapangan B'], [
            'location' => 'Outdoor',
            'price_per_hour' => 300000,
        ]);

        Court::query()->updateOrCreate(['name' => 'Lapangan C'], [
            'location' => 'Indoor',
            'price_per_hour' => 300000,
        ]);

        Court::query()->updateOrCreate(['name' => 'Lapangan D'], [
            'location' => 'Outdoor',
            'price_per_hour' => 300000,
        ]);

        Court::query()->updateOrCreate(['name' => 'Lapangan E'], [
            'location' => 'Indoor',
            'price_per_hour' => 300000,
        ]);

        User::query()->updateOrCreate(['username' => 'user01'], [
            'name' => 'User Demo',
            'phone' => '081234567890',
            'address' => 'Jl. Padel No. 1',
            'gender' => 'male',
            'role' => User::ROLE_USER,
            'email' => 'user01@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::query()->updateOrCreate(['username' => 'kasir01'], [
            'name' => 'Admin Kasir Demo',
            'phone' => '081298765432',
            'address' => 'Jl. Kasir No. 2',
            'gender' => 'female',
            'role' => User::ROLE_ADMIN_KASIR,
            'email' => 'kasir01@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::query()->updateOrCreate(['username' => 'owner01'], [
            'name' => 'Owner Demo',
            'phone' => '081211112222',
            'address' => 'Jl. Owner No. 3',
            'gender' => 'male',
            'role' => User::ROLE_OWNER,
            'email' => 'owner01@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
