<?php


namespace app\src\pbx\amo\handler;


use app\src\pbx\client\Instance;
use app\src\pbx\client\models\Config;


/**
 * Class Handler
 * @package app\src\pbx\amo\handler
 */
abstract class Handler
{

    public Instance $instance;
    /**
     * @var
     */
    public $mask;
    
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
     * @param $href
     * @return |null
     */
    public function getDealByHref($href)
    {
        $href = str_replace($this->instance->config->url, '', $href);
        return $this->request->getHrefRequest($href);
    }

    /**
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