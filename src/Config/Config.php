<?php

namespace Afk11\MailUsers\Config;


class Config
{
    const DB_HOST = 'db_host';
    const DB_USER = 'db_user';
    const DB_PASS = 'db_pass';
    const DB_NAME = 'db_name';
    const TBL_USERS = 'tbl_users';
    const TBL_ALIASES = 'tbl_aliases';
    const TBL_DOMAINS = 'tbl_domains';

    /**
     * @var array
     */
    private $values = [];

    /**
     * Config constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        foreach ($this->getKeys() as $param) {
            if (!isset($values[$param])) {
                throw new \RuntimeException('Missing config param: ' . $param);
            }
        }

        $this->values = $values;
    }

    /**
     * @return array
     */
    private function getKeys()
    {
        return [self::DB_HOST,self::DB_NAME,self::DB_PASS,self::DB_NAME];
    }

    /**
     * @param string $param
     * @return string
     */
    public function getValue($param)
    {
        if (!isset($this->values[$param])) {
            throw new \RuntimeException('Missing config param: ' . $param);
        }

        return $this->values[$param];
    }
}