<?php

namespace Afk11\Mailman\Db;


class NewUserAccount
{
    /**
     * @var int
     */
    private $domainId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * NewUserAccount constructor.
     * @param int $domainId
     * @param string $email
     * @param string $password
     */
    public function __construct($domainId, $email, $password)
    {
        $this->domainId = $domainId;
        $this->email = $email;
        $this->password = $password;
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
}