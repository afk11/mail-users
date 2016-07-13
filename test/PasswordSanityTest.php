<?php

namespace Afk11\MailUsers\Tests;


use Afk11\MailUsers\Password;

class PasswordSanityTest extends AbstractTestCase
{
    public function testWrapperIsSelfConsistent()
    {
        $password = 'test';

        $pw = new Password();
        $hash = $pw->create($password);

        $this->assertTrue($pw->verify($password, $hash));
        $this->assertFalse($pw->verify('anything else', $hash));
    }

    public function testGeneratedPassword()
    {
        $password = 'password';
        $hash = '$6$32c83249e9882b57$U8qmWN1pfMigQ/Ygosm1sTVVU/qWqBbm.RobDbMi810N8WvorL//5BryO/pN/S3wLRdfanD.8CjCqAjpCnN4b0';
        $pw = new Password();
        $this->assertTrue($pw->verify($password, $hash));
    }
}