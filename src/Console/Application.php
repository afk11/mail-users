<?php

namespace Afk11\Mailman\Console;


class Application extends \Symfony\Component\Console\Application
{
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        return $commands;
    }
}