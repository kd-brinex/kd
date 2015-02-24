<?php
/**
 * @link https://github.com/himiklab/yii2-ipgeobase-component
 * @copyright Copyright (c) 2014 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace app\modules\city;

//use app\modules\city\models\City;
use app\modules\city\models\CitySearch;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use app\modules\city\models\CityList;
use yii\db\BaseActiveRecord;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Компонент для работы с базой IP-адресов сайта IpGeoBase.ru,
 * он Реализует поиск географического местонахождения IP-адреса,
 * выделенного RIPE локальным интернет-реестрам (LIR-ам).
 * Для Российской Федерации и Украины с точностью до города.
 *
 * @author HimikLab
 * @package himiklab\ipgeobase
 */
class IpGeoBase extends Component
{
    const XML_URL = 'http://ipgeobase.ru:7020/geo?ip=';
    const ARCHIVE_URL = 'http://ipgeobase.ru/files/db/Main/geo_files.zip';
    const ARCHIVE_IPS_FILE = 'cidr_optim.txt';
    const ARCHIVE_CITIES_FILE = 'cities.txt';

    const DB_IP_INSERTING_ROWS = 20000; // максимальный размер (строки) пакета для INSERT запроса
    const DB_IP_TABLE_NAME = '{{%geobase_ip}}';
    const DB_CITY_TABLE_NAME = '{{%geobase_city}}';
    const DB_REGION_TABLE_NAME = '{{%geobase_region}}';

    const CITY_NAME = 'city';

    /** @var bool $useLocalDB Использовать ли локальную базу данных */
    public $useLocalDB = false;

    /**
     * Определение географического положеня по IP-адресу.
     * @param string $ip
     * @param bool $asArray
     * @return array|IpData ('ip', 'country', 'city', 'region', 'lat', 'lng') или false если ничего не найдено.
     */
    public function getLocation($ip, $asArray = true)
    {
//        $ipDataArray=$this->fromCookies(self::CITY_NAME,$ipDataArray[self::CITY_NAME]);

        if ($this->useLocalDB) {
            $ipDataArray = $this->fromDB($ip) + ['ip' => $ip];
        } else {
            $ipDataArray = $this->fromSite($ip) + ['ip' => $ip];
        }
        $ipDataArray['id']=isset($ipDataArray['id'])?$ipDataArray['id']:2097;
        $ipDataArray['city']=isset($ipDataArray['city'])?$ipDataArray['city']:'Москва';
        $this->setCookies(self::CITY_NAME,$ipDataArray['id']);
//        $this->fromCookies(self::CITY_NAME,$ipDataArray[self::CITY_NAME]);

        if ($asArray) {

            return $ipDataArray;
        } else {
            return new IpData($ipDataArray);
        }
    }

    public function getCityName($ip)
    {
        if (!isset(Yii::$app->request->cookies[self::CITY_NAME])) {
            $data = $this->getLocation($ip);
            return $data[self::CITY_NAME];
        } else {
            $city=$this->fromCookies(Yii::$app->request->cookies[self::CITY_NAME]);
            return $city[self::CITY_NAME];
        }
    }

    /**
     * Тест скорости получения данных из БД.
     * @param int $iterations
     * @return float IP/second
     */
    public function speedTest($iterations)
    {
        $ips = [];
        for ($i = 0; $i < $iterations; ++$i) {
            $ips[] = mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
        }

        $begin = microtime(true);
        foreach ($ips as $ip) {
            $this->getLocation($ip);
        }
        $time = microtime(true) - $begin;

        if ($time != 0 && $iterations != 0) {
            return $iterations / $time;
        } else {
            return 0.0;
        }
    }

    /**
     * Метод создаёт или обновляет локальную базу IP-адресов.
     * @throws Exception
     */
    public function updateDB()
    {
        if (($fileName = $this->getArchive()) == false) {
            throw new Exception('Ошибка загрузки архива.');
        }
        $zip = new \ZipArchive;
        if ($zip->open($fileName) !== true) {
            @unlink($fileName);
            throw new Exception('Ошибка распаковки.');
        }

        $this->generateIpTable($zip);
        $this->generateCityTables($zip);
        $zip->close();
        @unlink($fileName);
    }

    /**
     * @param string $ip
     * @return array
     */
    protected function fromCookies($id){
        $result = Yii::$app->db->createCommand('SELECT id, name AS city FROM geobase_city WHERE id=:id')->bindValue(':id', $id)->queryOne();
        if ($result != false) {
            return $result;
        } else {
            return [];
        }
    }
    protected function fromSite($ip)
    {
        $xmlData = $this->getRemoteContent(self::XML_URL . urlencode($ip));
        $ipData = (new \SimpleXMLElement($xmlData))->ip;
        if (isset($ip->message)) {
            return [];
        }

        return [
            'country' => (string)$ipData->country,
            'city' => isset($ipData->city) ? (string)$ipData->city : null,
            'region' => isset($ipData->region) ? (string)$ipData->region : null,
            'lat' => isset($ipData->lat) ? (string)$ipData->lat : null,
            'lng' => isset($ipData->lng) ? (string)$ipData->lng : null
        ];
    }

    /**
     * @param string $ip
     * @return array
     */
    protected function fromDB($ip)
    {
        $dbIpTableName = self::DB_IP_TABLE_NAME;
        $dbCityTableName = self::DB_CITY_TABLE_NAME;
        $dbRegionTableName = self::DB_REGION_TABLE_NAME;

        $result = Yii::$app->db->createCommand(
            "SELECT tIp.country_code AS country, tCity.name AS city,
                    tRegion.name AS region, tCity.latitude AS lat,
                    tCity.longitude AS lng,
                    tCity.id as id
            FROM (SELECT * FROM {$dbIpTableName} WHERE ip_begin <= INET_ATON(:ip) ORDER BY ip_begin DESC LIMIT 1) AS tIp
            LEFT JOIN {$dbCityTableName} AS tCity ON tCity.id = tIp.city_id
            LEFT JOIN {$dbRegionTableName} AS tRegion ON tRegion.id = tCity.region_id
            WHERE INET_ATON(:ip) <= tIp.ip_end"
        )->bindValue(':ip', $ip)->queryOne();

        if ($result != false) {
            return $result;
        } else {
            return [];
        }
    }

    private function setCookies($name,$value)
    {
        if (!isset(Yii::$app->request->cookies[$name])) {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => $name,
                'value' => $value,
                'path'=>'/',
                'expire'=>time()+1814400,//21*24*60*60=21 день
                'httpOnly'=>false,

            ]));
        }
    }

    public function getListCites($params)
    {
//        var_dump($params);die;
//select c.id as id, c.name as city, r.name as region from geobase_city as c left join geobase_region as r on r.id=c.region_id')->query();
//        var_dump($params);die;
//        $query=CityList::findBySql(
//////            "set @g1=(select point from geobase_city where name='Казань');
////
//'select
//  c.id as id,
//  c.name as name,
//  -- r.name as regionName,
//  st_distance((select point from geobase_city where id=:id), c.point) dist
//from geobase_city as c
//  left join geobase_region as r on r.id=c.region_id
//order by dist asc'.((isset($params[':lcount']))?' limit :lcount':'').';',$params);
        $query=new CitySearch();
        $dataProvider=$query->search(Yii::$app->request->queryParams);
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
        return $dataProvider;

//        var_dump($dataProvider);die;
//        foreach ($query as $row) {
//            $city[$row['region']][$row['id']] = ['id'=>$row['id'],'city'=>$row['city']];
//        }
//        $html = '<div>';
//        foreach ($city as $key => $cs) {
//            $html .= '<div class="region">' . $key .'<br>';
//            foreach ($cs as $c) {
////                Html::a(['options'=>['value'=>$c]]);
//                $html .= Html::button($c['city'], ['class' => '', 'onclick' => "setCookies('city','" . $c['id'] . "')"]);
//            }
//            $html .= '</div>';
//        }
//        $html .= '</div>';
//        $html.=(isset($params['limit']))?Html::a('Показать все','/'):'';
//        return $html;
    }

    /**
     * Метод производит заполнение таблиц городов и регионов используя
     * данные из файла self::ARCHIVE_CITIES.
     * @param $zip \ZipArchive
     * @throws \yii\db\Exception
     */
    protected function generateCityTables($zip)
    {
        $citiesArray = explode("\n", $zip->getFromName(self::ARCHIVE_CITIES_FILE));
        array_pop($citiesArray); // пустая строка

        $cities = [];
        $uniqueRegions = [];
        $regionId = 1;
        foreach ($citiesArray as $city) {
            $row = explode("\t", $city);

            $regionName = iconv('WINDOWS-1251', 'UTF-8', $row[2]);
            if (!isset($uniqueRegions[$regionName])) {
                // новый регион
                $uniqueRegions[$regionName] = $regionId++;
            }

            $cities[$row[0]][0] = $row[0]; // id
            $cities[$row[0]][1] = iconv('WINDOWS-1251', 'UTF-8', $row[1]); // name
            $cities[$row[0]][2] = $uniqueRegions[$regionName]; // region_id
            $cities[$row[0]][3] = $row[4]; // latitude
            $cities[$row[0]][4] = $row[5]; // longitude
        }

        // города
        Yii::$app->db->createCommand()->truncateTable(self::DB_CITY_TABLE_NAME)->execute();
        Yii::$app->db->createCommand()->batchInsert(
            self::DB_CITY_TABLE_NAME,
            ['id', 'name', 'region_id', 'latitude', 'longitude'],
            $cities
        )->execute();

        // регионы
        $regions = [];
        foreach ($uniqueRegions as $regionUniqName => $regionUniqId) {
            $regions[] = [$regionUniqId, $regionUniqName];
        }
        Yii::$app->db->createCommand()->truncateTable(self::DB_REGION_TABLE_NAME)->execute();
        Yii::$app->db->createCommand()->batchInsert(
            self::DB_REGION_TABLE_NAME,
            ['id', 'name'],
            $regions
        )->execute();
    }

    /**
     * Метод производит заполнение таблиц IP-адресов используя
     * данные из файла self::ARCHIVE_IPS.
     * @param $zip \ZipArchive
     * @throws \yii\db\Exception
     */
    protected function generateIpTable($zip)
    {
        $ipsArray = explode("\n", $zip->getFromName(self::ARCHIVE_IPS_FILE));
        array_pop($ipsArray); // пустая строка

        $i = 0;
        $values = '';
        $dbIpTableName = self::DB_IP_TABLE_NAME;
        Yii::$app->db->createCommand()->truncateTable($dbIpTableName)->execute();
        foreach ($ipsArray as $ip) {
            $row = explode("\t", $ip);
            $values .= '(' . (float)$row[0] .
                ',' . (float)$row[1] .
                ',' . Yii::$app->db->quoteValue($row[3]) .
                ',' . ($row[4] !== '-' ? (int)$row[4] : 0) .
                ')';
            ++$i;

            if ($i === self::DB_IP_INSERTING_ROWS) {
                Yii::$app->db->createCommand(
                    "INSERT INTO {$dbIpTableName} (ip_begin, ip_end, country_code, city_id)
                    VALUES {$values}"
                )->execute();
                $i = 0;
                $values = '';
                continue;
            }
            $values .= ',';
        }

        // оставшиеся строки не вошедшие в пакеты
        Yii::$app->db->createCommand(
            "INSERT INTO {$dbIpTableName} (ip_begin, ip_end, country_code, city_id)
            VALUES " . rtrim($values, ',')
        )->execute();
    }

    /**
     * Метод загружает архив с данными с адреса self::ARCHIVE_URL.
     * @return bool|string путь к загруженному файлу или false если файл загрузить не удалось.
     */
    protected function getArchive()
    {
        $fileData = $this->getRemoteContent(self::ARCHIVE_URL);
        if ($fileData == false) {
            return false;
        }

        $fileName = Yii::getAlias('@runtime') . DIRECTORY_SEPARATOR .
            substr(strrchr(self::ARCHIVE_URL, '/'), 1);
        if (file_put_contents($fileName, $fileData) != false) {
            return $fileName;
        }

        return false;
    }

    /**
     * Метод возвращает содержимое документа полученного по указанному url.
     * @param string $url
     * @return mixed|string
     */
    protected function getRemoteContent($url)
    {
        if (function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt_array($curl, [
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true
            ]);
            $data = curl_exec($curl);
            curl_close($curl);
            return $data;
        } else {
            return file_get_contents($url);
        }
    }

}
