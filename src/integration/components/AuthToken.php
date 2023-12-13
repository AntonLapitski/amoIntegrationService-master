<?php


namespace app\src\integration\components;

use app\modules\api\modules\integration\models\InstallRequest;

use app\src\integration\Integration;
use app\src\integration\models\AuthTokenModel;
use app\src\integration\request\AuthServiceRequest;
use crmpbx\httpClient\HttpClient;
use crmpbx\httpClient\Response;

/**
 * Class AuthToken
 * @property AuthTokenModel $model
 * @property string $integrationSid
 * @property string $companySid
 * @property string $domain
 * @property HttpClient $httpClient
 * @package app\src\integration\components
 */
class AuthToken
{
    /**
     * модель с токеном для авторизации
     *
     * @var AuthTokenModel
     */
    private AuthTokenModel $model;

    /**
     * идентификатор интеграции
     *
     * @var string
     */
    private string $integrationSid;

    /**
     * идентификатор компании
     *
     * @var string
     */
    private string $companySid;

    /**
     * домен
     *
     * @var string
     */
    private string $domain;

    /**
     * клиент объект
     *
     * @var HttpClient
     */
    private HttpClient $httpClient;

    /**
     * AuthToken constructor.
     * @param string $integrationSid
     * @param string $companySid
     * @param string $domain
     */
    public function __construct(string $integrationSid, string $companySid, string $domain)
    {
        $this->httpClient = \Yii::$app->httpClient;
        $this->integrationSid = $integrationSid;
        $this->companySid = $companySid;
        $this->domain = $domain;
    }

    /**
     * сетинг модели
     *
     * @param Response|null $response
     * @return AuthTokenModel
     */
    private function setModel(Response $response = null): AuthTokenModel
    {
        $this->model = new AuthTokenModel($response->body['token'] ?? []);
        return $this->model;
    }

    /**
     * установка или апдейт параметров
     *
     * @param InstallRequest $request
     * @return AuthTokenModel
     */
    public function install(InstallRequest $request): AuthTokenModel
    {
        if(!isset($this->get()->access_token))
            return $this->set($request);
        else
            return $this->update($request);

    }

    /**
     * отправление запроса и получение ответа
     *
     * @param $method
     * @param $body
     * @return mixed
     */
    private function request($method, $body)
    {
        $response = $this->httpClient->getResponse(new AuthServiceRequest($method, $body));
        if($response && in_array($response->status, [200, 201]))
            return $response;
    }

    /**
     * возвращение авторизационной модели
     *
     * @return AuthTokenModel
     */
    public function get(): AuthTokenModel
    {
        if(isset($this->model) && isset($this->model->access_token))
            return $this->model;

        $body =  $this->searchTokenRequestBody();
        $response = $this->request('GET', $body);
        return $this->setModel($response);
    }

    /**
     * сетинг авторизационной модели
     *
     * @param InstallRequest $request
     * @return AuthTokenModel
     */
    public function set(InstallRequest $request): AuthTokenModel
    {
        $body = $this->setTokenRequestBody($request);
        $response = $this->request('POST', $body);
        return $this->setModel($response);
    }

    /**
     * обновление авторизационной модели
     *
     * @param InstallRequest $request
     * @return AuthTokenModel
     */
    public function update(InstallRequest $request): AuthTokenModel
    {
        $body = $this->setTokenRequestBody($request);
        $response = $this->request('PATCH', $body);
        return $this->setModel($response);
    }

    /**
     * удаление
     *
     *
     * @return bool
     */
    public function delete(): bool
    {
        $body = $this->searchTokenRequestBody();
        $body['delete'] = true;

        $response = $this->request('DELETE', $body);

        if($response && 200 === $response->status)
            return true;

        return false;
    }

    /**
     * получение идетификаторов
     *
     * @return array
     */
    private function searchTokenRequestBody(): array
    {
        return [
            'integration_sid' => $this->integrationSid,
            'company_sid' => $this->companySid,
        ];
    }

    /**
     * сетинг массива значений ответного тела
     *
     * @param InstallRequest $request
     * @return array
     */
    private function setTokenRequestBody(InstallRequest $request): array
    {
        return [
            'integration_sid' =>  $this->integrationSid,
            'company_sid' => $this->companySid,
            'integration_id' => AMO_INTEGRATION_ID,
            'secret_key' => AMO_INTEGRATION_SECRET_KEY,
            'service' => 'amocrm',
            'authorization_code' => $request->code,
            'url' => $this->domain,
            'redirect_url' => AMO_INTEGRATION_REDIRECT_URL
        ];
    }
}