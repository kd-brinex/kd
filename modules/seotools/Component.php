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
use yii\base\ErrorException;


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
     * Если в ссылке встречается начало из данного параметра+/ то после идет игнорирование(не добавляются в базу)
     * @var array
     */
    public $after_ignore = [
        'autocatalogs',
        'admin',
        'seotools',
        'user',
        'basket',
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
            foreach($this->after_ignore as $value)
            {
                if(preg_match("/^".$value."(\b|\/)/",$path))
                {
                    $path = $value;
                    break;
                }
            }
            $this->route = Yii::$app->request->getHostInfo() .'/'. $path;

        }

        return $this->route;
    }


    /**
     * @param string $route
     * @return array
     */
    public function getMeta($route)
    {
//        $cache = Yii::$app->{$this->cache};
//        $cacheId = $this->componentId . '|routes|' . $route;
//        $aMeta = $cache->get($cacheId);

//        if ($aMeta) {
//            return $aMeta;
//        }

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

        //находим альтернативный текст для города
        if(!empty($aMeta)) {
            $aMeta = $this->replaceInfotext($aMeta);
            $aMeta = $this->setLinks($aMeta, ['infotext_before', 'infotext_after']);
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
            $title = !empty($title)?$this->replaceText($title):'';
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

    public function setInfotextafter($infotext_after)
    {
        //заменяем фразы в тексте
        $this->_infotext_after = !empty($infotext_after)?
                '<div class="infotext">'.$this->replaceText($infotext_after).'</div>': '';
        return $this;
    }


    public function getInfotextafter()
    {
        return $this->_infotext_after;
    }

    public function setInfotextbefore($infotext_before)
    {
        //заменяем фразы в тексте
        $this->_infotext_before = !empty($infotext_before)?
            '<div class="infotext">'.$this->replaceText($infotext_before).'</div>': '';
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
            'title' => '', 'description' => '', 'keywords' => '', 'keywords' => '', 'params_url' => '', 'h1_title' => '', 'infotext_before' => '', 'infotext_after' => ''];


        //если передан ошибка 404 то обнуляем значения
        if(\Yii::$app->errorHandler->exception==null || \Yii::$app->errorHandler->exception!=null && \Yii::$app->errorHandler->exception->statusCode != 404) {
            $metadata = array_merge($metadataReset, $metadata);
            if ($checkDb) {
                // Merge passed parameter meta with route meta
                $metadata = array_merge($metadata, $this->getMeta($this->getRoute()));
            }
            // Override meta with the defaults via merge
            $metadata = array_merge($metadata, $this->defaults);
        }
        else {
            $metadata = $metadataReset;
        }

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

            //Находим значения по шаблону
            preg_match_all($this->regReplace,$text,$findSubstr);

            //перебираем найденные значения и зменяем
            foreach($findSubstr[0] as $value)
            {
                //проверяем есть ли найденное значение в массиве для замены
                if(array_key_exists($value,$this->replaceParams))
                {
                    $where = null;

                    //проверяем было ли ранее найдено данное значение для замены
                    if(isset($this->replaceParams[$value]['value']))
                    {
                        //заменяем строку $value в строке $text
                        $text = str_replace($value, $this->replaceParams[$value]['value'], $text);
                        continue;
                    }

                    //переводим первую букву в верхний регистр
                    $data = ucfirst($this->getData($this->replaceParams[$value]));

                    //заменяем строку $value в строке $text
                    $text = str_replace($value, $data, $text);

                    //сохраняем найденное значение
                    $this->replaceParams[$value]['value'] = $data;

                }

            }
        return $text;
    }

    /**
     * Функция вызова метода получения данных get/post/cookies/model
     * @param $value array массив содержащий информацию о переменной для замены
     * @return mixed
     */
    public function getData($value)
    {
        //вызываем функцию получения данных post/get/model/cookies
        $data = $this->$value['request']($value);

        //проверяем является ли полученное значение шаблоном для замены
        if(preg_match($this->regReplace,$data))
        {
            //
            $data = $this->getData($this->replaceParams[$data]);
        }

        return $data;
    }

    /**
     * Получение пуе данных
     * @param $name - наименование get переменной
     * @param null $defaultValue - значение возвращаемое если нет get значния
     * @return array|mixed
     */
    public function get($value)
    {
        return \Yii::$app->request->get($value['name'], $value['default']);
    }

    /**
     * получние post данных
     * @param $name - наименование post переменной
     * @param null $defaultValue - значение возвращаемое если нет post значния
     * @return array|mixed
     */
    public function post($value)
    {
        return \Yii::$app->request->post($value['name'], $value['default']);
    }

    /**
     * получение данных из cookies
     * @param $name - Cookies name
     * @param null $defaultValue - значение присваиваемое если cookie не объявлена
     * @return mixed
     */
    public function cookies($value)
    {
        return Yii::$app->request->cookies->getValue($value['name'], $value['default']);
    }

    /**
     * получение даннных из базы (модель класс Active Record)
     * @param $value array массив содержащий информацию о переменной для замены
     * @return mixed
     */
    public function model($value)
    {
        //проверка наличия условия отбора
        if(is_array($value['modelParams']['where']))
        {
            $where = array();
            //перебираем условия отбора
            foreach($value['modelParams']['where'] as $k => $v)
            {

                //если в значение параметра отбора стоит шаблон то вызываем функцию поиска значения для шаблона
                if(preg_match($this->regReplace,$v))
                {
                    $where[$k] = $this->getData($this->replaceParams[$v]);
                }
                else {
                    $where[$k] = $v;
                }
            }

            //получаем данные из базы
            $model = $value['modelParams']['model'];
            $s = $model::find()
                -> where($where)
                ->one();

            if($s)
            {
                //если найдено значение в базе то возвращаем его
                return $s->$value['name'];
            }
            else {
                //если не найдено то возврат значения по умолчанию
                return $value['default'];
            }
        }
        else {
            //возврат значения по умолчанию
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
        //TODO в значение по умолчанию добавлено определение city_id по StoreID
        $city_id = Yii::$app->request->cookies->getValue('city', $this->getCityid(Yii::$app->request->post('StoreID')));

        if(!empty($city_id))
        {
            $infotext= \app\modules\seotools\models\base\Infotext::find()
                ->select('infotext_before, infotext_after')
                ->where(['meta_id' => $aMeta['id_meta'], 'city_id' => $city_id])
                ->asArray()
                ->one();

            if(!empty($infotext))
            {
                $aMeta = array_merge($aMeta,$infotext);
            }
        }

        return $aMeta;

    }

    /**
     * Функция берет из базы список ссылок с ключевыми словами и перебирает значения в переданном массиве aMeta
     * по ключам из массива $replaceKey и вызывает функцию замены слов в тексте на ссылки
     * @param $aMeta array - массив значений мета тэгов и текстов
     * @param $replaceKey array - массив ключей aMeta значения которых будут изменены
     * @return mixed array aMeta
     */
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
                }
            }
        }

        return $aMeta;

    }


    /**
     * Функция поиска и замены слова в тексте на ссылку
     * @param $word  - слово для поиска
     * @param $link  - ссылка
     * @param $text - текст в котором заменяем слова
     * @return mixed  - возврат текста с заменными слови на ссылки
     */
    private function replaceToLink($word, $link, $text)
    {
        return preg_replace('/(\b'.$word.'\b)/siu', '<a href='.$link.' title="${1}">${1}</a>', $text);
    }


    /**
     * Функция определения city_id по StoreID - id магазина
     * @param $store_id
     * @return int|mixed
     */
    private function getCityid($store_id)
    {
        $store = \app\modules\autoparts\models\TStore::find()
                    ->where(['id' => $store_id])
                    ->one();
        return !empty($store)?$store->city_id:'';
    }

}