<?php

namespace Afk11\MailUsers\Config;


class ConfigFile
{

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $config;

    /**
     * ConfigFile constructor.
     * @param string $configDir
     */
    public function __construct($configDir)
    {
        $this->directory = $configDir;
        $this->config = $this->directory . "config";
    }

    /**
     * @return bool
     */
    public function checkFileExists()
    {
        return file_exists($this->config) && is_file($this->config);
    }

    /**
     * @return Config
     */
    public function readConfig()
    {
        if (!$this->checkFileExists()) {
            throw new \RuntimeException('File does not exist');
        }
        $contents = file_get_contents($this->config);
        $decoded = json_decode($contents, true);
        if (json_last_error() !== JSON_ERROR_NONE || $decoded === false) {
            throw new \RuntimeException('Invalid json in config file');
        }

        return new Config($decoded);
    }

    /**
     * @param Config $config
     * @return bool
     */
    public static function writeConfig(Config $config)
    {
        $data = [];
        foreach ([Config::DB_HOST, Config::DB_NAME, Config::DB_USER, Config::DB_PASS, Config::TBL_USERS, Config::TBL_DOMAINS, Config::TBL_ALIASES] as $key) {
            $data[$key] = $config->getValue($key);
        }

        $json = json_encode($data, JSON_PRETTY_PRINT);
        return $json;
    }
}