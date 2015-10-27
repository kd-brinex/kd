<?php
/**
 * Created by PhpStorm.
 * User: javierperezu
 */

namespace app\modules\seotools;

use yii;
use yii\helpers\Json;
use app\modules\seotools\models\Meta;


class Component extends \yii\base\Component
{
    /**
     * Default meta data values. These override any other metadata set
     * @var array
     */
    public $defaults = [
        'og:type' => 'website',
    ];

    /**
     * Component ID representing the database
     * @var string
     */
    public $db = 'db';

    /**
     * Component ID representing the cache
     * @var string
     */
    public $cache = 'cache';

    /**
     * The Component ID for this module (used to mark cache segments)
     * @var string
     */
    public $componentId = 'seotools';

    /**
     * After how long (seconds) will the routes caching expire.
     * @var int
     */
//    public $cacheDuration = 3600;
    public $cacheDuration = 30;

    /**
     * host + path: used to identify pages
     * @var null
     */
    public $route = null;

    /*
     * Если в ссылке встречаются слова из данного параметра+/ то после идет игнорирование(не добавляются в базу)
     * @var array
     */
    public $afterIgnore = [
        'autocatalogs',
    ];

    /*
     * Массив определяющий что менять и откуда брать данные для замены
     * @var array
     */
    public $replaceParams = [
        '{{city}}' => [
            'names' => [
                'city' => [
                    'request' => 'cookies',
                    'default' => 2097,
                    'whereName' => 'id'
                ]
            ],
            'modelParams' => [
                'model' => '\app\modules\city\models\City',
                'colName'=> 'name'
            ]
        ],
        '{{marka}}' => [
            'names' => [
                'marka' => [
                    'request' => 'get',
                    'default' => ''
                ]
            ]
        ],
        '{{family}}' => [
            'names' => [
                'family' => [
                    'request' => 'get',
                    'default' => ''
                ]
            ]
        ],
    ];

    /*
     * Шаблон поиска фраз для замены в тексте
     */
    public $regReplace = "/\{\{[a-z]*\}\}/";

    /**
     * chache tag dependency
     *
     * @var string
     */
    const CACHE_TAG = 'seotools';


    private $_info = '';

    /**
     * Devuelve la url absoluta con el path
     * @return string
     */
    public function getRoute() {
        if (is_null($this->route)) {

            $path = Yii::$app->request->getPathInfo();
            //проверяем адрес если в адресе игнорируемое слово то устанавливаем адрес до игнорируемого слова
            $path_array = explode("/",$path);
            foreach($path_array as $vPath)
            {
                $newPath[] = $vPath;
                if(in_array($vPath,$this->afterIgnore))
                {
                    break;
                }
            }

            $this->route = Yii::$app->request->getHostInfo() . '/' . implode("/",$newPath);

        }

        return $this->route;
    }


    /**
     * @param string $route
     * @return array
     */
    public function getMeta($route)
    {
        $cache = Yii::$app->{$this->cache};
        $cacheId = $this->componentId . '|routes|' . $route;
        $aMeta = $cache->get($cacheId);

        if ($aMeta) {
            return $aMeta;
        }

        $oMeta = new Meta();
        $oMeta->setRoute($route);

        $aMeta = [];
        $model = Meta::findOne([
            'hash' => $oMeta->hash
        ]);

        if (!empty($model)) {
            $info = $model->toArray();

            foreach ($info as $idData => $data) {
                if (!empty($data)) {
                    $aMeta[$idData] = $data;
                }
            }
        } else {
            // Si no existe la entrada con esa ruta la creamos
            $oMeta->save();
        }

        $oTagDependency = new \yii\caching\TagDependency(['tags' => self::CACHE_TAG ]);

        $cache->set($cacheId, $aMeta, $this->cacheDuration, $oTagDependency);

        return $aMeta;
    }

    /**
     * Register the robots meta
     * $index must be index or noindex or empty/null
     * $follow must be follow or nofollow or empty/null
     * @param string $index
     * @param string $follow
     */
    public function setRobots($index = '', $follow = '')
    {
        $v = [];

        if (!empty($index)) {
            $v[] = $index;
        }

        if (!empty($follow)) {
            $v[] = $follow;
        }

        if (!empty($v)) {
            Yii::$app->view->registerMetaTag(['name' => 'robots', 'content' => strtolower(implode(',', $v))], 'robots');
        }
        return $this;
    }

    /**
     * Register the author meta
     * @param string $author
     */
    public function setAuthor($author)
    {
        if (!empty($author)) {
            Yii::$app->view->registerMetaTag(['name' => 'author', 'content' => $author], 'author');
        }
        return $this;
    }

    /**
     * Register Open Graph Type meta
     * @param string $type
     */
    public function setOpenGraphType($type)
    {
        if (!empty($type)) {
            Yii::$app->view->registerMetaTag(['name' => 'og:type', 'content' => $type], 'og:type');
        }
        return $this;
    }

    /**
     * Register title meta and open graph title meta
     * @param string $title
     */
    public function setTitle($title)
    {
        if (!empty($title)) {
            $title = $this->replaceText($title);
            Yii::$app->view->registerMetaTag(['name' => 'title', 'content' => $title], 'title');
            Yii::$app->view->registerMetaTag(['name' => 'og:title', 'content' => $title], 'og:title');
            Yii::$app->view->title = $title;
        }
        return $this;
    }

    /**
     * Register description meta and open graph description meta
     * @param string $description
     */
    public function setDescription($description)
    {
        if (!empty($description)) {
            $description = $this->replaceText($description);
            Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $description], 'description');
            Yii::$app->view->registerMetaTag(['name' => 'og:description', 'content' => $description], 'og:description');
        }
        return $this;
    }

    /**
     * Register keywords meta
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        if (!empty($keywords)) {
            Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $this->replaceText($keywords)], 'keywords');
        }
        return $this;
    }

    /**
     * Register Canonical url
     * @param string $url
     */
    public function setCanonical($url)
    {
        Yii::$app->view->registerLinkTag(['href' => $url, 'rel' => 'canonical'], 'canonical');
        return $this;
    }

    /**
     * Register Open Graph Page Url
     * @param string $url
     */
    public function setOpenGraphUrl($url)
    {
        Yii::$app->view->registerMetaTag(['name' => 'og:url', 'content' => $url], 'og:url');
        return $this;
    }

    /**
     * Register text associated to a Url
     * @param string $info
     */
    public function setInfotext($info)
    {
        //заменяем фразы в тексте
        $this->_info = $this->replaceText($info);
        return $this;
    }


    public function getInfotext()
    {
        return $this->_info;
    }

    /**
     * @param array $metadata
     * @param bool $setCanonical true, try to create a canonical url and og url, action needs to have params
     * @param bool $checkDb try to get from DB params
     */
    public function setMeta($metadata = [], $setCanonical = false, $checkDb = false)
    {
        // Set to empty not given values
        $metadataReset = ['robots_index' => '', 'robots_follow' => '', 'author' => '',
            'title' => '', 'description' => '', 'info' =>'','keywords' => '', 'keywords' => '', 'params_url' => ''];

        $metadata = array_merge($metadataReset, $metadata);

        if ($checkDb) {
            // Merge passed parameter meta with route meta
            $metadata = array_merge($metadata, $this->getMeta($this->getRoute()));

        }

        // Override meta with the defaults via merge
        $metadata = array_merge($metadata, $this->defaults);

        $this->setRobots($metadata['robots_index'], $metadata['robots_follow'])
            ->setAuthor($metadata['author'])
            ->setTitle($metadata['title'])
            ->setDescription($metadata['description'])
            ->setKeywords($metadata['keywords'])
            ->setOpenGraphType($metadata['og:type'])
            ->setInfotext($metadata['info']);

        if ($setCanonical == true) {

            if (!isset($metadata['params'])) {
                $params = Yii::$app->controller->actionParams;
            } else {
                $params = $metadata['params'];
            }

            if (!isset($metadata['route'])) {
                $params[0] = Yii::$app->controller->getRoute();
            } else {
                $params[0] = $metadata['route'];
            }

            $url = Yii::$app->getUrlManager()->createAbsoluteUrl($params);

            if ($url !== Yii::$app->request->absoluteUrl) {
                $this->setCanonical($url);
            }

            $this->setOpenGraphUrl($url);
        }

    }


    /**
     * @param $text - string
     * @return mixed -string
     */
    public function replaceText($text)
    {
        if(!empty($text))
        {
            //Находим значения по шаблону
            preg_match_all($this->regReplace,$text,$findSubstr);

            //перебираем найденные значения и зменяем
            foreach($findSubstr[0] as $value)
            {
                //проверяем есть ли найденное значение в массиве для замены
                if(array_key_exists($value,$this->replaceParams))
                {
                    $data = '';
                    $where = null;

                    //проверяем было ли ранее найдено данное значение для замены
                    if(isset($this->replaceParams[$value]['value']))
                    {
                        //заменяем строку $value в строке $text
                        $text = str_replace($value, $this->replaceParams[$value]['value'], $text);
                        continue;
                    }

                    //перебираем значения из get и cookies
                    foreach($this->replaceParams[$value]['names'] as $k => $v)
                    {
                        //получаем значение
                        $d = $this->$v['request']($k,$v['default']);

                        //есди не заданы параметры модели то завершаем цикл
                        if(!isset($this->replaceParams[$value]['modelParams']))
                        {
                            $data = ucfirst($d);
                            break;
                        }
                        //если заданы параметры модели то формируем масив для условия отобора из базы
                        $where[$v['whereName']] = $d;
                    }

                    if(isset($this->replaceParams[$value]['modelParams']) && is_array($where))
                    {
                        $model = $this->replaceParams[$value]['modelParams']['model'];
                        $s = $model::find()
                            -> where($where)
                            ->one();

                        //если найдена строка в базе
                        if($s)
                        {
                            $colName = $this->replaceParams[$value]['modelParams']['colName'];
                            $data = ucfirst($s->$colName);
                        }

                    }

                    //заменяем строку $value в строке $text
                    $text = str_replace($value, $data, $text);

                    //сохраняем найденное значение
                    $this->replaceParams[$value]['value'] = $data;

                }

            }

        }
        return $text;
    }

    /**
     * @param $name - наименование get переменной
     * @param null $defaultValue - значение возвращаемое если нет get значния
     * @return array|mixed
     */
    public function get($name, $defaultValue = null)
    {
        return \Yii::$app->request->get($name, $defaultValue);
    }

    /**
     * @param $name - наименование post переменной
     * @param null $defaultValue - значение возвращаемое если нет post значния
     * @return array|mixed
     */
    public function post($name, $defaultValue = null)
    {
        return \Yii::$app->request->post($name, $defaultValue);
    }

    /**
     * @param $name - Cookies name
     * @param null $defaultValue - значение присваиваемое если cookie не объявлена
     * @return mixed
     */
    public function cookies($name, $defaultValue = null)
    {
        return Yii::$app->request->cookies->getValue($name,$defaultValue);
    }
}