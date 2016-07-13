<?php

namespace Afk11\MailUsers\Entities;

/**
 * @Entity @Table(name="virtual_aliases")
 */
class VirtualAlias
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
    private $source;

    /**
     * @var string
     * @Column(type="string")
     */
    private $destination;

    /**
     * VirtualUser constructor.
     * @param int $id
     * @param int $domainId
     * @param string $source
     * @param string $destination
     */
    public function __construct($id, $domainId, $source, $destination)
    {
        $this->id = $id;
        $this->domainId = $domainId;
        $this->source = $source;
        $this->destination = $destination;
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
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }
}