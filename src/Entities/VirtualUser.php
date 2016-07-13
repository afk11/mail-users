<?php

namespace Afk11\MailUsers\Entities;
use Afk11\MailUsers\Db\NewVirtualAlias;

/**
 * @Entity @Table(name="virtual_users")
 */
class VirtualUser
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    private $id;

    /**
     * @var int
     * @Column(type="integer", name="domain_id")
     */
    private $domainId;

    /**
     * @var string
     * @Column(type="string")
     */
    private $email;

    /**
     * @var string
     * @Column(type="string")
     */
    private $password;

    /**
     * VirtualUser constructor.
     * @param int $id
     * @param int $domainId
     * @param string $password
     * @param string $email
     */
    public function __construct($id, $domainId, $password, $email)
    {
        $this->id = $id;
        $this->domainId = $domainId;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $alias
     * @return NewVirtualAlias
     */
    public function createAlias($alias)
    {
        return new NewVirtualAlias($this->domainId, $this->email, $alias);
    }
}