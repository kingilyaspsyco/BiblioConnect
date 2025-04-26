<?php

namespace App\Tests\User;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class LoginTest extends TestCase
{
    public function testLoginValidUser(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));

        $this->assertTrue(password_verify('password123', $user->getPassword()));
    }

    public function testLoginInvalidPassword(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));

        $this->assertFalse(password_verify('wrongpassword', $user->getPassword()));
    }
}
