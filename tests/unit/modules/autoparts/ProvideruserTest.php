<?php
namespace modules\autoparts;

use SoapClient;
use Exception;
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

        $link = mysql_connect('localhost', 'mysql_user', 'mysql_password');
        if (!$link) {
            die('Ошибка соединения: ' . mysql_error());
        }
        echo 'Успешно соединились';
        mysql_close($link);
    }
}