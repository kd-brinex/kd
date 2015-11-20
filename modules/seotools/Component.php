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
    public $cacheDuration = 3600;

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
            'messages' => 'city',
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
    const REGREPLACE ="/\{\{[a-zA-Zа-яА-Я_\-]{2,15}\}\}/";

    /*
     * @var string Текст перед контентом
     */
    public $infotext_before = null;

    /*
    * @var string Текст посел контента
    */
    public $infotext_after = null;

    /*
     * @var string H1 тэг
     */
    public $h1_title = '';

    public $title = '';

    /*
     * @var int id города определяемый по id магазина из post переменной StoreID
     */
    public $city = null;

    public function init()
    {
        if(!empty($store_id=\Yii::$app->request->post('StoreID')))
        {
            $this->city = $this->getCityid($store_id);
        }
        else {
            $this->city = Yii::$app->request->cookies->getValue('city');
        }
        parent::init();
    }

    /**
     * Devuelve la url absoluta con el path
     * @return string
     */
    public function getRoute() {
        if (is_null($this->route)) {
            $this->route = Yii::$app->request->getHostInfo() .'/'. Yii::$app->request->getPathInfo();
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
//        $cacheId = $this->componentId . '|routes|' . $route . '|city|' . $this->city;
//        $aMeta = $cache->get($cacheId);

//        if ($aMeta) {
//            return $aMeta;
//        }

        $aMeta = [];
        $model = Meta::findOne([
            'hash' => md5($route)
        ]);

        if (!empty($model)) {
            $info = $model->toArray();

            foreach ($info as $idData => $data) {
                if (!empty($data)) {
                    $aMeta[$idData] = $data;
                }
            }
            //находим альтернативный текст для города
            $aMeta = $this->replaceInfotext($aMeta);
            //TODO отключаем формирование ссылок в тексте по ключам
            $aMeta = $this->setLinks($aMeta, ['infotext_before', 'infotext_after']);


        }elseif(Yii::$app->request->get('seo') === 'add') {
            //при передаче гетом переменной seo = add страница добавиться в базу, только если ее там нет
            $oMeta = new Meta();
            $oMeta->setRoute($route);
            $oMeta->save();
        }

//        $oTagDependency = new \yii\caching\TagDependency(['tags' => self::CACHE_TAG]);
//
//        $cache->set($cacheId, $aMeta, $this->cacheDuration, $oTagDependency);

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

    public function setInfotextafter($infotext_after)
    {
        //заменяем фразы в тексте
        $this->infotext_after = !empty($infotext_after)?
                '<div class="infotext">'.$this->replaceText($infotext_after).'</div>': '';
        return $this;
    }


    public function getInfotextafter()
    {
        return $this->infotext_after;
    }

    public function setInfotextbefore($infotext_before)
    {
        //заменяем фразы в тексте
        $this->infotext_before = !empty($infotext_before)?
            '<div class="infotext">'.$this->replaceText($infotext_before).'</div>': '';
        return $this;
    }


    public function getInfotextbefore()
    {
        return $this->infotext_before;
    }

    public function setH1title($h1_title)
    {
        //заменяем фразы в тексте
        $this->h1_title = $this->replaceText($h1_title);
        return $this;
    }

    public function getH1title()
    {
        return !empty($this->h1_title)? "<h1>".$this->h1_title."</h1>" : '';
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

                //определяем title и h1 родителей если свои пустые
                if(empty($metadata['title'])) $key[]='title';
                if(empty($metadata['h1_title'])) $key[]='h1_title';
                if(isset($key))
                {
                    if(!empty($searchTitle = $this->searchTitle($key))) {
                        $metadata = array_merge($metadata, $searchTitle);
                    }
                }
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
            preg_match_all(self::REGREPLACE,$text,$findSubstr);

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

                    //получаем значение и делаем первую букву заглавной
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
        if(preg_match(self::REGREPLACE,$data))
        {
            //
            $data = $this->getData($this->replaceParams[$data]);
        }

        //проверяем надо заменять из словаря или нет перед возвратом
        return isset($value['messages'])?Yii::t($value['messages'], $data):$data;
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
                if(preg_match(self::REGREPLACE,$v))
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
        $infotext= \app\modules\seotools\models\base\Infotext::find()
                ->select('infotext_before, infotext_after')
                ->where(['meta_id' => $aMeta['id_meta'], 'city_id' => $this->city])
                ->asArray()
                ->one();

        if(!empty($infotext))
        {
            $aMeta = array_merge($aMeta,$infotext);
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
        //заменяем
        foreach($replaceKey as $key)
        {
            if(!empty($aMeta[$key]))
            {
                //переводим в нижний регистр
                $infotext = mb_strtolower($aMeta[$key]);
                //получаем список первых ключей
                $first_keys = Meta::find()->where("first_keyword <> '' ")->all();
                //заменяем в тексте слова на ссылки
                foreach($first_keys as $fk)
                {
                    $aMeta[$key] = $this->replaceToLink($fk->first_keyword, $fk->route, $aMeta[$key]);
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
    public function replaceToLink($word, $link, $text)
    {
        return preg_replace('/(\b'.$word.'\b)/siu', '<a href='.$link.' title="${1}">${1}</a>', $text);
    }

    /**
     * Функция определения city_id по StoreID - id магазина
     * @param $store_id
     * @return int|mixed
     */
    public function getCityid($store_id)
    {
        $store = \app\modules\autoparts\models\TStore::find()
                    ->where(['id' => $store_id])
                    ->one();
        return !empty($store)?$store->city_id:'';
    }

    public function searchTitle($key)
    {
        $path = Yii::$app->request->getPathInfo();
        $path =  explode("/",$path);

        $routes=[];
        for($i = count($path)-1; $i > 0; $i--)
        {
            unset($path[$i]);
            $routes[] = Yii::$app->request->getHostInfo() ."/".implode("/",$path);
        }

        $key[] = "MAX(CHAR_LENGTH(route))";
        $data = Meta::find()
            ->select($key)
            ->where("route in ('".implode("','",$routes)."')")
            ->asArray()
            ->one();

        return $data;
    }

}