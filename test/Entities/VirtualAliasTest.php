<?php

namespace Afk11\MailUsers\Tests\Entities;


use Afk11\MailUsers\Entities\VirtualAlias;
use Afk11\MailUsers\Tests\AbstractTestCase;

class VirtualAliasTest extends AbstractTestCase
{
    public function testMethods()
    {
        $id = 500;
        $domainId = 123;
        $src = 'asdfasdfasdf@example.com';
        $dest = 'user@example.com';

        $virtualAlias = new VirtualAlias($id, $domainId, $src, $dest);
        $this->assertEquals($id, $virtualAlias->getId());
        $this->assertEquals($domainId, $virtualAlias->getDomainId());
        $this->assertEquals($src, $virtualAlias->getSource());
        $this->assertEquals($dest, $virtualAlias->getDestination());

    }
}