<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;


    public function test_successful_login()
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_successfull_register()
    {
        $admin = User::factory()->create(['role'=> 'admin',]);
        $token = $admin->createToken('auth_token')->plainTextToken;
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/register', [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement(['manager','admin','user']),
            'password' => 'password',
            'password_confirmation'=> 'password'
        ]);
        $response->assertCreated();
    }
}
