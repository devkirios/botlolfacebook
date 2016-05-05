<?php
/**
* handles the request to messenger Platform
*
*
* @author    Carlos Andres Patino <patispawn@gmail.com>    
* @license: Property patispawn
* @version    0.1
*/ 
require_once('Pusher.php');


class Botfacebook {  

  protected static $accesstoken =   "MYACCESTOKEN";

  function __construct(){

  }

  /**   
  * Description: send Structured Message - Generic Template
  *
  * Observation: 
  *
  * @param $destino Sender id
  * @param $mensaje JSON format elements
  *
  * @return bool $result 
  **/   
  function sendgeneric($destino,$mensaje){



    $jsonData = '
    {
    "recipient":{
            "id":"' . $destino . '"
    },
    "message":{
      "attachment":{
        "type":"template",
        "payload":{
          "template_type":"generic",
          "elements":['
          .$mensaje.
          ']
        }
      }
    }
    }';

    $options = array(
      'http' => array(
       'method' => 'POST',
       'content' => $jsonData,
       'header' => "Content-Type: application/json\r\n" .
       "Accept: application/json\r\n"
      )
    );



   $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . self::$accesstoken;
   $context = stream_context_create($options);
   $result = file_get_contents($url, false, $context);

   return $result;
  }

  /**   
  * Description: send Structured Message - Text Message
  *
  * Observation: 
  *
  * @param $destino Sender id
  * @param $mensaje String text
  *
  * @return bool $result 
  **/   
  function sendtext($destino,$mensaje){

    $jsonData = '{
        "recipient":{
            "id":"' . $destino . '"
        }, 
        "message":{
            "text":"' . $mensaje . '"
        }
    }';

    $options = array(
      'http' => array(
       'method' => 'POST',
       'content' => $jsonData,
       'header' => "Content-Type: application/json\r\n" .
       "Accept: application/json\r\n"
      )
    );



   $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . self::$accesstoken;
   $context = stream_context_create($options);
   $result = file_get_contents($url, false, $context);

   return $result;

  }
  /**   
  * Description: send Structured Message - Image (url)
  *
  * Observation: 
  *
  * @param $destino Sender id
  * @param $mensaje String image url
  *
  * @return bool $result 
  **/  
  function sendimage($destino,$imagen,$accesstoken){

    $jsonData = '{
        "recipient":{
            "id":"' . $destino . '"
        }, 
        "message":{
            "attachment":{
                  "type":"image",
                  "payload":{
                    "url":"'.$imagen.'"
                  }
                }          
        }
    }';

    $options = array(
      'http' => array(
       'method' => 'POST',
       'content' => $jsonData,
       'header' => "Content-Type: application/json\r\n" .
       "Accept: application/json\r\n"
      )
    );

   $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . self::$accesstoken;
   $context = stream_context_create($options);
   $result = file_get_contents($url, false, $context);

    return $result;

  }

  /**   
  * Description: send Structured Message - Text Message
  *
  * Observation: 
  *
  * @param $destino Sender id
  * @param $mensaje String text
  *
  * @return bool $result 
  **/   
  function sendwelcome($mensaje){

    $jsonData = '{
        "setting_type":"call_to_actions",
        "thread_state":"new_thread",
        "call_to_actions":[
          {
            "message":{
              "text":"'.$mensaje.'"
            }
          }
        ]
      }';

    $options = array(
      'http' => array(
       'method' => 'POST',
       'content' => $jsonData,
       'header' => "Content-Type: application/json\r\n" .
       "Accept: application/json\r\n"
      )
    );



   $url = 'https://graph.facebook.com/v2.6/MYPAGEID/thread_settings?access_token=' . self::$accesstoken;
   $context = stream_context_create($options);
   $result = file_get_contents($url, false, $context);

   return $result;

  }

}
?>