<?php
/**
* Класс работы с SOAP-клиентом
* 
* Класс реализует логику работы с сервисами компании Автостелс, 
* через модуль расширения PHP - SOAP
* 
* @author Autostels
* @version 1.1
*/
  class soap_transport
  {
    private $_wsdl_uri = 'https://allautoparts.ru/WEBService/SearchService.svc/wsdl?wsdl';   //Ссылка на WSDL-документ сервиса
    private static $_soap_client = false;                                                    //Объект SOAP-клиента
    private static $_inited = false;                                                         //Флаг инициализации

   /**  
    * init
    * 
    * Инициализирует класс, создаёт объект SOAP-клиента и открывает соединение
    * 
    * @param &array $errors ссылка на текущий массив ошибок
    * @return true в случае успеха, false при ошибке
    */
    public function init(&$errors)
    {
      if(!self::$_inited)
      {
         try
         {
           if (self::$_soap_client = @new SoapClient($this->_wsdl_uri, array('soap_version' => SOAP_1_1)))
               self::$_inited = true;
         }
         catch (Exception $e)
         {
            $errors[] = 'Произошла ошибка связи с сервером Автостэлс. '.$e->getMessage();
            return false;
         }
      }
      return self::$_inited;
    }

    /**  
     * query
     * 
     * Выполняет запрашиваемый метод сервиса
     * 
     * @param string $method имя метода
     * @param string $requestData данные запроса
     * @param &array $errors ссылка на текущий массив ошибок
     * @return объект SimpleXMLElement в случае успеха, false при ошибке
     */
    public function query($method, $requestData, &$errors)
    {
      //Инициализация
      if (!$this->init($errors))
      {
        $errors[] = 'Ошибка соединения с сервером Автостэлс: Не может быть инициализирован класс SoapClient';
        return false;
      }
      
      //Выполнение запроса
      $result =  self::$_soap_client->$method($requestData);
      $resultKey = $method.'Result';
      
      //Проверка ответа на соответствие формату XML
      try
      {
        $XML = new SimpleXMLElement($result->$resultKey);
      }
      catch (Exception $e)
      {
        $errors[] = 'Ошибка сервиса Автоселс: полученные данные не являются корректным XML';
        return false;
      }
      
      //Проверка ответа на ошибки
      if(isset($XML->error)) {
        $errors[] = 'Ошибка сервиса Автоселс: '.(string)$XML->error->message;
        if ((string)$XML->error->stacktrace)
          $errors[] = 'Отладочная информация: '.(string)$XML->error->stacktrace;
        return false;
      }
      
      //Закрытие соединение
      $this->close();
      
      return $XML;
    }
    
    /**  
     * close
     * 
     * Закрывает соединение
     * 
     * @param void
     * @return void
     */
    public function close()
    {
      if( self::$_inited )
      {
        self::$_inited = false;
        self::$_soap_client = false;
      }
    }

  }
?>