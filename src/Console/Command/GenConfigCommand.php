<?php

namespace Afk11\Mailman\Console\Command;

use Afk11\Mailman\Config\Config;
use Afk11\Mailman\Config\ConfigFile;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenConfigCommand extends AbstractCommand
{
    protected $insecurePasswordLength = 6;

    /**
     *
     */
    protected function configure()
    {
        $this->setName('config:gen')
            ->setDescription('Creates a config file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config([
            Config::DB_HOST => '',
            Config::DB_USER => '',
            Config::DB_PASS  => '',
            Config::DB_NAME => '',
            Config::TBL_ALIASES => '',
            Config::TBL_DOMAINS => '',
            Config::TBL_USERS => '',
        ]);

        $output->writeln(ConfigFile::writeConfig($config));
    }
}