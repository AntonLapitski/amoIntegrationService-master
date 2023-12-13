<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\client\Instance;
use app\src\pbx\client\models\Config;


/**
 * Class Handler
 * @property Instance $instance
 * @package app\src\pbx\amo\handler
 */
abstract class Handler
{

    /**
     *  ключевой объект
     *
     * @var Instance
     */
    public Instance $instance;

    /**
     * маска
     *
     * @var mixed
     */
    public $mask;

    /**
     * объект запроса
     *
     * @var Request
     */
    public Request $request;

    /**
     * Handler constructor.
     * @param Instance $instance
     * @param Request $request
     */
    public function __construct(Instance $instance, Request $request)
    {
        $this->instance = $instance;
        $this->request = $request;
    }

    /**
     * получить сделку по ссылке
     *
     * @param $href
     * @return |null
     */
    public function getDealByHref($href)
    {
        $href = str_replace($this->instance->config->url, '', $href);
        return $this->request->getHrefRequest($href);
    }

    /**
     * сетинг полей запроса
     *
     * @param $fields
     * @return array
     */
    protected static function setRequestCustomFields($fields): array
    {
        if (false === is_array($fields)) return [];

        $res = [];
        foreach ($fields as $code => $value)
            $res[] = [
                'field_code'=> $code,
                'values' => [
                    [
                        'value' => $value
                    ]
                ]
            ];

        return $res;
    }

    /**
     * сетинг тэгов запроса
     *
     * @param $tags
     * @return array
     */
    protected static function setRequestDataTags($tags): array
    {
        if(false === is_array($tags)) return [];

        foreach ($tags as $key => $tag)
            $tags[$key] = ['name' =>$tag];

        return $tags;
    }

}