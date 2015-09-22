<?php
/**
 * Created by PhpStorm.
 * User: marat
 * Date: 21.09.15
 * Time: 15:49
 */


//require "../../../models/payment.class.php";
use app\modules\user\models\GetPayment;

//Указываем локализацию (доступно ru | en | fr)
$Language = "ru";
// Указываем идентификатор мерчанта
$MerchantId='57211';
//$MerchantId='58957';
//Указываем приватный ключ (см. в ЛК PayOnline в разделе Сайты -> настройка -> Параметры интеграции)
$PrivateSecurityKey='3df0c3fa-de74-4548-8a5f-910883691c6f';
//$PrivateSecurityKey='0711458b-5dda-4140-ac89-315ee49f5283';
//Номер заказа (Строка, макс.50 символов)
$OrderId=$order['id'];
//Валюта (доступны следующие валюты | USD, EUR, RUB)
$Currency='RUB';
//Сумма к оплате (формат: 2 знака после запятой, разделитель ".")
$Amount=2;
//Описание заказа (не более 100 символов, запрещено использовать: адреса сайтов, email-ов и др.) необязательный параметр
$OrderDescription="Оплата запасных частей, ".$order->user_name.".";
//$OrderDescription="Оплата запасных частей, ".$order->user_name.",".$order->store->city->name.".";
//Срок действия платежа (По UTC+0) необязательный параметр
//$ValidUntil="2013-10-10 12:45:00";
//В случае неуспешной оплаты, плательщик будет переадресован, на данную страницу.
$FailUrl="http://payonline.ru";
// В случае успешной оплаты, плательщик будет переадресован, на данную страницу.
$ReturnUrl="yandex.ru";

//Создаем класс
$pay = new GetPayment;
$ValidUntil=date('Y-m-d h:i:s');
//var_dump($ValidUntil);die;
//Показываем ссылку на оплату
$result=$pay->GetPaymentURL(
    $pay->Language=$Language,
    $pay->Email=$order->user_email,
    $pay->MerchantId=$MerchantId,
    $pay->PrivateSecurityKey=$PrivateSecurityKey,
    $pay->OrderId=$order->number,
    $pay->Amount=number_format($order->orderSumma, 2, '.', ''),
    $pay->Currency=$Currency,
    $pay->OrderDescription=$OrderDescription,
//    $pay->ValidUntil=$ValidUntil,
    $pay->ReturnUrl=$ReturnUrl,
    $pay->FailUrl=$FailUrl);

echo "<meta http-equiv='refresh'  content='0; URL=".$result."'>";







