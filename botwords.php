<?php
/**
* actions for phrase or word
*
*
* @author    Carlos Andres Patino <patispawn@gmail.com>    
* @license: Property patispawn
* @version    0.1
*/ 
require_once('Pusher.php');
require_once('bitly.php');


class Botwords {  

  protected static $api_key =   "MYAPIKEY";

  function __construct(){

  }

  /**   
  * Description: LOL FREE CHAMPIONS
  *
  * Observation: 
  *
  *
  * @return String $freechamps 
  **/   
  function getFreeChampions(){

      $urlrequest = "https://na.api.pvp.net/api/lol/na/v1.2/champion?freeToPlay=true&api_key=". self::$api_key;
      $losheroes = $this->peticionGet($urlrequest);  
      $a_heroes = $losheroes->champions;
      $freechamps = "";
      $numberchamp = 0;
      for ($i=0; $i < count($a_heroes); $i++) { 
            if($numberchamp == '10'){
              break;
            } 

            $numberchamp = $numberchamp + 1 ;
            $urlrequest = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion/".$a_heroes[$i]->id."?champData=all&api_key=". self::$api_key;
            $datosheroes = $this->peticionGet($urlrequest); 

            $rolesheroes = "";
            for ($j=0; $j < count($datosheroes->tags); $j++) { 
              $rolesheroes = $rolesheroes . " " . $datosheroes->tags[$j];
            }

            $miurl =  "http://ddragon.leagueoflegends.com/cdn/6.8.1/img/champion/".$datosheroes->image->full;
            $results = bitly_v3_shorten($miurl, 'MYBITLYACCESSTOKEN', 'bit.ly');

            $freechamp ='{"title": "'.$datosheroes->name.'",
                          "image_url": "'.$results["url"].'",
                          "subtitle" : "'.$datosheroes->title.' Roles: '. $rolesheroes.'",
                          "buttons":[
                                    {
                                      "type":"postback",
                                      "title":"Statistics",
                                      "payload":"statistics.'.$datosheroes->id.'"
                                    },
                                    {
                                      "type":"postback",
                                      "title":"Spells",
                                      "payload":"spells.'.$datosheroes->id.'"
                                    }                                    
                                  ]                          
                        }';    
                          
            if(empty($freechamps)){
              $freechamps = $freechamp;
            }else{
              $freechamps = $freechamps ."," .$freechamp;
            }
      }


      return $freechamps;


  }


  /**   
  * Description: LOL CHAMPION
  *
  * @param $message Search string
  *
  * Observation:  search champion and get list
  *
  *
  * @return array $results 
  **/   
  function getSearchChampion($message){

    $arraychampion = $this->feedchampion();
    //clean accents
    $searchchampion = strtoupper(substr($message,13));
    $searchchampion = str_replace(' ', '', $searchchampion);
    $searchchampion = str_replace('"', '', $searchchampion);
    $searchchampion = str_replace('`', '', $searchchampion);
    $searchchampion = str_replace('´', '', $searchchampion);
    $searchchampion = str_replace("'", "", $searchchampion);
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
    $searchchampion = str_replace($a, $b, $searchchampion);
   
    //search champion
    $results = null;
    $numberchamp = 0;
    for ($i=0; $i < count($arraychampion); $i++) { 
      if($numberchamp == 10){
        break;
      } 
      
      $michampion = $arraychampion[$i]['key'];
      $resulsearch = strpos($michampion, $searchchampion);
      if ($resulsearch === false) {

      } else {
        $numberchamp = $numberchamp + 1;
        $results[] =  $arraychampion[$i]['id'];   
      }
    }

    return  $results;

  }  


/**   
  * Description: get info champions
  *
  * @param $message Search string
  *
  * Observation:  search champion and get list
  *
  *
  * @return string $freechamps 
  **/   
  function getInfoChampions($results){
    $freechamps = "";
      for ($i=0; $i < count($results); $i++) { 
        $urlrequest = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion/".$results[$i]."?champData=all&api_key=". self::$api_key;
        $datosheroes = $this->peticionGet($urlrequest); 

        $rolesheroes = "";
        for ($j=0; $j < count($datosheroes->tags); $j++) { 
          $rolesheroes = $rolesheroes . " " . $datosheroes->tags[$j];
        }

        $miurl =  "http://ddragon.leagueoflegends.com/cdn/6.8.1/img/champion/".$datosheroes->image->full;
        $resultsurl = bitly_v3_shorten($miurl, 'MYBITLYACCESSTOKEN', 'bit.ly');

        $freechamp ='{"title": "'.$datosheroes->name.'",
                      "image_url": "'.$resultsurl["url"].'",
                      "subtitle" : "'.$datosheroes->title.' Roles: '. $rolesheroes.'",
                      "buttons":[
                                {
                                  "type":"postback",
                                  "title":"Statistics",
                                  "payload":"statistics.'.$datosheroes->id.'"
                                },
                                {
                                  "type":"postback",
                                  "title":"Spells",
                                  "payload":"spells.'.$datosheroes->id.'"
                                }                                    
                              ]                          
                    }';  

                      
        if(empty($freechamps)){
          $freechamps = $freechamp;
        }else{
          $freechamps = $freechamps ."," .$freechamp;
        }
      }

    return $freechamps ;
  }  
 

 /**   
  * Description: get info items
  *
  * @param $message Search string
  *
  * Observation:  
  *
  *
  * @return string $resultsitems 
  **/   
  function getSearchItems($message){

    //clean spaces
    $searchitem = strtoupper(substr($message,9));
    $searchitem = str_replace(' ', '', $searchitem);
    


    $resultsitems = "";
    $urlrequest = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/item?itemListData=all&api_key=". self::$api_key;
    $lositems = $this->peticionGet($urlrequest);  
    $a_items = (array)$lositems->data;
    $numberitems = 0;
    foreach ($a_items as $valor) {
      if($numberitems == 10){
        break;
      } 
      $pajaritem = str_replace(' ', '', strtoupper($valor->name) );

      $resulsearch = strpos($pajaritem, $searchitem);
      if ($resulsearch === false) {

      } else {
        $numberitems = $numberitems + 1;

        $miurl =  "http://ddragon.leagueoflegends.com/cdn/6.8.1/img/item/".$valor->image->full;
        $resultsurl = bitly_v3_shorten($miurl, 'MYBITLYACCESSTOKEN', 'bit.ly');

        $resultitem ='{"title": "'.$valor->name.'",
                      "image_url": "'.$resultsurl["url"].'",
                      "subtitle" : "'.$valor->plaintext.'",
                      "buttons":[
                                {
                                  "type":"postback",
                                  "title":"Info",
                                  "payload":"infoitem.'.$valor->id.'"
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


    return $resultsitems;
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


  /**   
  * Description: Get champions
  *
  * Observation: TODO: change array per database
  *
  *
  * @return array $datos
  **/   
  public function feedchampion(){
    //TODO: change array per database
    $datos[] = array('key' =>'AATROX', 'id' =>'266');
    $datos[] = array('key' =>'AHRI', 'id' =>'103');
    $datos[] = array('key' =>'AKALI', 'id' =>'84');
    $datos[] = array('key' =>'ALISTAR', 'id' =>'12');
    $datos[] = array('key' =>'AMUMU', 'id' =>'32');
    $datos[] = array('key' =>'ANIVIA', 'id' =>'34');
    $datos[] = array('key' =>'ANNIE', 'id' =>'1');
    $datos[] = array('key' =>'ASHE', 'id' =>'22');
    $datos[] = array('key' =>'AURELIONSOL', 'id' =>'136');
    $datos[] = array('key' =>'AZIR', 'id' =>'268');
    $datos[] = array('key' =>'BARD', 'id' =>'432');
    $datos[] = array('key' =>'BLITZCRANK', 'id' =>'53');
    $datos[] = array('key' =>'BRAND', 'id' =>'63');
    $datos[] = array('key' =>'BRAUM', 'id' =>'201');
    $datos[] = array('key' =>'CAITLYN', 'id' =>'51');
    $datos[] = array('key' =>'CASSIOPEIA', 'id' =>'69');
    $datos[] = array('key' =>'CHOGATH', 'id' =>'31');
    $datos[] = array('key' =>'CORKI', 'id' =>'42');
    $datos[] = array('key' =>'DARIUS', 'id' =>'122');
    $datos[] = array('key' =>'DIANA', 'id' =>'131');
    $datos[] = array('key' =>'DRAVEN', 'id' =>'119');
    $datos[] = array('key' =>'DRMUNDO', 'id' =>'36');
    $datos[] = array('key' =>'EKKO', 'id' =>'245');
    $datos[] = array('key' =>'ELISE', 'id' =>'60');
    $datos[] = array('key' =>'EVELYNN', 'id' =>'28');
    $datos[] = array('key' =>'EZREAL', 'id' =>'81');
    $datos[] = array('key' =>'FIDDLESTICKS', 'id' =>'9');
    $datos[] = array('key' =>'FIORA', 'id' =>'114');
    $datos[] = array('key' =>'FIZZ', 'id' =>'105');
    $datos[] = array('key' =>'GALIO', 'id' =>'3');
    $datos[] = array('key' =>'GANGPLANK', 'id' =>'41');
    $datos[] = array('key' =>'GAREN', 'id' =>'86');
    $datos[] = array('key' =>'GNAR', 'id' =>'150');
    $datos[] = array('key' =>'GRAGAS', 'id' =>'79');
    $datos[] = array('key' =>'GRAVES', 'id' =>'104');
    $datos[] = array('key' =>'HECARIM', 'id' =>'120');
    $datos[] = array('key' =>'HEIMERDINGER', 'id' =>'74');
    $datos[] = array('key' =>'ILLAOI', 'id' =>'420');
    $datos[] = array('key' =>'IRELIA', 'id' =>'39');
    $datos[] = array('key' =>'JANNA', 'id' =>'40');
    $datos[] = array('key' =>'JARVANIV', 'id' =>'59');
    $datos[] = array('key' =>'JAX', 'id' =>'24');
    $datos[] = array('key' =>'JAYCE', 'id' =>'126');
    $datos[] = array('key' =>'JHIN', 'id' =>'202');
    $datos[] = array('key' =>'JINX', 'id' =>'222');
    $datos[] = array('key' =>'KALISTA', 'id' =>'429');
    $datos[] = array('key' =>'KARMA', 'id' =>'43');
    $datos[] = array('key' =>'KARTHUS', 'id' =>'30');
    $datos[] = array('key' =>'KASSADIN', 'id' =>'38');
    $datos[] = array('key' =>'KATARINA', 'id' =>'55');
    $datos[] = array('key' =>'KAYLE', 'id' =>'10');
    $datos[] = array('key' =>'KENNEN', 'id' =>'85');
    $datos[] = array('key' =>'KHAZIX', 'id' =>'121');
    $datos[] = array('key' =>'KINDRED', 'id' =>'203');
    $datos[] = array('key' =>'KOGMAW', 'id' =>'96');
    $datos[] = array('key' =>'LEBLANC', 'id' =>'7');
    $datos[] = array('key' =>'LEESIN', 'id' =>'64');
    $datos[] = array('key' =>'LEONA', 'id' =>'89');
    $datos[] = array('key' =>'LISSANDRA', 'id' =>'127');
    $datos[] = array('key' =>'LUCIAN', 'id' =>'236');
    $datos[] = array('key' =>'LULU', 'id' =>'117');
    $datos[] = array('key' =>'LUX', 'id' =>'99');
    $datos[] = array('key' =>'MALPHITE', 'id' =>'54');
    $datos[] = array('key' =>'MALZAHAR', 'id' =>'90');
    $datos[] = array('key' =>'MAOKAI', 'id' =>'57');
    $datos[] = array('key' =>'MASTERYI', 'id' =>'11');
    $datos[] = array('key' =>'MISSFORTUNE', 'id' =>'21');
    $datos[] = array('key' =>'MONKEYKING', 'id' =>'62');
    $datos[] = array('key' =>'MORDEKAISER', 'id' =>'82');
    $datos[] = array('key' =>'MORGANA', 'id' =>'25');
    $datos[] = array('key' =>'NAMI', 'id' =>'267');
    $datos[] = array('key' =>'NASUS', 'id' =>'75');
    $datos[] = array('key' =>'NAUTILUS', 'id' =>'111');
    $datos[] = array('key' =>'NIDALEE', 'id' =>'76');
    $datos[] = array('key' =>'NOCTURNE', 'id' =>'56');
    $datos[] = array('key' =>'NUNU', 'id' =>'20');
    $datos[] = array('key' =>'OLAF', 'id' =>'2');
    $datos[] = array('key' =>'ORIANNA', 'id' =>'61');
    $datos[] = array('key' =>'PANTHEON', 'id' =>'80');
    $datos[] = array('key' =>'POPPY', 'id' =>'78');
    $datos[] = array('key' =>'QUINN', 'id' =>'133');
    $datos[] = array('key' =>'RAMMUS', 'id' =>'33');
    $datos[] = array('key' =>'REKSAI', 'id' =>'421');
    $datos[] = array('key' =>'RENEKTON', 'id' =>'58');
    $datos[] = array('key' =>'RENGAR', 'id' =>'107');
    $datos[] = array('key' =>'RIVEN', 'id' =>'92');
    $datos[] = array('key' =>'RUMBLE', 'id' =>'68');
    $datos[] = array('key' =>'RYZE', 'id' =>'13');
    $datos[] = array('key' =>'SEJUANI', 'id' =>'113');
    $datos[] = array('key' =>'SHACO', 'id' =>'35');
    $datos[] = array('key' =>'SHEN', 'id' =>'98');
    $datos[] = array('key' =>'SHYVANA', 'id' =>'102');
    $datos[] = array('key' =>'SINGED', 'id' =>'27');
    $datos[] = array('key' =>'SION', 'id' =>'14');
    $datos[] = array('key' =>'SIVIR', 'id' =>'15');
    $datos[] = array('key' =>'SKARNER', 'id' =>'72');
    $datos[] = array('key' =>'SONA', 'id' =>'37');
    $datos[] = array('key' =>'SORAKA', 'id' =>'16');
    $datos[] = array('key' =>'SWAIN', 'id' =>'50');
    $datos[] = array('key' =>'SYNDRA', 'id' =>'134');
    $datos[] = array('key' =>'TAHMKENCH', 'id' =>'223');
    $datos[] = array('key' =>'TALON', 'id' =>'91');
    $datos[] = array('key' =>'TARIC', 'id' =>'44');
    $datos[] = array('key' =>'TEEMO', 'id' =>'17');
    $datos[] = array('key' =>'THRESH', 'id' =>'412');
    $datos[] = array('key' =>'TRISTANA', 'id' =>'18');
    $datos[] = array('key' =>'TRUNDLE', 'id' =>'48');
    $datos[] = array('key' =>'TRYNDAMERE', 'id' =>'23');
    $datos[] = array('key' =>'TWISTEDFATE', 'id' =>'4');
    $datos[] = array('key' =>'TWITCH', 'id' =>'29');
    $datos[] = array('key' =>'UDYR', 'id' =>'77');
    $datos[] = array('key' =>'URGOT', 'id' =>'6');
    $datos[] = array('key' =>'VARUS', 'id' =>'110');
    $datos[] = array('key' =>'VAYNE', 'id' =>'67');
    $datos[] = array('key' =>'VEIGAR', 'id' =>'45');
    $datos[] = array('key' =>'VELKOZ', 'id' =>'161');
    $datos[] = array('key' =>'VI', 'id' =>'254');
    $datos[] = array('key' =>'VIKTOR', 'id' =>'112');
    $datos[] = array('key' =>'VLADIMIR', 'id' =>'8');
    $datos[] = array('key' =>'VOLIBEAR', 'id' =>'106');
    $datos[] = array('key' =>'WARWICK', 'id' =>'19');
    $datos[] = array('key' =>'XERATH', 'id' =>'101');
    $datos[] = array('key' =>'XINZHAO', 'id' =>'5');
    $datos[] = array('key' =>'YASUO', 'id' =>'157');
    $datos[] = array('key' =>'YORICK', 'id' =>'83');
    $datos[] = array('key' =>'ZAC', 'id' =>'154');
    $datos[] = array('key' =>'ZED', 'id' =>'238');
    $datos[] = array('key' =>'ZIGGS', 'id' =>'115');
    $datos[] = array('key' =>'ZILEAN', 'id' =>'26');
    $datos[] = array('key' =>'ZYRA', 'id' =>'143');


    return $datos;
  }


}
?>