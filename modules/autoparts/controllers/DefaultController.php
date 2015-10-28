<?php
/**
 * @author: Eugene Brx
 * @email: compuniti@mail.ru
 * @date: 13.08.15
 * @time: 11:59
 */

namespace app\modules\autoparts\controllers;

use app\modules\tovar\models\Tovar;

class DefaultController extends ProviderController
{
    public function actionIndex()
    {
//        76078762
//    84893361
//        79210276
        var_dump(Tovar::getProviderOrderState(['provider' => 'Partkom', 'order_id' => 'НДИ1681194'], 6));

    }
}
//  0 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string 'ML9035R' (length=7)
//      'name' => string 'Линк  "Masuma"' (length=18)
//      'quantity' => int 1
//      'comment' => string 'клиент Александр Сидельников: lc200 89172835064' (length=73)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//  1 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string '4783160080' (length=10)
//      'name' => string 'ПОРШЕНЬ' (length=14)
//      'quantity' => int 1
//      'comment' => string 'клиент Александр Сидельников: lc200 89172835064' (length=73)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//  2 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string 'PN21001' (length=7)
//      'name' => string 'Колодки тормозные дисковые' (length=50)
//      'quantity' => int 1
//      'comment' => string 'клиент Александр Сидельников:' (length=55)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//  3 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string '771440H010' (length=10)
//      'name' => string 'ФИКСАТОР БЕНЗОНАСОСА' (length=39)
//      'quantity' => int 1
//      'comment' => string 'клиент Александр Сидельников: королла то 89872222800' (length=87)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//  4 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string '771690D030' (length=10)
//      'name' => string 'Уплотнительное кольцо' (length=41)
//      'quantity' => int 1
//      'comment' => string 'клиент Александр Сидельников: королла то 89872222800' (length=87)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//  5 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string '90301W0002' (length=10)
//      'name' => string 'КОЛЬЦО' (length=12)
//      'quantity' => int 1
//      'comment' => string 'клиент Александр Сидельников: королла то 89872222800' (length=87)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//  6 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string 'ST7702405010' (length=12)
//      'name' => string 'Фильтр топливный погружной TOYOTA COROLLA N E180/ZRE18  13-/AURIS  ZE18  12-/AVENSIS  T27  08- 1NR/1' (length=124)
//      'quantity' => int 1
//      'comment' => string 'клиент Александр Сидельников: королла то 89872222800' (length=87)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//  7 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string 'DF6679' (length=6)
//      'name' => string 'Диск тормозной передний TOYOTA COROLLA, AURIS (E18) (277мм)' (length=82)
//      'quantity' => int 2
//      'comment' => string 'клиент Александр Сидельников: королла то 89872222800' (length=87)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//  8 =>
//    array (size=7)
//      'order_id' => string 'НДИ1666811' (length=13)
//      'code' => string '8934133190C3' (length=12)
//      'name' => string 'ДАТЧИК ПАРКОВОЧНЫЙ' (length=35)
//      'quantity' => int 1
//      'comment' => string 'клиент Александр Сидельников: мендельазот 89656099271' (length=90)
//      'status' => string '26' (length=2)
//      'status_name' => string 'Выдан: доставка' (length=28)
//<?xml version="1.0" encoding="UTF-8"?>
<!--    <env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope"-->
<!--                  xmlns:ns1="http://www.part-kom.ru/webservice/motion.php"-->
<!--                  xmlns:xsd="http://www.w3.org/2001/XMLSchema"-->
<!--                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"-->
<!--                  xmlns:enc="http://www.w3.org/2003/05/soap-encoding">-->
<!--        <env:Body>-->
<!--            <ns1:getMotion env:encodingStyle="http://www.w3.org/2003/05/soap-encoding">-->
<!--                <login xsi:type="xsd:string">chelny-KD</login>-->
<!--                <password xsi:type="xsd:string">54lmmsWchelny</password>-->
<!--                <detailNum xsi:type="xsd:string">1666811</detailNum>-->
<!--                <maker xsi:nil="true"/>-->
<!--                <orderNumber xsi:nil="true"/>-->
<!--                <states xsi:nil="true"/>-->
<!--                <limit xsi:nil="true"/>-->
<!--                <archive xsi:nil="true"/>-->
<!--                <comment xsi:nil="true"/>-->
<!--            </ns1:getMotion></env:Body>-->
<!--    </env:Envelope>-->


