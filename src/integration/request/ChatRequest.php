<?php


namespace app\src\integration\request;


use yii\helpers\Json;

/**
 * Class ChatRequest
 * @package app\src\integration\request
 */
class ChatRequest extends BaseRequest
{
    private string $date;
    private string $checksum;
    private string $xSignature;

    /**
     * ChatRequest constructor.
     * @param $authToken
     * @param $method
     * @param $domain
     * @param $route
     * @param null $body
     */
    public function __construct($authToken, $method, $domain, $route, $body = null)
    {
        $this->date = date('r', time());
        $this->checksum = strtolower(md5(Json::encode($body)));
        $this->xSignature = $this->setXSignature($method, $route, $authToken);

        parent::__construct($authToken, $method, $domain.$route, $body);
    }

    /**
     * @param $headers
     * @return array
     */
    public function setHeaders($headers): array
    {
        return parent::setHeaders([
            'Date' => $this->date,
            'Content-MD5' => $this->checksum,
            'X-Signature' => $this->xSignature
        ]);
    }

    /**
     * @param $method
     * @param $url
     * @param $secret
     * @return string
     */
    public function setXSignature($method, $url, $secret): string
    {
        $str = implode("\n", [
            strtoupper($method),
            $this->checksum,
            'application/json',
            $this->date,
            $url,
        ]);

        return strtolower(hash_hmac('sha1', $str, $secret));
    }

}