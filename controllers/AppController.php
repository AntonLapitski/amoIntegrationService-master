<?php


namespace app\controllers;


use Yii;
use yii\web\Response;

/**
 * Class AppController
 * @package app\controllers
 */
abstract class AppController extends \yii\rest\Controller
{
    protected object $target;
    public \crmpbx\httpClient\HttpClient $httpClient;
    protected \crmpbx\logger\Logger $logger;

    /**
     * AppController constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->target = $this->setTarget();
        $this->httpClient = Yii::$app->httpClient;
        $this->logger = Yii::$app->logger;
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['*/*'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * @return object
     */
    private function setTarget(): object
    {
        $targetArray = explode('/', \Yii::$app->request->url);
        return (object)[
            'controller' => $targetArray[1] ?? null,
            'action' => $targetArray[2] ?? null,
            'id' => $targetArray[3] ?? null,
        ];
    }

    /**
     * @param $response
     * @return mixed
     */
    protected static function response($response)
    {
        $response['integration'] = 'amo';
        $response['success'] = true;

        return $response;
    }
}