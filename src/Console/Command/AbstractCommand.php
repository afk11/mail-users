<?php

namespace Afk11\Mailman\Console\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    /**
     * @var Connection 
     */
    protected $db;

    /**
     * @param array $conn
     * @return Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private function loadDb(array $conn)
    {
        $connectionParams = array(
            'url' => 'mysql://user:secret@localhost/mydb',
        );
        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
    }

    /**
     * @return Connection
     */
    public function getDb()
    {
        if (null === $this->db) {
            // database configuration parameters
            $conn = array(
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/db.sqlite',
            );

            $this->db = $this->loadDb($conn);
        }

        return $this->db;
    }
}