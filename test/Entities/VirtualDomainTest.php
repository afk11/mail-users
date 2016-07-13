<?php

namespace Afk11\MailUsers\Tests\Entities;


use Afk11\MailUsers\Entities\VirtualDomain;
use Afk11\MailUsers\Tests\AbstractTestCase;

class VirtualDomainTest extends AbstractTestCase
{
    public function testMethods()
    {
        $id = 123;
        $name = 'example.com';
        $virtualDomain = new VirtualDomain($id, $name);
        $this->assertEquals($id, $virtualDomain->getId());
        $this->assertEquals($name, $virtualDomain->getName());

        $email = 'newuser@example.com';
        $password = 'password';
        $newAccount = $virtualDomain->createUser($email, $password);
        $this->assertEquals($id, $newAccount->getDomainId());
        $this->assertEquals($email, $newAccount->getEmail());
        $this->assertEquals($password, $newAccount->getPassword());
    }
}