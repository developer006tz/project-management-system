<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
