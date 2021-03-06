<?php

namespace Neleid\ChatworkSDK;

use Neleid\ChatworkSDK\Exception\RequestFailException;

class ChatworkRequest
{
    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_PUT = 'PUT';
    const REQUEST_METHOD_DELETE = 'DELETE';
    const REQUEST_HEADER = 'X-ChatWorkToken';
    const CHATWORK_API_LINK = 'https://api.chatwork.com/';

    /**
     * Default Chatwork api version.
     *
     * @var string
     */
    protected $apiVersion = 'v2';

    /**
     * Request Method.
     *
     * @var string
     */
    protected $method;

    /**
     * Request End point.
     *
     * @var string
     */
    protected $endPoint;

    /**
     * Request Params.
     *
     * @var array
     */
    protected $params = [];

    /**
     * Chatwork Api Key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor.
     *
     * @param $apiKey
     * @param string $method
     */
    public function __construct($apiKey, $method = self::REQUEST_METHOD_GET)
    {
        $this->apiKey = $apiKey;
        $this->method = $method;
    }

    /**
     * Set end point.
     *
     * @param string $endPoint
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    /**
     * Set Params.
     *
     * @param string $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Set Method.
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get header.
     *
     * @return string
     */
    public function getHeader()
    {
        return self::REQUEST_HEADER . ": {$this->apiKey}";
    }

    /**
     * Get url.
     *
     * @return string
     */
    protected function buildUrl()
    {
        return self::CHATWORK_API_LINK . "{$this->apiVersion}/{$this->endPoint}";
    }

    /**
     * Send Request to Chatwork.
     *
     * @throws RequestFailException
     *
     * @return array
     */
    public function send()
    {
        $curl = curl_init();
        $url = $this->buildUrl();
        curl_setopt($curl, CURLOPT_HTTPHEADER, [$this->getHeader()]);

        switch ($this->method) {
            case self::REQUEST_METHOD_GET:
                curl_setopt($curl, CURLOPT_HTTPGET, 1);
                if ($this->params) {
                    $url .= '?' . http_build_query($this->params, '', '&');
                }
                break;
            case self::REQUEST_METHOD_POST:
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($this->params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->params, '', '&'));
                }
                break;
            case self::REQUEST_METHOD_PUT:
                curl_setopt($curl, CURLOPT_PUT, 1);
                if ($this->params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->params, '', '&'));
                }
                break;
            case self::REQUEST_METHOD_DELETE:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::REQUEST_METHOD_DELETE);
                if ($this->params) {
                    $url .= '?' . http_build_query($this->params, '', '&');
                }
                break;
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        if (!ChatworkSDK::getSslVerificationMode()) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        }
        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        $headers = substr($response, 0, $info['header_size']);
        $headers = $this->parse_headers($headers);

        $body = substr($response, $info['header_size']);
        $body = json_decode($body, 1);

        curl_close($curl);
        if ($info['http_code'] >= 400) {
            $error = $body['errors'];

            throw new RequestFailException();
        }

        return [
            'http_code'              => $info['http_code'],
            'response'               => $body,
            'usage_limit_max'        => isset($headers['X-RateLimit-Limit']) ? $headers['X-RateLimit-Limit'] : null,
            'usage_limit_remaining'  => isset($headers['X-RateLimit-Remaining']) ? $headers['X-RateLimit-Remaining'] : null,
            'usage_limit_reset_time' => isset($headers['X-RateLimit-Reset']) ? $headers['X-RateLimit-Reset'] : null,
        ];
    }

    /**
     * Parse response header.
     *
     * @param $headers
     * @return array
     */
    protected function parse_headers($headers)
    {
        $result = [];
        foreach (explode("\r\n", $headers) as $header) {
            $header = explode(':', $header, 2);
            $name = trim($header[0]);
            $value = isset($header[1]) ? trim($header[1]) : '';

            if (!$name) {
                continue;
            }

            if (array_key_exists($name, $result)) {
                $result[$name] .= ', ' . $value;
            } else {
                $result[$name] = $value;
            }
        }
        return $result;
    }
}
