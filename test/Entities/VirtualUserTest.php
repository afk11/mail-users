<?php

namespace Afk11\MailUsers\Tests\Entities;


use Afk11\MailUsers\Entities\VirtualUser;
use Afk11\MailUsers\Tests\AbstractTestCase;

class VirtualUserTest extends AbstractTestCase
{
    public function testMethods()
    {
        $id = 1;
        $domainId = 123;
        $password = 'abcdabcdabcd';
        $email = 'test@example.com';
        $virtualUser = new VirtualUser($id, $domainId, $password, $email);
        $this->assertEquals($id, $virtualUser->getId());
        $this->assertEquals($domainId, $virtualUser->getDomainId());
        $this->assertEquals($password, $virtualUser->getPassword());
        $this->assertEquals($email, $virtualUser->getEmail());

        $aliasMail = 'another@example.com';
        $alias = $virtualUser->createAlias($aliasMail);
        $this->assertEquals($domainId, $alias->getDomainId());
        $this->assertEquals($aliasMail, $alias->getDestination());
        $this->assertEquals($email, $alias->getSource());
    }
}