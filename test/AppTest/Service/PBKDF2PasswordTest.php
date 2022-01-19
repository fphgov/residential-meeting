<?php

declare(strict_types=1);

namespace AppTest\Service;

use App\Exception\IllegalArgumentException;
use App\Model\PBKDF2Password;
use PHPUnit\Framework\TestCase;

final class PBKDF2PasswordTest extends TestCase
{
    public function testCheckPasswordParser()
    {
        $storedPassword = "PBKDF2WITHHMACSHA512:40000:90be05c0b062cdc94fd6124a88f95523:"
            . "4e574b19813e5e5af7ae936a1798b0c12f28b79bb761f32f94270b31"
            . "731f44bca37513855af9c08f7abf09e65bc46f9f1b25d39b31a657b5649c7bc8020e1486"
            . "ae854c5aefdb73a74e4ceb0acd96abee24ca68cf8c0403b7602952f0a0"
            . "bf8662a4c83c4c28ecb0c282d2afe49e71870e260c07f419c9ddd3c63115694864b1e5"; // NOSONAR

        $passwordModel = new PBKDF2Password($storedPassword, PBKDF2Password::PW_REPRESENTATION_STORABLE);

        $this->assertTrue($passwordModel->verify("PASSWORD"));
        $this->assertFalse($passwordModel->verify("password"));
        $this->assertFalse($passwordModel->verify("bar"));
    }

    public function testBadStoredException()
    {
        $this->expectException(IllegalArgumentException::class);

        $storedPassword = "Bad stored password"; // NOSONAR

        $passwordModel = new PBKDF2Password($storedPassword, PBKDF2Password::PW_REPRESENTATION_STORABLE);
    }

    public function testInvalidStoredException()
    {
        $this->expectException(IllegalArgumentException::class);

        $storedPassword = "PBKDF2WITHHMACSHA512:40000:90be05c0b062cdc94fd6124a88f95523"; // NOSONAR

        $passwordModel = new PBKDF2Password($storedPassword, PBKDF2Password::PW_REPRESENTATION_STORABLE);
    }

    public function testUnsupportedProcessException()
    {
        $this->expectException(IllegalArgumentException::class);

        $storedPassword = "MD5:40000:90be05c0b062cdc94fd6124a88f95523:"
            . "4e574b19813e5e5af7ae936a1798b0c12f28b79bb761f32f94270b31"
            . "731f44bca37513855af9c08f7abf09e65bc46f9f1b25d39b31a657b5649c7bc8020e14"
            . "86ae854c5aefdb73a74e4ceb0acd96abee24ca68cf8c0403b7602952f0a0"
            . "bf8662a4c83c4c28ecb0c282d2afe49e71870e260c07f419c9ddd3c63115694864b1e5"; // NOSONAR

        $passwordModel = new PBKDF2Password($storedPassword, PBKDF2Password::PW_REPRESENTATION_STORABLE);
    }

    public function testRecyclability()
    {
        $password = "PASSWORD"; // NOSONAR

        $passwordModel  = new PBKDF2Password($password);
        $storedPassword = $passwordModel->getStorableRepresentation();

        $passwordModelFake = new PBKDF2Password($storedPassword, PBKDF2Password::PW_REPRESENTATION_STORABLE);
        $passwordModelFake->verify($password);

        $this->assertTrue($passwordModel->verify("PASSWORD"));
        $this->assertTrue($passwordModelFake->verify("PASSWORD"));
    }
}
