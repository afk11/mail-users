<?php

namespace Afk11\Mailman\Db;


use Afk11\Mailman\Console\Config;
use Afk11\Mailman\Entities\VirtualDomain;
use Afk11\Mailman\Entities\VirtualUser;
use Doctrine\DBAL\Connection;

class Db
{
    /**
     * Config value
     * @var Connection
     */
    private $conn;

    /**
     * Config value
     * @var string
     */
    private $tblUsers;

    /**
     * Config value
     * @var string
     */
    private $tblDomains;

    /**
     * Config value
     * @var string
     */
    private $tblAliases;

    /**
     * @var \Doctrine\DBAL\Driver\Statement
     */
    private $sqlListDomains;

    /**
     * @var \Doctrine\DBAL\Driver\Statement
     */
    private $sqlListUsers;

    /**
     * @var \Doctrine\DBAL\Driver\Statement
     */
    private $sqlListUsersByDomain;

    /**
     * @var \Doctrine\DBAL\Driver\Statement
     */
    private $sqlCreateUser;

    /**
     * Db constructor.
     * @param Connection $conn
     * @param Config $config
     */
    public function __construct(Connection $conn, Config $config)
    {
        $this->conn = $conn;
        $this->tblUsers = $config->getValue(Config::TBL_USERS);
        $this->tblAliases = $config->getValue(Config::TBL_ALIASES);
        $this->tblDomains = $config->getValue(Config::TBL_DOMAINS);
        
        $this->sqlCreateUser = $conn->prepare("INSERT INTO :tblUsers (`domain_id`, `password`, `email`) VALUES (:domainId, ENCRYPT(:password, CONCAT('$6$', SUBSTRING(SHA(RAND()), -16))), :email)");
        $this->sqlListUsers = $conn->prepare("SELECT u.id, u.domain_id, u.email, u.password FROM :tblUsers u");
        $this->sqlListUsersByDomain = $conn->prepare("SELECT u.id, u.domain_id, u.email, u.password FROM :tblUsers u WHERE u.domainId = :domainId");

        $this->sqlCreateAlias = $conn->prepare("INSERT INTO :tblAliases (`domain_id`, `source`, `destination`) VALUES (:domainId, :src, :dest)");

        $this->sqlCreateDomain = $conn->prepare("INSERT INTO :tblDomains (`name`) VALUES (:name)");
        $this->sqlListDomains = $conn->prepare("SELECT d.id, d.name FROM :tblDomains d");
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * @param array $row
     * @return VirtualUser
     */
    public function getUserFromRow(array $row)
    {
        return new VirtualUser($row['id'], $row['domainId'], $row['password'], $row['email']);
    }

    /**
     * @param array $row
     * @return VirtualDomain
     */
    public function getDomainFromRow(array $row)
    {
        return new VirtualDomain($row['id'], $row['name']);
    }

    /**
     * @return VirtualDomain[]
     */
    public function listDomains()
    {
        $this->sqlListDomains->execute(['tblDomains' => $this->tblDomains]);
        $rows = $this->sqlListDomains->fetchAll(\PDO::FETCH_ASSOC);
        $results = array_map([$this, 'getDomainFromRow'], $rows);
        return $results;
    }

    /**
     * @return VirtualUser[]
     */
    public function listUsers()
    {
        $this->sqlListUsers->execute(['tblUsers' => $this->tblUsers]);
        $rows = $this->sqlListUsers->fetchAll(\PDO::FETCH_ASSOC);
        $results = array_map([$this, 'getUserFromRow'], $rows);
        return $results;
    }

    /**
     * @return VirtualUser[]
     * @param VirtualDomain $domain
     */
    public function listUsersByDomain(VirtualDomain $domain)
    {
        $this->sqlListUsersByDomain->execute(['tblUsers' => $this->tblUsers, 'domainId'=>$domain->getId()]);
        $rows = $this->sqlListUsers->fetchAll(\PDO::FETCH_ASSOC);
        $results = array_map([$this, 'getUserFromRow'], $rows);
        return $results;
    }

    /**
     * @param NewUserAccount $account
     * @return string
     */
    public function createAccount(NewUserAccount $account)
    {
        $this->sqlCreateUser->execute([
            'tblUsers' => $this->tblUsers,
            'domainId' => $account->getDomainId(),
            'password' => $account->getPassword(),
            'email' => $account->getEmail()
        ]);

        return $this->conn->lastInsertId();
    }

    /**
     * @param NewVirtualAlias $alias
     * @return string
     */
    public function createAlias(NewVirtualAlias $alias)
    {
        $this->sqlCreateUser->execute([
            'tblDomains' => $this->tblDomains,
            'domainId' => $alias->getDomainId(),
            'src' => $alias->getSource(),
            'dest' => $alias->getDestination()
        ]);

        return $this->conn->lastInsertId();
    }

    /**
     * @param string $name
     * @return string
     */
    public function addDomain($name)
    {
        $this->sqlCreateUser->execute([
            'tblDomains' => $this->tblDomains,
            'name' => $name
        ]);

        return $this->conn->lastInsertId();
    }
}