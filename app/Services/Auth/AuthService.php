<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(protected UserRepository $userRepository) {}
    public function attempt(LoginDTO $dto): bool
    {
        $user = $this->userRepository->findByEmail($dto->email);
        if (!$user || !Hash::check($dto->password, $user->password)) {
            return false;
        }
        Auth::login($user);
        return true;
    }
    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
