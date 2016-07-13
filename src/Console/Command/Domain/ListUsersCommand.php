<?php

namespace Afk11\Mailman\Console\Command\Domain;

use Afk11\Mailman\Config\Config;
use Afk11\Mailman\Console\Command\AbstractCommand;
use Afk11\Mailman\Entities\VirtualDomain;
use Afk11\Mailman\Entities\VirtualUser;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ListUsersCommand extends AbstractCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('domain:users')
            ->setDescription('Lists users on domain');
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
                throw new \InvalidArgumentException('Please enter a value');
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
        $config = $this->getConfig();

        $db = $this->getDb($config);
        $domains = $db->listDomains();

        $questionHelper = new QuestionHelper();
        $domainStr = $this->promptFor('Domain', $questionHelper, $input, $output);
        $domain = $this->searchForDomain($domains, $domainStr);
        if (!$domain instanceof VirtualDomain) {
            throw new \RuntimeException('Domain not found');
        }

        $users = $db->listUsersByDomain($domain);
        $this->printUsers($users, $output);
    }

    /**
     * @param VirtualUser[] $users
     * @param OutputInterface $output
     */
    private function printUsers(array $users, OutputInterface $output)
    {
        if (count($users) > 0) {
            $output->writeln("User list: ");
            foreach ($users as $user) {
                $output->writeln(" - " . $user->getEmail());
            }
        } else {
            $output->writeln("No users on this domain");
        }
    }
}