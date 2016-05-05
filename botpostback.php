<?php
/**
* actions for postback 
*
*
* @author    Carlos Andres Patino <patispawn@gmail.com>    
* @license: Property patispawn
* @version    0.1
*/ 
require_once('Pusher.php');
require_once('bitly.php');


class Botpostback {  

  protected static $api_key =   "MYAPIKEY";

  function __construct(){

  }

  /**   
  * Description: Statistics Champion
  *
  * Observation: 
  *
  *
  * @return array $mensajes 
  **/   
  function getStatisticsChampion($champion){
    $mensajes = null;
    $urlrequest = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion/".$champion."?champData=all&api_key=". self::$api_key;
    $datosheroe = $this->peticionGet($urlrequest); 

    //attack
    $mensaje = "";
    $attack = $datosheroe->info->attack;
    $mensaje =  $mensaje ."Attack     |";
    for ($i=0; $i < 13; $i++) { 
       if($i < $attack){
          $mensaje =  $mensaje ."#";
       }else{
          $mensaje =  $mensaje ." ";                    
       } 
    }
    $mensaje = $mensaje. "|";
    $mensajes[] = $mensaje ;

    //defense
    $mensaje = "";
    $defense = $datosheroe->info->defense;
    $mensaje =  $mensaje ."Defense    |";
    for ($i=0; $i < 13; $i++) { 
       if($i < $defense){
          $mensaje =  $mensaje ."#";
       }else{
          $mensaje =  $mensaje ." ";                    
       } 
    }
    $mensaje = $mensaje. "|";
    $mensajes[] = $mensaje ;

    //magia ABILITY
    $mensaje = "";
    $magic = $datosheroe->info->magic;
    $mensaje =  $mensaje ."Ability    |";
    for ($i=0; $i < 13; $i++) { 
       if($i < $magic){
          $mensaje =  $mensaje ."#";
       }else{
          $mensaje =  $mensaje ." ";                    
       } 
    }
    $mensaje = $mensaje. "|";
    $mensajes[] = $mensaje ;


    //difficulty
    $mensaje = "";
    $difficulty = $datosheroe->info->difficulty;
    $mensaje =  $mensaje ."Difficulty |";
    for ($i=0; $i < 13; $i++) { 
       if($i < $difficulty){
          $mensaje =  $mensaje ."#";
       }else{
          $mensaje =  $mensaje ." ";                    
       } 
    }
    $mensaje = $mensaje. "|";
    $mensajes[] = $mensaje ;

    //other statistics 1
    $mensaje = "";
    $mensaje = "Health: ". ($datosheroe->stats->hp + $datosheroe->stats->hpperlevel). " (+".$datosheroe->stats->hpperlevel.") ";
    $mensaje = $mensaje.
               "Health Regen: ". ($datosheroe->stats->hpregen + $datosheroe->stats->hpregenperlevel). " (+".$datosheroe->stats->hpregenperlevel.") ";
    $mensaje = $mensaje.
               "Mana: ". ($datosheroe->stats->mp + $datosheroe->stats->mpperlevel). " (+".$datosheroe->stats->mpperlevel.") ";
    $mensaje = $mensaje.
               "Mana Regen: ". ($datosheroe->stats->mpregen + $datosheroe->stats->mpregenperlevel). " (+".$datosheroe->stats->mpregenperlevel.") ";
    $mensaje = $mensaje. "Move Speed: ". $datosheroe->stats->movespeed ;
    $mensajes[] = $mensaje ;

    //other statistics 2
    $mensaje = "";
    $mensaje = "Att. Damage: ". ($datosheroe->stats->attackdamage + $datosheroe->stats->attackdamageperlevel). " (+".$datosheroe->stats->attackdamageperlevel.") ";
    $mensaje = $mensaje.
               "Att. Speed: ". round((0.625 /(1 + $datosheroe->stats->attackspeedoffset)),3). " (%".$datosheroe->stats->attackspeedperlevel.") ";
    $mensaje = $mensaje. "Att. Range: ". $datosheroe->stats->attackrange ;               
    $mensaje = $mensaje.
               "Armor: ". ($datosheroe->stats->armor + $datosheroe->stats->armorperlevel). " (+".$datosheroe->stats->armorperlevel.") ";
    $mensaje = $mensaje.
               "Magic Resist: ". ($datosheroe->stats->spellblock + $datosheroe->stats->spellblockperlevel). " (+".$datosheroe->stats->spellblockperlevel.") ";
    $mensajes[] = $mensaje ;


    return $mensajes;
  }

  /**   
  * Description: Statistics Champion
  *
  * Observation: 
  *
  *
  * @return array $mensajes 
  **/   
  function getSpellsChampion($champion){
    $mensajes = null;

    $urlrequest = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion/".$champion."?champData=all&api_key=". self::$api_key;
    $datosheroe = $this->peticionGet($urlrequest); 
    for ($j=0; $j < count($datosheroe->spells); $j++) { 

        $imagen ="http://ddragon.leagueoflegends.com/cdn/6.8.1/img/spell/".$datosheroe->spells[$j]->image->full;
        $mensajes[]= array('type'=>"2",'message'=>$imagen);

        $effects = null;
        $effectBurn = $datosheroe->spells[$j]->effectBurn;
        for ($k=0; $k < count($effectBurn); $k++) { 
            $effects['e'.$k] = $effectBurn[$k]  ; 
        }

        if(isset($datosheroe->spells[$j]->vars)){
          $vars = $datosheroe->spells[$j]->vars;
          for ($k=0; $k < count($vars); $k++) { 
              $key = $vars[$k]->key;
              $xcoeficientes = "";
              $coeff = $vars[$k]->coeff;

              for ($l=0; $l < count($coeff); $l++) { 
                if(empty( $xcoeficientes)){
                    $xcoeficientes = $coeff[$l]; 
                }else{
                    $xcoeficientes = $xcoeficientes ."/".$coeff[$l]; 
                }
              }

              $effects[$key] = $xcoeficientes;

          }   
        }
     

        $mensaje = $datosheroe->spells[$j]->sanitizedTooltip;
        foreach ($effects as $clave=>$valor) {
          $mensaje = str_replace('{{ '.$clave.' }}', $valor, $mensaje);
        }  

        if(strlen($mensaje) > 300){
          $parmensaje =  substr($mensaje, 0, 300);
          $ultspace = strrpos($parmensaje," ");
          $mensajes[]= array('type'=>"1",'message'=>substr($mensaje, 0, $ultspace));
          $mensajes[]= array('type'=>"1",'message'=>substr($mensaje, $ultspace));
        }else{
          $mensajes[]= array('type'=>"1",'message'=>$mensaje);
        }
    }

    return $mensajes;
  } 


  /**   
  * Description: Info item
  *
  * Observation: 
  *
  *
  * @return array $mensajes 
  **/   
  function getInfoItem($item){
    $mensajes = null;
    $urlrequest = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/item/".$item."?itemData=all&api_key=". self::$api_key;
    $datositems = $this->peticionGet($urlrequest); 

    $mensaje = $datositems->sanitizedDescription;
    if(strlen($mensaje) > 300){
      $parmensaje =  substr($mensaje, 0, 300);
      $ultspace = strrpos($parmensaje," ");

      $mensajes[]= array('type'=>"1",'message'=>substr($mensaje, 0, $ultspace));
      $mensajes[]= array('type'=>"1",'message'=>substr($mensaje, $ultspace));

    }else{
        $mensajes[]= array('type'=>"1",'message'=>$mensaje);
    }

    //costo
    $mensaje = "Cost: ".$datositems->gold->total." (".$datositems->gold->base.")";
    $mensajes[]= array('type'=>"1",'message'=>$mensaje);


    //forjado de (from)
    if(isset($datositems->from)){
      $mensajes[]= array('type'=>"1",'message'=>'From:');

      $numberitems = 0;
      $resultsitems = "";
      for ($i=0; $i < count($datositems->from); $i++) { 
          if($numberitems == 10){
            break;
          } 

          $numberitems = $numberitems + 1;
          $urlrequestfrom = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/item/".$datositems->from[$i]."?itemData=all&api_key=". self::$api_key;
          $datositemfrom = $this->peticionGet($urlrequestfrom);
          if(is_null($datositemfrom) || $datositemfrom == "false"){

          }else{          
            $miurl =  "http://ddragon.leagueoflegends.com/cdn/6.8.1/img/item/".$datositemfrom->image->full;
            $resultsurl = bitly_v3_shorten($miurl, 'MYBITLYACCESSTOKEN', 'bit.ly');

            $resultitem ='{"title": "'.$datositemfrom->name.'",
                          "image_url": "'.$resultsurl["url"].'",
                          "subtitle" : "'.$datositemfrom->plaintext.'",
                          "buttons":[
                                    {
                                      "type":"postback",
                                      "title":"Info",
                                      "payload":"infoitem.'.$datositemfrom->id.'"
                                    }                                  
                                  ]                          
                        }';          

            if(empty($resultsitems)){
              $resultsitems = $resultitem;
            }else{
              $resultsitems = $resultsitems ."," .$resultitem;
            }
          }  
      }

      
      if(empty($resultsitems)){
        $mensajes[]= array('type'=>"1",'message'=>'No data found');
      }else{
        $mensajes[]= array('type'=>"2",'message'=>$resultsitems);
      }

    }

    //forja a (into)
    if(isset($datositems->into)){
      $mensajes[]= array('type'=>"1",'message'=>'Into:');

      $numberitems = 0;
      $resultsitems = "";

      for ($i=0; $i < count($datositems->into); $i++) { 
          if($numberitems == 10){
            break;
          }

          $numberitems = $numberitems + 1;
          $urlrequestinto = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/item/".$datositems->into[$i]."?itemData=all&api_key=". self::$api_key;

          $datositeminto = $this->peticionGet($urlrequestinto); 
          if(is_null($datositeminto) || $datositeminto == "false"){

          }else{
            $miurl =  "http://ddragon.leagueoflegends.com/cdn/6.8.1/img/item/".$datositeminto->image->full;
            $resultsurl = bitly_v3_shorten($miurl, 'MYBITLYACCESSTOKEN', 'bit.ly');

            $resultitem ='{"title": "'.$datositeminto->name.'",
                          "image_url": "'.$resultsurl["url"].'",
                          "subtitle" : "'.$datositeminto->plaintext.'",
                          "buttons":[
                                    {
                                      "type":"postback",
                                      "title":"Info",
                                      "payload":"infoitem.'.$datositeminto->id.'"
                                    }                                  
                                  ]                          
                        }';          

            if(empty($resultsitems)){
              $resultsitems = $resultitem;
            }else{
              $resultsitems = $resultsitems ."," .$resultitem;
            }              
          }

      }


      if(empty($resultsitems)){
        $mensajes[]= array('type'=>"1",'message'=>'No data found');        
      }else{
        $mensajes[]= array('type'=>"2",'message'=>$resultsitems);        
      }

    }


    return $mensajes;
  }  


  /**   
  * Description: Get petition url
  *
  * Observation: 
  *
  *
  * @return String $result
  **/  
  public function peticionGet($miurl){

    $options = array(
     'http' => array(
       'method' => 'GET',
       'header' => "Content-Type: application/json\r\n" .
       "Accept: application/json\r\n"
       )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($miurl, false, $context);


    return json_decode($result);

  }


}
?>