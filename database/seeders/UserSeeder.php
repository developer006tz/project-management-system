<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    protected $password = '12345678';

    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make($this->password),
                'role' => 'admin',
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@gmail.com',
                'password' => Hash::make($this->password),
                'role' => 'manager',
            ],
            [
                'name' => 'User User',
                'email' => 'user@gmail.com',
                'password' => Hash::make($this->password),
                'role' => 'user',
            ],

        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
