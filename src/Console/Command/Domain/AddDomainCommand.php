<?php

namespace Afk11\Mailman\Console\Command\Domain;

use Afk11\Mailman\Console\Command\AbstractCommand;
use Afk11\Mailman\Entities\VirtualDomain;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AddDomainCommand extends AbstractCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('domain:add')
            ->setDescription('Creates a domain');
    }

    /**
     * @param string $strName
     * @param QuestionHelper $questionHelper
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function promptFor($strName, QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output)
    {
        $question = new Question($strName . ": ");
        $question->setValidator(function ($value) {
            if (empty($value)) {
                throw new \InvalidArgumentException('Must provide a value for domain');
            }

            return $value;
        });

        return $questionHelper->ask($input, $output, $question);
    }

    /**
     * @param VirtualDomain[] $domains
     * @param string $providedDomain
     * @return VirtualDomain
     */
    private function searchForDomain(array $domains, $providedDomain)
    {
        foreach ($domains as $domain) {
            if ($providedDomain === $domain->getName()) {
                return $domain;
            }
        }

        return false;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = new QuestionHelper();
        $domainStr = $this->promptFor('Domain', $questionHelper, $input, $output);

        $config = $this->getConfig();

        $db = $this->getDb($config);
        $domains = $db->listDomains();

        if ($this->searchForDomain($domains, $domainStr) instanceof VirtualDomain) {
            throw new \RuntimeException('Domain already setup');
        }

        $domain = new VirtualDomain($db->addDomain($domainStr), $domainStr);
        $output->writeln("<info>New domain added: ".$domain->getName()."</info>");
    }
}