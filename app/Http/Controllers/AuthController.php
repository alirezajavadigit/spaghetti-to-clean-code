<?php

namespace App\Http\Controllers;

use App\DTOs\Auth\LoginDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function index(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $success = $this->authService->attempt(LoginDTO::fromRequest($request));

        if (!$success) {
            return back()->with("error", __("auth.fail-login"));
        }

        return redirect('/dashboard');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect('/login');
    }
}
