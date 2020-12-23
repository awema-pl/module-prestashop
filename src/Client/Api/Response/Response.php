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
     * To array
     *
     * @return array
     */
    public function toArray()
    {
        if (!$this->array) {
            $dom=new DOMDocument;
            $dom->loadHTML($this->content());
            $xpath = new DOMXPath($dom);
            $nodelist = $xpath->query('//body');
            $this->array = $this->parseXmlToArray($nodelist[0]);
        }
        return $this->array;
    }

    /**
     * Parse XML to array
     *
     * @param XMLReader $oXml
     * @return array
     */
    protected function parseXmlToArray($node) {

        $output =[];
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->parseXmlToArray($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = [];
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v || $v === '0') {
                        $output = (string) $v;
                    }
                }
                if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
                    $output = array('@content'=>$output); //Change output into an array.
                }
                if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }

    /**
     * Return the provided parameter's value from the response's JSON.
     *
     * @param string $parameter
     * @param $default
     * @return mixed
     */
    public function getParameter(string $parameter, $default=null)
    {
        return Arr::get($this->toArray(), $key, $default);
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
