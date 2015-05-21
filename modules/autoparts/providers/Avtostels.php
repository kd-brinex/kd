<?php
namespace  app\modules\autoparts\providers;

/**
* Класс работы с SOAP-клиентом
* 
* Класс реализует логику работы с сервисами компании Автостелс, 
* через модуль расширения PHP - SOAP
* 
* @author Autostels
* @version 1.1
*/
  class Avtostels extends PartsProvider
  {
                                                       //Флаг инициализации

   /**  
    * init
    * 
    * Инициализирует класс, создаёт объект SOAP-клиента и открывает соединение
    * 
    * @param &array $errors ссылка на текущий массив ошибок
    * @return true в случае успеха, false при ошибке
    */

      public function getData(){
          $data =parent::getData();
          $defaults = array(
              'session_id' => '10708',
              'session_guid' => '',
              'session_login' => $this->login,
              'session_password' => $this->password,
              'search_code' => (isset($data['article']))?$data['article']:$this->article,
              'instock' => '1',
              'showcross' => '0',
              'periodmin' => -1,
              'periodmax' => 10,
          );
          $data = isset($data['session_id']) ? array_merge($defaults, $data) : $defaults;
          return $data;
      }
      public static function nameProvider()
      {
          return 'Москва';
      }
      public function xmlSearchOffer(){
          $data=$this->getData();
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

         return  ['SearchParametersXml'=>$xml];


      }
      public function update_estimation($value){
          return 50;
      }
  }
?>