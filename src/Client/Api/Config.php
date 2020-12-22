<?php

namespace AwemaPL\Prestashop\Client\Api;

use InvalidArgumentException;

class Config
{
    /** @var string $url */
    private $url;

    /** @var string $apiKey */
    private $apiKey;

    /** @var bool $debug */
    private $debug;
    
    public function __construct(array $parameters)
    {
        $this->set($parameters);
    }

    /**
     * Set
     *
     * @param array $parameters
     * @return $this
     */
    public function set(array $parameters): self
    {
        if (!$parameters['url']) {
            throw new InvalidArgumentException('Parameter "url" must be provided in the configuration.');
        } else if (!$parameters['api_key']) {
            throw new InvalidArgumentException('Parameter "api_key" must be provided in the configuration.');
        }
        $this->url = $parameters['url'];
        $this->apiKey = $parameters['api_key'];
        $this->debug = $parameters['debug'] ?? false;
        return $this;
    }

    /**
     * Get URL
     * 
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get API key
     * 
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Is debug
     * 
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }
    
}
