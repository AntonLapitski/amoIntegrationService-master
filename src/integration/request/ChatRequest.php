<?php


namespace app\src\integration\request;


use yii\helpers\Json;

/**
 * Class ChatRequest
 * @property string $date
 * @property string $checksum
 * @property string $xSignature
 * @package app\src\integration\request
 */
class ChatRequest extends BaseRequest
{

    /**
     * дата
     *
     * @var string
     */
    private string $date;

    /**
     * проверка суммы
     *
     * @var string
     */
    private string $checksum;

    /**
     * подпись
     *
     * @var string
     */
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
     * установить хедеры
     *
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
     * установить подпись
     *
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