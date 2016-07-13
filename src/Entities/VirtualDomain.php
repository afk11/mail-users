<?php

namespace Afk11\MailUsers\Entities;
use Afk11\MailUsers\Db\NewUserAccount;

/**
 * Class VirtualDomain
 * @package Afk11\Mailman
 * @Entity @Table(name="virtual_domains")
 */
class VirtualDomain
{

    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @Column(type="string")
     */
    private $name;

    /**
     * VirtualDomain constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $email
     * @param string $password
     * @return NewUserAccount
     */
    public function createUser($email, $password)
    {
        return new NewUserAccount($this->id, $email, $password);
    }
}