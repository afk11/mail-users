<?php

namespace Afk11\MailUsers\Console\Command\Domain;

use Afk11\MailUsers\Console\Command\AbstractCommand;
use Afk11\MailUsers\Entities\VirtualDomain;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DeleteDomainCommand extends AbstractCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('domain:rm')
            ->setDescription('Removes a domain');
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
        $domain = $this->searchForDomain($domains, $domainStr);
        if (!$domain instanceof VirtualDomain) {
            throw new \RuntimeException('Domain not found');
        }

        $msg = $db->deleteDomain($domain) ? '<info>Domain removed</info>' : '<warning>Unable to remove domain</warning>';
        $output->writeln($msg);
    }
}