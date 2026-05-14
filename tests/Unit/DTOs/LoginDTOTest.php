<?php

namespace Tests\Unit\DTOs;

use App\DTOs\Auth\LoginDTO;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class LoginDTOTest extends TestCase
{
    public function test_from_request_creates_dto_with_correct_values(): void
    {
        $request = Request::create('/login', 'POST', [
            'email'    => 'test@example.com',
            'password' => 'secret123',
        ]);

        $dto = LoginDTO::fromRequest($request);

        $this->assertSame('test@example.com', $dto->email);
        $this->assertSame('secret123', $dto->password);
    }

    public function test_dto_properties_are_readonly(): void
    {
        $dto = new LoginDTO('test@example.com', 'secret');

        $this->expectException(\Error::class);
        $dto->email = 'changed@example.com';
    }
}
