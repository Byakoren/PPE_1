<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testResetTokenGetterAndSetter(): void
    {
        $user = new User();

        $this->assertNull($user->getResetToken(), 'Le resetToken est null par défaut.');

        $token = 'sample-token-123';
        $user->setResetToken($token);

        $this->assertSame($token, $user->getResetToken(), 'Le resetToken est correctement défini et récupéré.');
    }

    public function testEmailSetterAndGetter(): void
    {
        $user = new User();
        $email = 'test@example.com';

        $user->setEmail($email);

        $this->assertSame($email, $user->getEmail());
        $this->assertSame($email, $user->getUserIdentifier());
    }
}
