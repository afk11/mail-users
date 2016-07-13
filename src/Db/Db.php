<?php

namespace Afk11\MailUsers\Db;


use Afk11\MailUsers\Config\Config;
use Afk11\MailUsers\Entities\VirtualDomain;
use Afk11\MailUsers\Entities\VirtualUser;
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
        
        $this->sqlCreateUser = $conn->prepare("INSERT INTO ".$this->tblUsers." (`domain_id`, `password`, `email`) VALUES (?, ENCRYPT(?, CONCAT('$6$', SUBSTRING(SHA(RAND()), -16))), ?)");
        $this->sqlListUsers = $conn->prepare("SELECT u.id, u.domain_id, u.email, u.password FROM ? u");
        $this->sqlListUsersByDomain = $conn->prepare("SELECT u.id, u.domain_id, u.email, u.password FROM ? u WHERE u.domainId = ?");

        $this->sqlCreateAlias = $conn->prepare("INSERT INTO ".$this->tblUsers." (`domain_id`, `source`, `destination`) VALUES (?,?,?)");

        $this->sqlCreateDomain = $conn->prepare("INSERT INTO ? (name) VALUES (?)");
        $this->sqlListDomains = $conn->prepare("SELECT * FROM :tblDomains");
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
        return new VirtualUser($row['id'], $row['domain_id'], $row['password'], $row['email']);
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
     * @return VirtualUser[]
     */
    public function listUsers()
    {
        $this->sqlListUsers->execute([
            'tblUsers' => $this->tblUsers
        ]);
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
        $results = $this->conn->fetchAll('SELECT u.id, u.domain_id, u.email, u.password FROM '.$this->tblUsers  .' u WHERE u.domain_id = ?', [$domain->getId()]);
        $results = array_map([$this, 'getUserFromRow'], $results);
        return $results;
    }

    /**
     * @param NewUserAccount $account
     * @return string
     */
    public function createAccount(NewUserAccount $account)
    {
        $this->sqlCreateUser->bindParam(1, $account->getDomainId());
        $this->sqlCreateUser->bindParam(2, $account->getPassword());
        $this->sqlCreateUser->bindParam(3, $account->getEmail());
        $this->sqlCreateUser->execute();

        return $this->conn->lastInsertId();
    }

    /**
     * @param string $email
     * @return VirtualUser
     */
    public function fetchAccountByEmail($email)
    {
        $results = $this->conn->fetchAssoc('SELECT u.id, u.email, u.password, u.domainId FROM ' . $this->tblUsers  . ' u where u.email = ?', [$email]);
        $account = new VirtualUser($results['id'], $results['domain_id'], $results['password'], $results['email']);
        return $account;
    }

    /**
     * @param NewVirtualAlias $alias
     * @return string
     */
    public function createAlias(NewVirtualAlias $alias)
    {
        return $this->conn->insert($this->tblDomains, [
            'domainId' => $alias->getDomainId(),
            'src' => $alias->getSource(),
            'dest' => $alias->getDestination()
        ]);
    }

    /**
     * @param VirtualDomain $domain
     * @return int
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function deleteDomain(VirtualDomain $domain)
    {
        return $this->conn->delete($this->tblDomains, ['id' => $domain->getId()]);
    }

    /**
     * @param string $name
     * @return string
     */
    public function addDomain($name)
    {
        return $this->conn->insert($this->tblDomains, [
            'name' => $name
        ]);
    }
    
    /**
     * @return VirtualDomain[]
     */
    public function listDomains()
    {
        return array_map([$this, 'getDomainFromRow'], $this->conn->fetchAll('Select * from ' . $this->tblDomains));
    }
}