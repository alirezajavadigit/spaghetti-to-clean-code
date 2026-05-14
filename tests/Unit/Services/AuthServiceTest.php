<?php

namespace Tests\Unit\Services;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->authService    = new AuthService($this->userRepository);
    }

    public function test_attempt_returns_true_with_valid_credentials(): void
    {
        $user           = new User();
        $user->password = Hash::make('password123');

        $this->userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->with('admin@example.com')
            ->andReturn($user);

        Auth::shouldReceive('login')->once()->with($user);

        $dto    = new LoginDTO('admin@example.com', 'password123');
        $result = $this->authService->attempt($dto);

        $this->assertTrue($result);
    }

    public function test_attempt_returns_false_when_user_not_found(): void
    {
        $this->userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->andReturn(null);

        $dto    = new LoginDTO('unknown@example.com', 'password');
        $result = $this->authService->attempt($dto);

        $this->assertFalse($result);
    }

    public function test_attempt_returns_false_with_wrong_password(): void
    {
        $user           = new User();
        $user->password = Hash::make('correctpassword');

        $this->userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->andReturn($user);

        $dto    = new LoginDTO('admin@example.com', 'wrongpassword');
        $result = $this->authService->attempt($dto);

        $this->assertFalse($result);
    }

    public function test_logout_calls_auth_logout(): void
    {
        Auth::shouldReceive('logout')->once();

        $this->authService->logout();

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
