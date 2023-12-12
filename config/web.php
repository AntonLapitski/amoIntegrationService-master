<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Api',
            'defaultRoute' => 'api',
        ],
    ],
    'components' => [
        'httpClient' => [
            'class' => 'crmpbx\httpClient\HttpClient'
        ],
        'logger' => [
            'class' => 'crmpbx\logger\Logger',
            'service' => $params['serviceName'],
            'callback' => function(){
                return new \crmpbx\commutator\Commutator([
                    'logServiceAddress' => LOG_SERVICE_ADDRESS,
                    'logServiceTimeout' => LOG_SERVICE_TIMEOUT,
                    'logServiceAccessToken' => LOG_SERVICE_ACCESS_TOKEN,
                ]);
            }
        ],
        'errorHandler' => [
            'maxSourceLines' => 20,
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                '*/xml' => 'yii\web\XmlParser',
            ],
            'baseUrl' => '',
            'enableCsrfValidation' => false,
        ],
        'response' => [
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    //'encodePrint' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if (404 !== $response->statusCode) {
                    if (!$response->isSuccessful){
                        Yii::$app->logger->addInFile('exception', Yii::$app->errorHandler->exception);
                        if (YII_DEBUG)
                            var_dump(Yii::$app->errorHandler->exception);
                    }
                    Yii::$app->logger->addInFile('incoming_request', Yii::$app->request->bodyParams);
                    Yii::$app->logger->send();
                    Yii::$app->logger->writeInFileSystem();
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/serv/<action:(add-entity)>/<entity:\w+>' => '/serv/<action>',
                '/serv/<action:(get-log)>/<sid:\w+>' => '/serv/<action>',
                '/api/amo' => '/api/amo/main/exec',
                '/api/integration/<action:(install|uninstall)>' => '/api/integration/service/<action>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
