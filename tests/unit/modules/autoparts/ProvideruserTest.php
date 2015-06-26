<?php
namespace modules\autoparts;

use SoapClient;
use Exception;
use PDO;
class ProvideruserTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function connectSoap($name){
        $params = require('config/params.php');

        $p = $params['Parts']['PartsProvider'][$name];

            try {
                $client = new SoapClient($p['_wsdl_uri']);
                $this->assertTrue(true);
            }
            catch (Exception $e) {

                $this->assertTrue(false);
            }
    }
    public function testEmexSoap()
    {
        $this->connectSoap('Emex');

    }
    public function testIksoraSoap()
    {
        $this->connectSoap('Iksora');
    }
    public function testPartkomSoap()
    {
        $this->connectSoap('Partkom');
    }
    public function testAvtostelsSoap()
    {
        $this->connectSoap('Avtostels');
    }
    public function testKdConnectDb(){
        defined('YII_ENV') or define('YII_ENV', 'prod');
        $db = require('config/db.php');

        $link = new PDO($db['dsn'], $db['username'], $db['password']);
        if ($link) {
            $this->assertTrue(true);
        }else{$this->assertTrue(false);}
$link=null;
    }
}