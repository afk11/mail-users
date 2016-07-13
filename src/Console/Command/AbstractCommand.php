<?php

namespace Afk11\Mailman\Console\Command;

use Afk11\Mailman\Config\Config;
use Afk11\Mailman\Config\ConfigFile;
use Afk11\Mailman\Db\Db;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    /**
     * @var Connection 
     */
    protected $db;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @return Db
     */
    public function getDb(Config $config)
    {
        if (null === $this->db) {
            // database configuration parameters
            $conn = array(
                'url' => sprintf(
                    'mysql://%s:%s@%s/%s',
                    $config->getValue(Config::DB_USER),
                    $config->getValue(Config::DB_PASS),
                    $config->getValue(Config::DB_HOST),
                    $config->getValue(Config::DB_NAME)
                )
            );

            $this->db = new Db(\Doctrine\DBAL\DriverManager::getConnection($conn), $config);
        }

        return $this->db;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (null === $this->config) {
            $reader = new ConfigFile(getenv('HOME') . "/.mailman/");
            $this->config = $reader->readConfig();
        }

        return $this->config;
    }
}