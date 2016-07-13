<?php

namespace Afk11\Mailman\Console;


use Afk11\Mailman\Console\Command\Domain\AddDomainCommand;
use Afk11\Mailman\Console\Command\Domain\DeleteDomainCommand;
use Afk11\Mailman\Console\Command\Domain\ListDomainCommand;
use Afk11\Mailman\Console\Command\Domain\ListUsersCommand;
use Afk11\Mailman\Console\Command\User\AddUserCommand;
use Afk11\Mailman\Console\Command\GenConfigCommand;

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