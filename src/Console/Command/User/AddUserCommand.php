<?php

namespace Afk11\MailUsers\Console\Command\User;

use Afk11\MailUsers\Console\Command\AbstractCommand;
use Afk11\MailUsers\Entities\VirtualDomain;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AddUserCommand extends AbstractCommand
{
    protected $insecurePasswordLength = 6;

    /**
     *
     */
    protected function configure()
    {
        $this->setName('user:add')
            ->setDescription('Creates a new user account');
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
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Email formatted incorrectly');
            }
            return $value;
        });

        return $questionHelper->ask($input, $output, $question);
    }

    /**
     * @param QuestionHelper $questionHelper
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function promptForPassword(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output)
    {
        $question = new Question("Password: ");
        $question->setHidden(true);
        $question->setValidator(function ($pass1) {
            if (strlen($pass1) < $this->insecurePasswordLength) {
                throw new \InvalidArgumentException('Insecure password (< '.$this->insecurePasswordLength.' characters)');
            }
            return $pass1;
        });
        $pass1 = $questionHelper->ask($input, $output, $question);

        $question2 = new Question("Password (again): ");
        $question2->setHidden(true);
        $question2->setValidator(function ($pass2) use ($pass1) {
            $random = random_bytes(32);
            if (!hash_equals(hash('sha256', $random.$pass1), hash('sha256', $random.$pass2))) {
                throw new \InvalidArgumentException("Passwords don't match");
            }
            return $pass2;
        });
        $questionHelper->ask($input, $output, $question2);
        return $pass1;
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

        throw new \RuntimeException('Unknown domain');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getConfig();

        $questionHelper = new QuestionHelper();
        $email = $this->promptFor('Email address', $questionHelper, $input, $output);
        $password = $this->promptForPassword($questionHelper, $input, $output);
        $parts = explode("@", $email);

        $db = $this->getDb($config);
        $domains = $db->listDomains();
        $domain = $this->searchForDomain($domains, $parts[1]);
        $newUser = $domain->createUser($email, $password);
        $db->createAccount($newUser);
        
        $output->writeln("<info>Account " . $email   . " was created!\n");
    }
}