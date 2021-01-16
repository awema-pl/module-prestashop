<?php

namespace AwemaPL\Prestashop\Client\Api;

use Illuminate\Support\Arr;
use MrGenis\Library\XmlToArray;
use RuntimeException;
use SimpleXMLElement;
use CurlFile;

class Client
{

    /** @var string PS version */
    protected $version;
    
    /** @var Config $config */
    protected $config;

    /** @var array compatible versions of PrestaShop WebService */
    const PS_COMPATIBLE_VERSION_MIN = '1.4.0.0';
    const PS_COMPATIBLE_VERSION_MAX = '1.7.99.99';

    public function __construct(Config $config)
    {
        if (!extension_loaded('curl')) {
            $exception = 'Please activate the PHP extension \'curl\' to allow use of PrestaShop WebService library';
            throw new RuntimeException($exception);
        }
        $this->config = $config;
        $this->version = 'unknown';
    }

    /**
     * Check connection
     *
     * @throws PrestashopApiException
     */
    public function checkConnection()
    {
        return !!$this->get(['resource' => '/']);
    }

    /**
     * Take the status code and throw an exception if the server didn't return 200 or 201 code
     *
     * @param int $status_code Status code of an HTTP return
     * @return boolean
     * @throws PrestashopApiException
     */
    protected function checkRequest($request)
    {
        if ($request['status_code'] === 200 || $request['status_code'] === 201) {
            return true;
        }
        $messages = [
            204 => 'No content.',
            400 => 'Bad Request.',
            401 => 'Unauthorized.',
            404 => 'Not Found.',
            405 => 'Method Not Allowed.',
            500 => 'Internal Server Error.',
            503 => 'Service Unavailable.',
            504 => 'Gateway timeout.',
        ];
        $prestashopUserMessages = [
            18 => _p('prestashop::exceptions.user.shop.invalid_api_key_format', 'PrestaShop API error. Invalid API key format.'),
            20 => _p('prestashop::exceptions.user.shop.api_key_is_not_active', 'PrestaShop API error. API key is not active.'),
            21 => _p('prestashop::exceptions.user.shop.no_permission_api_key', 'PrestaShop API error. No permission for this API key. Please set the permissions in the PrestaShop admin panel.'),
            22 => _p('prestashop::exceptions.user.shop.api_is_disabled', 'The PrestaShop API is disabled. Please activate it in the PrestaShop Back Office.'),
        ];
        $details = isset($request['response']) ? $this->parseXML($request['response'], true) : null;
        $httpStatus = $request['status_code'];
        $statusMsg = isset($messages[$httpStatus]) ? sprintf(' %s %s', $httpStatus, $messages[$httpStatus]) : $httpStatus;
        $message = 'Error request to the PrestaShop API.' . $statusMsg;
        if (!isset($details->errors->error)) {
            throw new PrestashopApiException($message, PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, $request['status_code'], null, null, optional($details)->asXML(), $this->config->isDebug());
        } else {
            $prestashopCode = (int)$details->errors->error->code;
            $errorMessage = sprintf('%s (%s).', $details->errors->error->message, $prestashopCode);
            $message .= sprintf(' %s', $errorMessage);
            $errorUserMessage = isset($prestashopUserMessages[$prestashopCode]) ? $prestashopUserMessages[$prestashopCode] : _p('prestashop::exceptions.user.shop.error_request_prestashop_api', 'Error request to the PrestaShop API. :error', ['error' => $errorMessage]);
            throw new PrestashopApiException($message, PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, $request['status_code'], null, $errorUserMessage, optional($details)->asXML(), $this->config->isDebug());
        }
    }

    /**
     * Throws exception if prestashop version is not supported
     *
     * @param int $version The prestashop version
     * @throws PrestashopApiException
     */
    public function isPrestashopVersionSupported($version)
    {
        if (version_compare($version, self::PS_COMPATIBLE_VERSION_MIN, '>=') === false ||
            version_compare($version, self::PS_COMPATIBLE_VERSION_MAX, '<=') === false
        ) {
            throw new PrestashopApiException(sprintf('PrestaShop error. Incompatible version (min:%s, max:%s).', self::PS_COMPATIBLE_VERSION_MIN, self::PS_COMPATIBLE_VERSION_MAX), PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, 400,
                null, _p('prestashop::exceptions.user.shop.error_incompatibile_version', 'PrestaShop error. Incompatible version (minimum version: :min, maksimum version: :max).', [
                    'min' => self::PS_COMPATIBLE_VERSION_MIN,
                    'max' => self::PS_COMPATIBLE_VERSION_MAX
                ]), null, $this->config->isDebug());
        }
    }

    /**
     * Prepares and validate a CURL request to PrestaShop WebService. Can throw exception.
     *
     * @param string $url Resource name
     * @param mixed $curl_params CURL parameters (sent to curl_set_opt)
     * @return array status_code, response
     * @throws PrestashopApiException
     */
    protected function executeRequest($url, $curl_params = [])
    {
        $defaultParams = [
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->config->getApiKey() . ':',
            CURLOPT_HTTPHEADER => ['Expect:'],
            CURLOPT_SSL_VERIFYPEER => config('app.env') === 'local' ? 0 : 1,
            CURLOPT_SSL_VERIFYHOST => config('app.env') === 'local' ? 0 : 2
            // value 1 is not accepted https://curl.haxx.se/libcurl/c/CURLOPT_SSL_VERIFYHOST.html
        ];
        $curl_options = [];
        foreach ($defaultParams as $defkey => $defval) {
            if (isset($curl_params[$defkey])) {
                $curl_options[$defkey] = $curl_params[$defkey];
            }
            else {
                $curl_options[$defkey] = $defaultParams[$defkey];
            }
        }
        foreach ($curl_params as $defkey => $defval) {
            if (!isset($curl_options[$defkey])) {
                $curl_options[$defkey] = $curl_params[$defkey];
            }
        }
        list($response, $info, $error) = $this->executeCurl($url, $curl_options);
        $status_code = $info['http_code'];
        if ($status_code === 0 || $error) {
            $httpStatus = ($status_code) ? $status_code : 400;
            throw new PrestashopApiException('Error request to the PrestaShop API. ' . $error, PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, $httpStatus,
                null, _p('prestashop::exceptions.user.shop.error_request_prestashop_api', 'Error request to the PrestaShop API. :error', ['error' => $error]), null, $this->config->isDebug());
        }
        $index = $info['header_size'];
        if ($index === false && $curl_params[CURLOPT_CUSTOMREQUEST] !== 'HEAD') {
            throw new PrestashopApiException('Error request to the PrestaShop API. Bad HTTP response.', PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, 400,
                null, _p('prestashop::exceptions.user.shop.error_request_prestashop_api', 'Error request to the PrestaShop API. :error', ['error' => 'Bad HTTP response']), null, $this->config->isDebug());
        }
        $header = substr($response, 0, $index);
        $body = substr($response, $index);
        $headerArray = [];
        foreach (explode("\n", $header) as $headerItem) {
            $tmp = explode(':', $headerItem, 2);
            if (count($tmp) === 2) {
                $tmp = array_map('trim', $tmp);
                $headerArray[$tmp[0]] = $tmp[1];
            }
        }
        if (array_key_exists('PSWS-Version', $headerArray)) {
            $this->isPrestashopVersionSupported($headerArray['PSWS-Version']);
            $this->version = $headerArray['PSWS-Version'];
        }
        $this->dumpDebug('HTTP REQUEST HEADER', $info['request_header']);
        $this->dumpDebug('HTTP RESPONSE HEADER', $header);
        if ($curl_params[CURLOPT_CUSTOMREQUEST] == 'PUT' || $curl_params[CURLOPT_CUSTOMREQUEST] == 'POST') {
            $this->dumpDebug('XML SENT',json_encode($curl_params[CURLOPT_POSTFIELDS], JSON_UNESCAPED_UNICODE));
        }
        if ($curl_params[CURLOPT_CUSTOMREQUEST] != 'DELETE' && $curl_params[CURLOPT_CUSTOMREQUEST] != 'HEAD') {
            $this->dumpDebug('RETURN HTTP BODY', $body);
        }
        return [
            'status_code' => $status_code,
            'response' => $body,
            'header' => $header,
            'headers' => $headerArray
        ];
    }

    /**
     * Executes the CURL request to PrestaShop WebService.
     *
     * @param string $url Resource name
     * @param mixed $options CURL parameters (sent to curl_setopt_array)
     * @return array response, info
     */
    protected function executeCurl($url, array $options = [])
    {
        $session = curl_init($url);
        if (count($options)) {
            curl_setopt_array($session, $options);
        }
        $response = curl_exec($session);
        $error = false;
        $info = curl_getinfo($session);
        if ($response === false) {
            $error = curl_error($session);
        }
        curl_close($session);
        return [
            $response,
            $info,
            $error
        ];
    }

    /**
     * Dump debug
     *
     * @param $title
     * @param $content
     */
    public function dumpDebug($title, $content)
    {
        if ($this->config->isDebug()) {
            dump('START ' . $title . "\n" . $content . "\n" . 'END ' . $title . "\n" . "\n");
        }
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Load XML from string. Can throw exception
     *
     * @param string $content String from a CURL response
     * @param boolean $suppressExceptions Whether to throw exceptions on errors
     * @return SimpleXMLElement status_code, response
     * @throws PrestashopApiException
     */
    protected function parseXML($content, $suppressExceptions = false)
    {
        if ($content != '') {
            libxml_clear_errors();
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (libxml_get_errors()) {
                $msg = var_export(libxml_get_errors(), true);
                libxml_clear_errors();
                if (!$suppressExceptions) {
                    throw new PrestashopApiException('Error PrestaShop. HTTP XML response is not parsable.', PrestashopApiException::ERROR_XML_PROCESSING, 400,
                        null, _p('prestashop::exceptions.user.shop.http_xml_response_is_not_parsable', 'Error PrestaShop. HTTP XML response is not parsable.'), $msg, $this->config->isDebug());
                }
            }
            return $xml;
        }
        elseif (!$suppressExceptions) {
            throw new PrestashopApiException('Error PrestaShop. HTTP response is empty.', PrestashopApiException::ERROR_XML_PROCESSING, 400,
                null, _p('prestashop::exceptions.user.shop.http_response_is_empty', 'Error PrestaShop. HTTP response is empty.'), null, $this->config->isDebug());
        }
        return null;
    }

    /**
     * Add (POST) a resource
     * <p>Unique parameter must take : <br><br>
     * 'resource' => Resource name<br>
     * 'postXml' => Full XML string to add resource<br><br>
     * Examples are given in the tutorial</p>
     *
     * @param array $options
     * @return SimpleXMLElement status_code, response
     * @throws PrestashopApiException
     */
    public function add($options)
    {
        $xml = '';
        if (isset($options['resource'], $options['postXml']) || isset($options['url'], $options['postXml'])) {
            $url = (isset($options['resource']) ? $this->config->getUrl() . '/api/' . $options['resource'] : $options['url']);
            $xml = $options['postXml'];
            if (isset($options['id_shop'])) {
                $url .= '&id_shop=' . $options['id_shop'];
            }
            if (isset($options['id_group_shop'])) {
                $url .= '&id_group_shop=' . $options['id_group_shop'];
            }
        }
        else {
            throw new PrestashopApiException('Error PrestaShop. Bad parameters given.', PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, 400,
                null, _p('prestashop::exceptions.user.shop.bad_parameters_given', 'Error PrestaShop. Bad parameters given.'), null, $this->config->isDebug());
        }
        $request = $this->executeRequest($url, [
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xml,
        ]);
        $this->checkRequest($request);
        return $this->parseXML($request['response']);
    }

    /**
     * Retrieve (GET) a resource
     * <p>Unique parameter must take : <br><br>
     * 'url' => Full URL for a GET request of WebService (ex: http://mystore.com/api/customers/1/)<br>
     * OR<br>
     * 'resource' => Resource name,<br>
     * 'id' => ID of a resource you want to get</p>
     * @param array $options Array representing resource to get.
     * @return SimpleXMLElement status_code, response
     * @throws PrestashopApiException
     */
    public function get($options)
    {
        if (isset($options['url'])) {
            $url = $options['url'];
        }
        elseif (isset($options['resource'])) {
            $url = $this->config->getUrl() . '/api/' . $options['resource'];
            $url_params = [];
            if (isset($options['id'])) {
                $url .= '/' . $options['id'];
            }
            $params = [
                'filter',
                'display',
                'sort',
                'limit',
                'id_shop',
                'id_group_shop',
                'date',
                'price'
            ];
            foreach ($params as $p) {
                foreach ($options as $k => $o) {
                    if (strpos($k, $p) !== false) {
                        $url_params[$k] = $options[$k];
                    }
                }
            }
            if (count($url_params) > 0) {
                $url .= '?' . http_build_query($url_params);
            }
        }
        else {
            throw new PrestashopApiException('Error PrestaShop. Bad parameters given.', PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, 400,
                null, _p('prestashop::exceptions.user.shop.bad_parameters_given', 'Error PrestaShop. Bad parameters given.'), null, $this->config->isDebug());
        }
        $request = $this->executeRequest($url, [CURLOPT_CUSTOMREQUEST => 'GET']);
        $this->checkRequest($request);
        return $this->parseXML($request['response']);
    }

    /**
     * Is associative array
     *
     * @param array $array
     * @return bool
     */
    public function isAssociativeArray(array $array)
    {
        return array_values($array) === $array;
    }

    /**
     * Head method (HEAD) a resource
     *
     * @param array $options Array representing resource for head request.
     * @return SimpleXMLElement status_code, response
     * @throws PrestashopApiException
     */
    public function head($options)
    {
        if (isset($options['url'])) {
            $url = $options['url'];
        }
        elseif (isset($options['resource'])) {
            $url = $this->config->getUrl() . '/api/' . $options['resource'];
            $url_params = [];
            if (isset($options['id'])) {
                $url .= '/' . $options['id'];
            }
            $params = [
                'filter',
                'display',
                'sort',
                'limit'
            ];
            foreach ($params as $p) {
                foreach ($options as $k => $o) {
                    if (strpos($k, $p) !== false) {
                        $url_params[$k] = $options[$k];
                    }
                }
            }
            if (count($url_params) > 0) {
                $url .= '?' . http_build_query($url_params);
            }
        }
        else {
            throw new PrestashopApiException('Error PrestaShop. Bad parameters given.', PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, 400,
                null, _p('prestashop::exceptions.user.shop.bad_parameters_given', 'Error PrestaShop. Bad parameters given.'), null, $this->config->isDebug());
        }
        $request = $this->executeRequest($url, [
            CURLOPT_CUSTOMREQUEST => 'HEAD',
            CURLOPT_NOBODY => true,
        ]);
        $this->checkRequest($request);
        return $request['header'];
    }

    /**
     * Edit (PUT) a resource
     * <p>Unique parameter must take : <br><br>
     * 'resource' => Resource name ,<br>
     * 'id' => ID of a resource you want to edit,<br>
     * 'putXml' => Modified XML string of a resource<br><br>
     * Examples are given in the tutorial</p>
     *
     * @param array $options Array representing resource to edit.
     * @return SimpleXMLElement
     * @throws PrestashopApiException
     */
    public function edit($options)
    {
        $xml = '';
        if (isset($options['url'])) {
            $url = $options['url'];
        }
        elseif ((isset($options['resource'], $options['id']) || isset($options['url'])) && $options['putXml']) {
            if (isset($options['url'])) {
                $url = $options['url'];
            }
            else {
                $url = $this->config->getUrl() . '/api/' . $options['resource'] . '/' . $options['id'];
            }
            $xml = $options['putXml'];
            if (isset($options['id_shop'])) {
                $url .= '&id_shop=' . $options['id_shop'];
            }
            if (isset($options['id_group_shop'])) {
                $url .= '&id_group_shop=' . $options['id_group_shop'];
            }
        }
        else {
            throw new PrestashopApiException('Error PrestaShop. Bad parameters given.', PrestashopApiException::ERROR_REQUEST_API_PRESTASHOP, 400,
                null, _p('prestashop::exceptions.user.shop.bad_parameters_given', 'Error PrestaShop. Bad parameters given.'), null, $this->config->isDebug());
        }
        $request = $this->executeRequest($url, [
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $xml,
        ]);
        $this->checkRequest($request);
        return $this->parseXML($request['response']);
    }

    /**
     * Delete (DELETE) a resource.
     * Unique parameter must take : <br><br>
     * 'resource' => Resource name<br>
     * 'id' => ID or array which contains IDs of a resource(s) you want to delete
     * @param array $options Array representing resource to delete.
     * @return bool
     */
    public function delete($options)
    {
        if (isset($options['url'])) {
            $url = $options['url'];
        }
        elseif (isset($options['resource']) && isset($options['id'])) {
            if (is_array($options['id'])) {
                $url = $this->config->getUrl() . '/api/' . $options['resource'] . '/?id=[' . implode(',', $options['id']) . ']';
            }
            else {
                $url = $this->config->getUrl() . '/api/' . $options['resource'] . '/' . $options['id'];
            }
        }
        if (isset($options['id_shop'])) {
            $url .= '&id_shop=' . $options['id_shop'];
        }
        if (isset($options['id_group_shop'])) {
            $url .= '&id_group_shop=' . $options['id_group_shop'];
        }
        $request = $this->executeRequest($url, [CURLOPT_CUSTOMREQUEST => 'DELETE']);
        $this->checkRequest($request);
        return true;
    }

    /**
     * Add file
     * 
     * @param array $options
     * @return bool
     * @throws PrestashopApiException
     */
    public function addFile(array $options)
    {
        if (isset($options['url'])) {
            $url = $options['url'];
        }
        elseif (isset($options['resource']) && isset($options['id'])) {
            $url = $this->config->getUrl() . '/api/' . $options['resource'] . '/' . $options['id'];
        }
        if (isset($options['id_shop'])) {
            $url .= '&id_shop=' . $options['id_shop'];
        }
        if (isset($options['id_group_shop'])) {
            $url .= '&id_group_shop=' . $options['id_group_shop'];
        }
        $request = $this->executeRequest($url, [
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [$options['field'] =>new CurlFile($options['path'], mime_content_type($options['path']))],
        ]);
        $this->checkRequest($request);
        return true;
    }
    
    /**
     * Retrieve the resource schema
     *
     * @param $resource , $schema
     * @return SimpleXMLElement
     * @throws PrestashopApiException
     */
    public function getSchema($resource, $schema = 'blank')
    {
        return $this->get(['resource' => $resource . "?schema=$schema"]);
    }

    /**
     * Fill the provided schema with an associative array data, also remove the useless XML nodes if
     * the corresponding flag is true
     *
     * @param SimpleXMLElement $xmlSchema
     * @param array $data
     * @param bool $removeUselessNodes set true if you want to remove nodes that are not present in the data array
     * @param array $removeSpecificNodes If $removeUselessNodes is false you may add here the first level nodes that
     *                                   you want to remove
     * @return SimpleXMLElement
     */
    public function fillSchema(
        SimpleXMLElement $xmlSchema,
        $data,
        $removeUselessNodes = true,
        $removeSpecificNodes = []
    )
    {
        $resource = $xmlSchema->children()->children();
        foreach ($data as $key => $value) {
            $this->processNode($resource, $key, $value);
        }
        if ($removeUselessNodes) {
            $this->checkForUselessNodes($resource, $data);
        }
        else {
            $this->removeSpecificNodes($resource, $removeSpecificNodes);
        }
        return $xmlSchema;
    }

    /**
     * Get language value
     *
     * @param string|array $data
     * @param $languageId
     * @return string
     */
    private function getLanguageValue($data, $languageId)
    {
        if (is_string($data)) {
            return $data;
        }
        if (array_key_exists($languageId, $data)) {
            return $data[$languageId];
        }
        else {
            return $data[1];
        }
    }

    /**
     * Fill language node
     *
     * @param $node
     * @param $data
     */
    private function fillLanguageNode($node, $data)
    {
        for ($i = 0; $i < count($node->language); $i++) {
            $node->language[$i] = $this->getLanguageValue($data, (int)$node->language[$i]['id']->__toString());
        }
    }

    /**
     * Process node
     *
     * @param SimpleXMLElement $node
     * @param $dataKey
     * @param $dataValue
     */
    private function processNode(SimpleXMLElement $node, $dataKey, $dataValue)
    {
        if (is_int($dataKey)) {
            if ($dataKey === 0) {
                $this->emptyNode($node);
            }
            $this->createNode($node, $dataValue);
        }
        elseif (property_exists($node->$dataKey, 'language')) {
            $this->fillLanguageNode($node->$dataKey, $dataValue);
        }
        elseif (is_array($dataValue)) {
            foreach ($dataValue as $key => $value) {
                $this->processNode($node->$dataKey, $key, $value);
            }
        }
        else {
            $node->$dataKey = $dataValue;
        }
    }

    /**
     * Remove XML first level nodes that are not present int the data array
     *
     * @param SimpleXMLElement $resource
     * @param $data
     */
    private function checkForUselessNodes(SimpleXMLElement $resource, $data)
    {
        $uselessNodes = [];
        foreach ($resource as $key => $value) {
            if (!array_key_exists($key, $data)) {
                $uselessNodes[] = $key;
            }
        }
        foreach ($uselessNodes as $key) {
            unset($resource->$key);
        }
    }

    /**
     * Remove the given nodes from the resource
     * @param $resource
     * @param $removeSpecificNodes
     */
    private function removeSpecificNodes($resource, $removeSpecificNodes)
    {
        foreach ($removeSpecificNodes as $node) {
            unset($resource->$node);
        }
    }

    /**
     * Create node
     *
     * @param SimpleXMLElement $node
     * @param array $dataValue
     */
    private function createNode(SimpleXMLElement $node, $dataValue)
    {
        foreach ($dataValue as $key => $value) {
            if (is_array($value)) {
                if (is_int($key)) {
                    $this->createNode($node, $value);
                }
                else {
                    $childNode = $node->addChild($key);
                    $this->createNode($childNode, $value);
                }
            }
            else {
                $node->addChild($key, $value);
            }
        }
    }

    /**
     * Empty node
     *
     * @param SimpleXMLElement $node
     */
    private function emptyNode(SimpleXMLElement $node)
    {
        $nodeNames = [];
        foreach ($node->children() as $key => $value) {
            $nodeNames[] = $key;
        }
        foreach ($nodeNames as $nodeName) {
            unset($node->$nodeName);
        }
    }
}
