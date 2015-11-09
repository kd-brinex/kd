<?php
/**
 * Created by PhpStorm.
 * User: javierperezu
 */

namespace app\modules\seotools;

use app\modules\seotools\models\base\MetaBase;
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
    public $cacheDuration = 10;

    /**
     * chache tag dependency
     *
     * @var string
     */
    const CACHE_TAG = 'seotools';

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
            'name' => 'name',
            'request' => 'model',
            'modelParams' => [
                'model' => '\app\modules\city\models\City',
                'where'=>[
                    'id' => '{{cityid}}',
                ],
            ],
            'default' => '',
        ],
        '{{cityid}}' => [
            'name' => 'city',
            'request' => 'cookies',
            'default' => '{{cityidTstore}}',
        ],
        '{{cityidTstore}}' => [
            'name' => 'city_id',
            'request' => 'model',
            'modelParams' => [
                'model' => '\app\modules\autoparts\models\TStore',
                'where'=>[
                    'id' => '{{StoreID}}',
                ],
            ],
            'default' => '',
        ],
        '{{StoreID}}' =>[
            'name' => 'StoreID',
            'request' => 'post',
            'default' => '',
        ],
        '{{marka}}' => [
            'name' => 'marka',
            'request' => 'get',
            'default' => ''
        ],
        '{{family}}' => [
            'name' => 'family',
            'request' => 'get',
            'default' => ''
        ],
    ];


    /*
     * Шаблон поиска фраз для замены в тексте
     */
    public $regReplace = "/\{\{[a-zA-Z]*\}\}/";

    public $default_cityid = 2097;


    //private $_info = '';
    private $_infotext_before = null;
    private $_infotext_after = null;

    private $_h1_title = '';

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

//        $oTagDependency = new \yii\caching\TagDependency(['tags' => self::CACHE_TAG.Yii::$app->request->cookies->getValue('city', 2097) ]);

//        $cache->set($cacheId, $aMeta, $this->cacheDuration, $oTagDependency);

        if(isset($aMeta['infotext_before']) || isset($aMeta['infotext_after']))
        {
            //находим альтернативный текст для города
            $aMeta = $this->replaceInfotext($aMeta);
            $aMeta = $this->setLinks($aMeta,['infotext_before', 'infotext_after']);
        }

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
    /*public function setInfotext($info)
    {
        //заменяем фразы в тексте
        $this->_info = $this->replaceText($info);
        return $this;
    }*/


    /*public function getInfotext()
    {
        return $this->_info;
    }*/

    public function setInfotextafter($infotext_after)
    {
        //заменяем фразы в тексте
        $this->_infotext_after = $this->replaceText($infotext_after);
        return $this;
    }


    public function getInfotextafter()
    {
        return $this->_infotext_after;
    }

    public function setInfotextbefore($infotext_before)
    {
        //заменяем фразы в тексте
        $this->_infotext_before = $this->replaceText($infotext_before);
        return $this;
    }


    public function getInfotextbefore()
    {
        return $this->_infotext_before;
    }

    public function setH1title($h1_title)
    {
        //заменяем фразы в тексте
        $this->_h1_title = $this->replaceText($h1_title);
        return $this;
    }

    public function getH1title()
    {
        return !empty($this->_h1_title)? "<h1>".$this->_h1_title."</h1>" : '';
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
            'title' => '', 'description' => '', /*'info' =>'',*/'keywords' => '', 'keywords' => '', 'params_url' => '', 'h1_title' => '', 'infotext_before' => '', 'infotext_after' => ''];

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
            ->setH1title($metadata['h1_title'])
            ->setInfotextbefore($metadata['infotext_before'])
            ->setInfotextafter($metadata['infotext_after']);


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

                    $data = ucfirst($this->getData($this->replaceParams[$value]));

                    //заменяем строку $value в строке $text
                    $text = str_replace($value, $data, $text);

                    //сохраняем найденное значение
                    $this->replaceParams[$value]['value'] = $data;

                }

            }

        }
        return $text;
    }

    public function getData($value)
    {
        $data = $this->$value['request']($value);

        if(preg_match($this->regReplace,$data))
        {
            $data = $this->getData($this->replaceParams[$data]);
        }

        return $data;
    }

    /**
     * @param $name - наименование get переменной
     * @param null $defaultValue - значение возвращаемое если нет get значния
     * @return array|mixed
     */
    public function get($value)
    {
        return \Yii::$app->request->get($value['name'], $value['default']);
    }

    /**
     * @param $name - наименование post переменной
     * @param null $defaultValue - значение возвращаемое если нет post значния
     * @return array|mixed
     */
    public function post($value)
    {
        return \Yii::$app->request->post($value['name'], $value['default']);
    }

    /**
     * @param $name - Cookies name
     * @param null $defaultValue - значение присваиваемое если cookie не объявлена
     * @return mixed
     */
    public function cookies($value)
    {
        return Yii::$app->request->cookies->getValue($value['name'], $value['default']);
    }

    public function model($value)
    {
        if(is_array($value['modelParams']['where']))
        {
            $where = array();
            foreach($value['modelParams']['where'] as $k => $v)
            {

                if(preg_match($this->regReplace,$v))
                {
                    $where[$k] = $this->getData($this->replaceParams[$v]);
                }
                else {
                    $where[$k] = $v;
                }
            }

            $model = $value['modelParams']['model'];
            $s = $model::find()
                -> where($where)
                ->one();

            //если найдена строка в базе
            if($s)
            {
                return $s->$value['name'];
            }
            else {
                return $value['default'];
            }
        }
        else {
            return $value['default'];
        }

    }


    /**
     *  Находит и заменяет текст before и after для города, если есть
     * @param $aMeta
     * @return array
     */
    public function replaceInfotext($aMeta)
    {
        $city_id = Yii::$app->request->cookies->getValue('city', $this->default_cityid);

        $infotext= \app\modules\seotools\models\base\Infotext::find()
            ->select('infotext_before, infotext_after')
            ->where(['meta_id' => $aMeta['id_meta'], 'city_id' => $city_id])
            ->asArray()
            ->one();

        if(!empty($infotext))
        {
            $aMeta = array_merge($aMeta,$infotext);
        }

        return $aMeta;

    }

    public function setLinks($aMeta, $replaceKey)
    {
        //получаем список ключей и ссылок
        $meta_links = \app\modules\seotools\models\base\MetaLinks::find()
                        ->where("link <> '".$this->route."'")
                        ->all();

        //заменяем
        foreach($replaceKey as $key)
        {
            if(!empty($aMeta[$key]))
            {
                foreach($meta_links as $m_link)
                {

                    $aMeta[$key] = $this->replaceToLink($m_link->keyword, $m_link->link, $aMeta[$key]);

                    //TODO костыль надо будет заменит
//                    if(preg_match("/[А-Я]/",$m_link->keyword))
//                        $aMeta[$key] = $this->replaceToLink(mb_substr(mb_strtoupper($m_link->keyword, 'utf-8'), 0, 1, 'utf-8') . mb_substr($m_link->keyword, 1, mb_strlen($m_link->keyword)-1, 'utf-8'), $m_link->link, $aMeta[$key]);

                }

            }
        }

        return $aMeta;

    }

    private function replaceToLink($word, $link, $text)
    {
        return preg_replace('/(\b'.$word.'\b)/siu', '<a href='.$link.' title="${1}">${1}</a>', $text);
    }



}