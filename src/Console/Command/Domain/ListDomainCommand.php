<?php

namespace Afk11\Mailman\Console\Command\Domain;

use Afk11\Mailman\Config\Config;
use Afk11\Mailman\Console\Command\AbstractCommand;
use Afk11\Mailman\Entities\VirtualDomain;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListDomainCommand extends AbstractCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('domain:ls')
            ->setDescription('Lists domains set up');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getConfig();

        $db = $this->getDb($config);
        $domains = $db->listDomains();
        $this->printDomains($domains, $output);
    }

    /**
     * @param VirtualDomain[] $domains
     * @param OutputInterface $output
     */
    private function printDomains(array $domains, OutputInterface $output)
    {
        $output->writeln("Domain list: ");
        foreach ($domains as $domain) {
            $output->writeln(" - " . $domain->getName());
        }
    }
}