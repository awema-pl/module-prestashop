<?php

namespace AwemaPL\Prestashop\Client\Api\Response;

use SimpleXMLElement;

class Response
{
    /** @var SimpleXMLElement $xml */
    private $xml;

   /** @var string $contents */
    private $contents;

   /** @var array $array */
    private $array;

    public function __construct(SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    /**
     * Return the response's body as a `string`.
     *
     * @return string
     */
    public function contents(): string
    {
        if (!$this->contents) {
            $this->contents = $this->xml->asXML();
        }
        return $this->contents;
    }

    /**
     * To array
     *
     * @return array
     */
    public function toArray()
    {
        if (!$this->array) {
            $resource = $this->xml->children()->children();
            $array = json_decode(json_encode($resource), true, 512, JSON_UNESCAPED_UNICODE);
            $hasOneItem = !isset(array_values($array)[0][0]);
            return ($hasOneItem) ? array_values($array) : array_values($array)[0];
        }
        return $this->array;
    }

    /**
     * Return the provided parameter's value from the response's JSON.
     *
     * @param string $parameter
     * @return mixed
     */
    public function getParameter(string $parameter)
    {
        return $this->toArray()[$parameter];
    }

}
