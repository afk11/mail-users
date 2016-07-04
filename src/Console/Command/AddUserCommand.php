<?php

namespace Afk11\Mailman\Console\Command;

use Afk11\Mailman\Entities\VirtualDomain;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AddUserCommand extends AbstractCommand
{
    protected $insecurePasswordLength = 6;

    private function promptFor($strName, QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output)
    {
        $question = new Question($strName . ": ");
        $question->setValidator(function ($value) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Email formatted incorrectly');
            }
        });

        return $questionHelper->ask($input, $output, $question);
    }

    private function promptForPassword(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output)
    {
        $question = new Question("Password: ");
        $question->setHidden(true);
        $question->setValidator(function ($pass1) {
            if (strlen($pass1) < $this->insecurePasswordLength) {
                throw new \InvalidArgumentException('Insecure password (< '.$this->insecurePasswordLength.' characters)');
            }
        });
        $pass1 = $questionHelper->ask($input, $output, $question);

        $question2 = new Question("Password: ");
        $question2->setHidden(true);
        $question2->setValidator(function ($pass2) use ($pass1) {
            $random = random_bytes(32);
            if (!hash_equals(hash('sha256', $random.$pass1), hash('sha256', $random.$pass2))) {
                throw new \InvalidArgumentException("Passwords don't match");
            }
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

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = new QuestionHelper();
        $email = $this->promptFor('Email address', $questionHelper, $input, $output);
        $password = $this->promptFor('Email address', $questionHelper, $input, $output);
        $parts = explode("@", $email);

        $db = $this->getDb();
        

        /** @var VirtualDomain[] $domains */
        $domains = $domainRepo->findAll();
        $domain = $this->searchForDomain($domains, $parts[1]);
        $domain->createNewUser($email, $password);
    }
}