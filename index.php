<?php
/**
* Controller the requests
*
* @author    Carlos Andres Patino <patispawn@gmail.com>    
* @license: Property patispawn
* @version    0.1
*/ 

require_once('Pusher.php');
require_once('bitly.php');
require_once('botfacebook.php');
require_once('botwords.php');
require_once('botpostback.php');


$botfacebook = new Botfacebook();
$botwords = new Botwords();
$botpostback = new Botpostback();

// Set this Verify Token Value on your Facebook App 
$verify_token = $_REQUEST['hub_verify_token'];
$challenge = $_REQUEST['hub_challenge'];
if ($verify_token === 'MYVERIFYTOKEN') {
  echo $challenge;
}else{
  echo "error";
}

//set welcome message
//$result = $botfacebook->sendwelcome("Hi there, let’s get started. If you get lost, just type help.");


//analyzes the request
$input = json_decode(file_get_contents('php://input'), true);
// Get the Senders Graph ID
$senderx = $input['entry'][0]['messaging'][0]['sender']['id'];
$sender = number_format($senderx, 0,"","");


//API Url and Access Token, generate this token value on your Facebook App Page
if(isset($input['entry'][0]['messaging'][0]['message'])){
  // Get the returned message
  $message = $input['entry'][0]['messaging'][0]['message']['text'];

  if(strtoupper($message) == "LOL FREE CHAMPIONS"){
      $result = $botfacebook->sendtext($sender,"Moment please...");
      $freechamps = $botwords->getFreeChampions();
      $result = $botfacebook->sendgeneric($sender,$freechamps);
  }


  if(strtoupper(substr($message,0,13)) == "LOL CHAMPION "){
    $results = $botwords->getSearchChampion($message);
    //show results
    if(count($results) > 0){
      $result = $botfacebook->sendtext($sender,"Moment please...");
      $freechamps = $botwords->getInfoChampions($results);
      $result = $botfacebook->sendgeneric($sender,$freechamps);      
    }else{
      $result = $botfacebook->sendtext($sender,"No data found");
    }
  }

  if(strtoupper(substr($message,0,9)) == "LOL ITEM "){

    $result = $botfacebook->sendtext($sender,"Moment please...");
    $resultsitems = $botwords->getSearchItems($message);
    if(empty($resultsitems)){
      $result = $botfacebook->sendtext($sender,"No data found");
    }else{
      $result = $botfacebook->sendgeneric($sender,$resultsitems);      
    }
  }  

  if(strtoupper(substr($message,0,4)) == "HELP"){
    $result = $botfacebook->sendtext($sender,"Write LOL FREE CHAMPIONS");
    $result = $botfacebook->sendtext($sender,"To know that champions are free to play");
    $result = $botfacebook->sendtext($sender,"Write LOL CHAMPION xxxx");
    $result = $botfacebook->sendtext($sender,"Replace xxxx by name or part of the name of the champion that you want to obtain information");
    $result = $botfacebook->sendtext($sender,"Write LOL ITEM xxxx");
    $result = $botfacebook->sendtext($sender,"Replace xxxx by name or part of the name of the item that you want to obtain information");

  }  
}

 
if(isset($input['entry'][0]['messaging'][0]['postback'])){

    $message = $input['entry'][0]['messaging'][0]['postback']['payload'];
    $postback = explode(".", $message);

    if($postback[0] == 'statistics'){
        $result = $botpostback->getStatisticsChampion($postback[1]);
        for ($i=0; $i < count($result); $i++) { 
          $botfacebook->sendtext($sender,$result[$i]);
        }
    }


    if($postback[0] == 'spells'){
        $result = $botpostback->getSpellsChampion($postback[1]);
        for ($i=0; $i < count($result); $i++) { 
          if($result[$i]['type']  == "1"){
            $botfacebook->sendtext($sender,$result[$i]['message']);
          }else{
            $botfacebook->sendimage($sender,$result[$i]['message']);            
          }
        }
    }  

    if($postback[0] == 'infoitem'){
        $result = $botpostback->getInfoItem($postback[1]);
        //midebug("envio",json_encode($result));
        for ($i=0; $i < count($result); $i++) { 
          if($result[$i]['type']  == "1"){
            $botfacebook->sendtext($sender,$result[$i]['message']);
          }else{
            $botfacebook->sendgeneric($sender,$result[$i]['message']);            
          }
        }
    }  
}


function midebug($evento,$mensaje){
   
  $app_key = "MYAPPKEY";
  $app_secret = "MYAPPSECRET";
  $app_id  = "MYAPPID";

  $pusher = new Pusher( $app_key, $app_secret, $app_id );
  $pusher->trigger('channellog', $evento,$mensaje);
}


?>