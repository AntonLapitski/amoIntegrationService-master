<?php


namespace app\controllers;


use Yii;
use yii\web\Response;

/**
 * Class AppController
 * Класс является базовым рест апи контроллером который инстанцирует приложение, инициализируется yii сущностями
 *
 * @property object $target
 * @property \crmpbx\httpClient\HttpClient $httpClient
 * @property \crmpbx\logger\Logger $logger
 * @package app\controllers
 */
abstract class AppController extends \yii\rest\Controller
{
    /**
     * Объект переменная, отвечает за то какой котроллер и метод будет использован
     * @var object
     */
    protected object $target;

    /**
     * Переменная класс, которая которая представляет собой класс который помогает отправлять данные на сервер
     * @var \crmpbx\httpClient\HttpClient
     */
    public \crmpbx\httpClient\HttpClient $httpClient;

    /**
     * Переменная класс, которая которая отвечает за выведение логов в файл
     * @var \crmpbx\logger\Logger
     */
    protected \crmpbx\logger\Logger $logger;

    /**
     * AppController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @return void
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->target = $this->setTarget();
        $this->httpClient = Yii::$app->httpClient;
        $this->logger = Yii::$app->logger;
    }

    /**
     * Функция врзвращает некоторые моды или поведения управления контента
     *
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['*/*'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Функция ставит в соответствие урлу, неоьходимый обрабочтик, то есть контроллер, который будет вызван
     *
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
     * Возвращает ответ с двумя надстройками мод и статус завпроса
     *
     * @param $response
     * @return array
     */
    protected static function response($response)
    {
        $response['integration'] = 'amo';
        $response['success'] = true;

        return $response;
    }
}