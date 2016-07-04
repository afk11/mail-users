<?php

namespace Afk11\Mailman\Db;

class NewVirtualAlias
{

    /**
     * @var int
     */
    private $domainId;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $destination;

    /**
     * VirtualAlias constructor.
     * @param int $domainId
     * @param string $source
     * @param string $destination
     */
    public function __construct($domainId, $source, $destination)
    {
        $this->domainId = $domainId;
        $this->source = $source;
        $this->destination = $destination;
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