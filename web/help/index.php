<?php
/**  
* Пример реализации работы с SOAP-сервисами компании Автостелс на языке PHP.
*
* Данный скрипт реализует:
* • работу с сервисом поиска ценовых предложений.
* • размещение предложения в корзине
*
* Системные требования
* •	PHP5
* •	Установленный модуль SOAP (обычно входит в дистрибутив PHP5)
* •	Установленный модуль openssl (обычно входит в дистрибутив PHP5)
* •	Установленный модуль SimpleXML
*
* © Autostels
*/


/**  
 * Вспомогательные функции
 */
   
   /**
    * generateRandom
    * 
    * Генерирует случайную строку из чисел заданой длины
    * 
    * @param int $maxlen длина строки
    * @return string
    */
   function generateRandom($maxlen = 32) {
      $code = '';
      while (strlen($code) < $maxlen) {
         $code .= mt_rand(0, 9);
      }
      return $code;
   }

	/**  
    * validateData
    * 
    * Фунцкия производит проверку и подготовку данных для отправки в запрос
    * 
    * @param &array $data ссылка на ассоц. массив с данными
    * @param &array $errors ссылка на массив ошибок
    * @return true в случае, если данные корректны, false при ошибке
    */
	function validateData(&$data, &$errors) {
		if (!$data['search_code'])
			$errors[] = 'Необходимо ввести номер для поиска';
		
		if (!$data['session_id'])
			$errors[] = 'Необходимо указать ID входа для работы с сервисом';
		
		if ((!$data['session_login'] || !$data['session_password']) && !$data['session_guid'])
			$errors[] = 'Необходимо ввести логин и пароль'.$data['session_guid'];
			
		$data['instock'] = $data['instock'] ? 1 : 0;
		$data['showcross'] = $data['showcross'] ? 1 : 0;
		$data['periodmin'] = $data['periodmin'] ? (int)$data['periodmin'] : -1;
		$data['periodmax'] = $data['periodmax'] ? (int)$data['periodmax'] : -1;
		
		return count($errors) ? false : true;
	}
	
	/**  
    * createSearchRequestXML
    * 
    * Генерация строки запроса на поиск
    * 
    * @param &array $data ссылка на ассоц. массив с данными
    * @return string возвращает строку с XML
    */
	function createSearchRequestXML($data) {
		$session_info = $data['session_guid'] ? 
			'SessionGUID="'.$data['session_guid'].'"' : 
			'UserLogin="'.base64_encode($data['session_login']).'" UserPass="'.base64_encode($data['session_password']).'"';
		
		$xml = '<root>
				  <SessionInfo ParentID="'.$data['session_id'].'" '.$session_info.'/>
				  <search>
					 <skeys>
						<skey>'.$data['search_code'].'</skey>
					 </skeys>
					 <instock>'.$data['instock'].'</instock>
					 <showcross>'.$data['showcross'].'</showcross>
					 <periodmin>'.$data['periodmin'].'</periodmin>
					 <periodmax>'.$data['periodmax'].'</periodmax>
				  </search>
				</root>';
		return $xml;
	}
   
   /**  
    * createAddBasketRequestXML
    * 
    * Генерация строки запроса на добавление в корзину
    * 
    * @param &array $data ссылка на ассоц. массив с данными
    * @return string возвращает строку с XML
    */
   function createAddBasketRequestXML($data) {
      $session_info = $data['session_guid'] ? 
			'SessionGUID="'.$data['session_guid'].'"' : 
			'UserLogin="'.base64_encode($data['session_login']).'" UserPass="'.base64_encode($data['session_password']).'"';
		$xml = '<root>
                 <SessionInfo ParentID="'.$data['session_id'].'" '.$session_info.' />
                 <rows>
                    <row>
                        <Reference>'.$data['Reference'].'</Reference>
                        <AnalogueCodeAsIs>'.$data['AnalogueCodeAsIs'].'</AnalogueCodeAsIs>
                        <AnalogueManufacturerName>'.$data['AnalogueManufacturerName'].'</AnalogueManufacturerName>
                        <OfferName>'.$data['OfferName'].'</OfferName>
                        <LotBase>'.$data['LotBase'].'</LotBase>
                        <LotType>'.$data['LotType'].'</LotType>
                        <PriceListDiscountCode>'.$data['PriceListDiscountCode'].'</PriceListDiscountCode>
                        <Price>'.$data['Price'].'</Price>
                        <Quantity>'.$data['Quantity'].'</Quantity>
                        <PeriodMin>'.$data['PeriodMin'].'</PeriodMin>
                        <ConstraintPriceUp>-1</ConstraintPriceUp>
                        <ConstraintPeriodMinUp>-1</ConstraintPeriodMinUp>
                    </row>
                 </rows>	 
				</root>';
		return $xml;
   }
	
	/**  
    * parseSearchResponseXML
    * 
    * Разбор ответа сервиса поиска.
    * 
	 * Собственно просто преобразует данные из SimpleXMLObject в массив,
    * также добавляет к каждой записи уникальный ReferenceID. В данном примере
    * в этом качестве будет выступать случайным образом сгенерированная строка.
    * В реальном использовании Reference обозначает ID конкретной записи в контексте
    * системы, в которой используются сервисы (например, id из таблицы БД, с которой 
    * сопоставлено предложение)
	 *	
    * @param SimpleXMLObject XML-объект
    * @return array возвращает массив данных
    */
	function parseSearchResponseXML($xml) {
		$data = array();
		foreach($xml->rows->row as $row) {
			$_row = array();
			foreach($row as $key => $field) {
				$_row[(string)$key] = (string)$field;
			}
         $_row['Reference'] = generateRandom(9);
			$data[] = $_row;
		}
		return $data;
	}
   
   
   /**
    * parseAddBasketResponseXML
    * 
    * Разбор ответа сервиса добавления в корзину.
    * Ответ содержит набор строк с результатами размещения выбранные позиций
    * В этом примере разбор ответа сводится к простой конвертации результата в массив.
    * Интерпретация и вывод результата происходит в файле /html/result_basket.html
    * 
    * @param SimpleXMLObject $xml XML-объект
    * @return array возвращает массив с результатами
    */
   function parseAddBasketResponseXML($xml) {
      $data = array();
		foreach($xml->rows->row as $row) {
			$_row = array();
			foreach($row as $key => $field) {
				$_row[(string)$key] = (string)$field;
			}
			$data[] = $_row;
		}
		return $data;
   }

/**  
 * Основное тело скрипта
 */
	
	//Обработка входных данных:
	//Значения формы по-умолчанию
	$defaults = array(
		'session_id' => '',
		'session_guid' => '',
		'session_login' => '',
		'session_password' => '',
		'search_code' => 'OC47',
		'instock' => 'ON',
		'showcross' => '',
		'periodmin' => 0,
		'periodmax' => 10,
	);
	
	//Получение POST данных
	$data = isset($_POST['session_id']) ? array_merge($defaults, $_POST) : $defaults;

   //Поиск:
   $action = isset($_POST['do']) ? $_POST['do'] : FALSE;
   
	if ($action !== FALSE) {
		//Нажата одна из кнопок на форме
		switch($action) {
			//Нажата кнопка "Поиск"
			case 'search': 
				$errors = array();
				$parsed_data = $data;	//Данные из формы копируются в другую переменную, чтобы 
												//подготовить их для формирования запроса.
												//Исходные данные будут отображены на форме.
				
				//Проверка данных
				if (validateData($parsed_data, $errors)) {
					//Подключение класса SOAP-клиента и создание экземпляра
					require_once("lib/soap_transport.php");
					$SOAP = new soap_transport();
					
					//Генерация запроса
					$requestXMLstring = createSearchRequestXML($parsed_data);
					
					//Выполнение запроса
					$responceXML = $SOAP->query('SearchOffer', array('SearchParametersXml' => $requestXMLstring), $errors);
					
					//Получен ответ
					if ($responceXML) {
						//Установка параметра session_guid, полученного из ответа сервиса.
						//Параметр используется, как замена связке session_login + session_password,
						//и при повторном поиске может быть подставлен в запрос вместо неё
						$attr = $responceXML->rows->attributes();
						$data['session_guid'] = (string)$attr['SessionGUID'];
						
						//Разбор данных ответа
						$result = parseSearchResponseXML($responceXML);
					}
				}
			break;
         
         //Нажата кнопка "Добавить в корзину"
         case 'add_basket':
            //Получение POST данных (в примере используется одиночное добавление записей,
            //но метод допускает добавление множества позиций за раз)
            $defaults = array(
               'session_id' => '',
               'session_guid' => '',
               'session_login' => '',
               'session_password' => '',
               'Reference' => '',
               'AnalogueCodeAsIs' => '',
               'AnalogueManufacturerName' => '',
               'OfferName' => '',
               'LotBase' => 1,
               'LotType' => 0,
               'PriceListDiscountCode' => 1,
               'Price' => 0,
               'Quantity' => 1,
               'PeriodMin' => 1,
               'ConstraintPriceUp' => -1,
               'ConstraintPeriodMinUp' => -1,
            );
            $parsed_data = array_merge($defaults, $_POST);
            
            require_once("lib/soap_transport.php");
            $SOAP = new soap_transport();

            //Генерация запроса
            $requestXMLstring = createAddBasketRequestXML($parsed_data);

            //Выполнение запроса
            $responceXML = $SOAP->query('AddBasket', array('AddBasketXml' => $requestXMLstring), $errors);
            
            //Разбор данных ответа
            if ($responceXML) 
               $basket_result = parseAddBasketResponseXML($responceXML);
            
            
         break;
				
			//Нажата кнопка "Сбросить параметры"
			case 'reset':
				$data = $defaults;
			break;
		}
	} 
	
/**  
 * Вывод
 */	
	
	header("Content-Type: text/html; charset=utf-8");
	
	//Шапка страницы
	include('html/header.html');
	
	//Форма поиска
	include('html/form.html');
	
   //Если произведено добавление в корзину
   if ($action == 'add_basket') {
      if (count($errors)) 
			//Блок ошибок (при их наличии)
			include('html/error.php');
		else		{			  
			//Блок результатов добавления в корзину
			include('html/result_basket.html');
      }
   }
   
	//Если производится поиск
	if($action == 'search') {
		if (count($errors)) 
			//Блок ошибок (при их наличии)
			include('html/error.html');
		else					  
			//Блок результатов поиска
			include('html/result.html');
	}
	
	//Подвал страницы
	include('html/footer.html');
?>

