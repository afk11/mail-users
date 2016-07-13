<?php

namespace Afk11\MailUsers\Console;


use Afk11\MailUsers\Console\Command\Domain\AddDomainCommand;
use Afk11\MailUsers\Console\Command\Domain\DeleteDomainCommand;
use Afk11\MailUsers\Console\Command\Domain\ListDomainCommand;
use Afk11\MailUsers\Console\Command\Domain\ListUsersCommand;
use Afk11\MailUsers\Console\Command\GenConfigCommand;
use Afk11\MailUsers\Console\Command\User\AddUserCommand;

class Application extends \Symfony\Component\Console\Application
{
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new AddUserCommand();
        $commands[] = new AddDomainCommand();
        $commands[] = new ListDomainCommand();
        $commands[] = new ListUsersCommand();
        $commands[] = new DeleteDomainCommand();
        $commands[] = new GenConfigCommand();;
        return $commands;
    }
}