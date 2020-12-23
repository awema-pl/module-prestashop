<?php

namespace AwemaPL\Prestashop\Client\Api\Response;

use Illuminate\Support\Arr;
use SimpleXMLElement;
use DOMDocument;
use DOMXPath;

class Response
{
    /** @var SimpleXMLElement $xml */
    private $xml;

   /** @var string $content */
    private $content;

   /** @var array $array */
    private $array;

    public function __construct(SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    /**
     * Return the response's body as a `SimpleXMLElement`.
     *
     * @return SimpleXMLElement
     */
    public function xml(): SimpleXMLElement
    {
        return $this->xml;
    }

    /**
     * Return the response's body as a `string`.
     *
     * @return string
     */
    public function content(): string
    {
        if (!$this->content) {
            $this->content = $this->xml->asXML();
        }
        return $this->content;
    }

    /**
     * Return string from XML
     *
     * @param $xpath
     * return string
     */
    public function getString($xpath){
        return trim((string) ($this->xml()->xpath($xpath)[0] ?? ''));
    }

    /**
     * Return integer from XML
     *
     * @param $xpath
     * return int
     */
    public function getInteger($xpath){
        return trim((string) ($this->xml()->xpath($xpath)[0] ?? ''));
    }

    /**
     * Return float from XML
     *
     * @param $xpath
     * return float
     */
    public function getFloat($xpath){
        return (float) ($this->xml()->xpath($xpath)[0] ?? 0);
    }

    /**
     * Return boolean from XML
     *
     * @param $xpath
     * return bool
     */
    public function getBoolean($xpath){
        return (bool) ($this->xml()->xpath($xpath)[0] ?? false);
    }

    /**
     * Return array from XML
     *
     * @param $xpath
     * @return array
     */
    public function getArray($xpath)
    {
            $array = json_decode(json_encode($this->xml()->xpath($xpath), JSON_UNESCAPED_UNICODE), true, 512, JSON_UNESCAPED_UNICODE);
            $hasOneItem = !isset(array_values($array)[0][0]);
            return ($hasOneItem) ? array_values($array) : array_values($array)[0];
    }

    /**
     * Return exist in XML
     *
     * @param $xpath
     * return bool
     */
    public function exist($xpath){
        $xml = $this->xml()->xpath($xpath);
        if (sizeof($xml)>1){
            return true;
        }
        return !empty(trim((string) ($this->xml()->xpath($xpath)[0] ?? '')));
    }

}
