<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
        $this->authService = new AuthService($this->userRepository);
    }

    public function test_authenticate_with_valid_credentials()
    {
        $user = User::factory()->create(['password' => 'password']);

        [$authUser, $token] = $this->authService->authenticate([
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertInstanceOf(User::class, $authUser);
        $this->assertNotEmpty($token);
    }

    public function test_authenticate_throws_exception_with_invalid_credentials()
    {
        $this->expectException(AuthenticationException::class);

        $this->authService->authenticate([
            'email' => 'invalid@gmail.com',
            'password' => 'invalid_password',
        ]);
    }

    public function test_register_creates_new_user_successfully()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'user',
        ];

        $user = $this->authService->register($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertEquals($userData['role'], $user->role);
        $this->assertTrue(Hash::check($userData['password'], $user->password));
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'role' => $userData['role'],
        ]);
    }
}
