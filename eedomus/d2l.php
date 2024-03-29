<?php
/*error_reporting(E_ALL);
ini_set("display_errors", 1);

require("config.php");
require("eedomus.lib.php"); //eedomus emulation lib
*/
/*
script cree par twitter:@Havok pour la eedomus
*/

class sdk_D2l {

  public $version = '2.1';
  public $error = null;
  public $typeContrat = null;

  //authentication:
  private $_login;
  private $_password;
  private $_isAuth = false;

  private $_APILoginUrl = 'https://d2lapi.sicame.io/api';
  private $_APIHost = 'sicame.io';
  //en mn la plage horaire entre 2 mesures
  private $_plage = 1;

  private $_numModule;
  private $_idModule;
  private $_APIKey;
  private $_APIExpirationDate;

  private function sdk__auth()
  {
    /* Connexion */

    $infoCurl = null; //pour r�cup�rer les info curl

    $postfields = '{"login":"'.$this->_login.'","password":"'.$this->_password.'"}';
    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json';

    $return = httpQuery($this->_APILoginUrl.'/D2L/Security/GetAPIKey','POST',$postfields,NULL,$headers,false,false,$infoCurl);
    if ($infoCurl['http_code'] != 200) {
      $this->error = "Return code is $infoCurl[http_code]\n";
      return false;
    }
    $params = sdk_json_decode($return);

    $this->_APIKey = $params['apiKey'];
    $this->_APIExpirationDate = $params['validTo'];
    //$this->_networkId = key($params['networks']);

    $this->_isAuth = true;
    $this->error = "";
    return true;
  }

  private function sdk__getD2lIds()
  {
    //Provide a list of all D2L modules accessible for one client (authentication by APIKey)
   // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/

   $infoCurl = null; //pour r�cup�rer les info curl

   $headers = array();
   $headers[] = 'Accept: application/json';
   $headers[] = 'APIKey: '.$this->_APIKey;

   $return = httpQuery($this->_APILoginUrl.'/D2L/D2Ls','GET',NULL,NULL,$headers,false,false,$infoCurl);
   if ($infoCurl['http_code'] != 200) {
     $this->error = "Return code is $infoCurl[http_code]\n";
     return false;
   }
   $params = sdk_json_decode($return);

   $this->_idModule = $params[$this->_numModule]["idModule"];
   $this->error = "";
   return true;

  }

  private function sdk__getLastIndexes()
  {
    //Get last indexes retreived by a specific D2L

    $infoCurl = null; //pour r�cup�rer les info curl

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'APIKey: '.$this->_APIKey;

    $return = httpQuery($this->_APILoginUrl.'/D2L/D2Ls/'.$this->_idModule.'/LastIndexes','GET',NULL,NULL,$headers,false,false,$infoCurl);
    if ($infoCurl['http_code'] != 200) {
      $this->error = "Return code is $infoCurl[http_code]\n";
      return false;
    }
    $params = sdk_json_decode($return);

    $this->error = "";
    return $params;

  }

  private function sdk__getLastCurrents()
  {
    //Get last currents retreived by a specific D2L

    $infoCurl = null; //pour récupérer les info curl

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'APIKey: '.$this->_APIKey;

    $return = httpQuery($this->_APILoginUrl.'/D2L/D2Ls/'.$this->_idModule.'/LastCurrents','GET',NULL,NULL,$headers,false,false,$infoCurl);
    if ($infoCurl['http_code'] != 200) {
      $this->error = "Return code is $infoCurl[http_code]\n";
      return false;
    }
    $params = sdk_json_decode($return);

    $this->error = "";
    return $params;

  }

  private function sdk__getIndexesBetween($dateFrom, $dateTo)
  {
    //Get indexes retreived by a specific D2L between two dates
    $infoCurl = null; //pour r�cup�rer les info curl

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'APIKey: '.$this->_APIKey;

    $return = httpQuery($this->_APILoginUrl.'/D2L/D2Ls/'.$this->_idModule.'/IndexesBetween?from='.$dateFrom.'&to='.$dateTo,'GET',NULL,NULL,$headers,false,false,$infoCurl);
    if ($infoCurl['http_code'] != 200) {
      $this->error = "Return code is $infoCurl[http_code]\n";
      return false;
    }
    $params = sdk_json_decode($return);

    $this->error = "";
    return $params;

  }

  function sdk__getTypeContrat()
  {
    //Get the subscribed contract type (based on last index)

    $infoCurl = null; //pour r�cup�rer les info curl

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'APIKey: '.$this->_APIKey;

    $return = httpQuery($this->_APILoginUrl.'/D2L/D2Ls/'.$this->_idModule.'/TypeContrat','GET',NULL,NULL,$headers,false,false,$infoCurl);
    if ($infoCurl['http_code'] != 200) {
      $this->error = "Return code is $infoCurl[http_code]\n";
      return false;
    }
    $params = sdk_json_decode($return);

    $this->error = "";
    return $params;

  }
  function sdk_getInitialData()
  {
    //Get the original d2l data)
    $initialData = $this->sdk__getLastIndexes();
    return $initialData;
  }

  function sdk_getIndexes($AtDate = NULL)
  //Get index
  {
    if (!$AtDate)
    {
      $LastIndexes = $this->sdk__getLastIndexes();
    } else {
      $d0 = mktime(23,59,0,substr($AtDate,5,2),substr($AtDate,8,2),substr($AtDate,0,4));
      $d1 = mktime(0,0,0,substr($AtDate,5,2),substr($AtDate,8,2)+1,substr($AtDate,0,4));
      //echo date('Y-m-d\TH:i:00',$d0);
      //echo date('Y-m-d\TH:i:00',$d1);

      $ListIndexes = $this->sdk__getIndexesBetween(date('Y-m-d\TH:i:00',$d0), date('Y-m-d\TH:i:00',$d1));
      $LastIndexes = $ListIndexes[0];
    }
    if ($this->error  || count($LastIndexes)==0)
    {
      return $this->error;
    } else {
      switch ($this->typeContrat) {

        case "BASE":
         $indexesTotal['total'] = $LastIndexes['baseHchcEjphnBbrhcjb']/1000;
         $indexesTotal['base'] = $LastIndexes['baseHchcEjphnBbrhcjb']/1000;
         break;

        case "HEURE_CREUSE_HEURE_PLEINE":
         $indexesTotal['total'] = ($LastIndexes['baseHchcEjphnBbrhcjb']+$LastIndexes['hchpEjphpmBbrhpjb'])/1000;
         $indexesTotal['HC'] = $LastIndexes['baseHchcEjphnBbrhcjb']/1000;
         $indexesTotal['HP'] = $LastIndexes['hchpEjphpmBbrhpjb']/1000;
         break;

        case "TEMPO":
         $indexesTotal['total'] = ($LastIndexes['baseHchcEjphnBbrhcjb']+$LastIndexes['hchpEjphpmBbrhpjb']+$LastIndexes['bbrhcjw']+$LastIndexes['bbrhpjw']+$LastIndexes['bbrhcjr']+$LastIndexes['bbrhpjr'])/1000;
         $indexesTotal['HCJB'] = $LastIndexes['baseHchcEjphnBbrhcjb']/1000;
         $indexesTotal['HPJB'] = $LastIndexes['hchpEjphpmBbrhpjb']/1000;
         $indexesTotal['HCJW'] = $LastIndexes['bbrhcjw']/1000;
         $indexesTotal['HPJW'] = $LastIndexes['bbrhpjw']/1000;
         $indexesTotal['HCJR'] = $LastIndexes['bbrhcjr']/1000;
         $indexesTotal['HPJR'] = $LastIndexes['bbrhpjr']/1000;
         break;

        case "EJP":
         $indexesTotal['total'] = ($LastIndexes['baseHchcEjphnBbrhcjb']+$LastIndexes['hchpEjphpmBbrhpjb'])/1000;
         $indexesTotal['HN'] = $LastIndexes['baseHchcEjphnBbrhcjb']/1000;
         $indexesTotal['HP'] = $LastIndexes['hchpEjphpmBbrhpjb']/1000;
         break;
        default:
         $indexesTotal = 0;
      }
      $this->error = "";
      return $indexesTotal;
    }
  }

  function sdk_getPowerUsedLast($lastperiod = 'HOUR')
  {
    //get the power used in kWh for last MIN,HOUR,DAY,WEEK,MONTH,YEAR
    $d0 = $d0a = $d1 = $d1a = 0;
    //pour ne pas surcharger le serveur on demande la consommation au d�but et � la fin de la p�riode (et pas pour toute la p�riode)
    switch ($lastperiod) {
      case 'MIN':
       $d0 = strtotime("-1 minutes");
       $d0a = time();
       $d1 = time();
       break;
      case 'HOUR':
       $d0 = strtotime("-1 hours");
       $d0a = strtotime("-1 hours + ".$this->_plage." minutes");
       $d1 = time();
       break;
      case 'DAY':
       $d0 = strtotime("-1 days");
       $d0a = strtotime("-1 days + ".$this->_plage." minutes");
       $d1 = time();
       break;
       case 'DAY-1':
        $d0 = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
        $d0a = mktime(0, $this->_plage, 0, date("m")  , date("d")-1, date("Y"));
        $d1 = mktime(23, 60-$this->_plage, 0, date("m")  , date("d")-1, date("Y"));
        $d1a = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
        break;
       case 'THISDAY':
        $d0 = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
        $d0a = mktime(0, $this->_plage, 0, date("m")  , date("d"), date("Y"));
        $d1 = time();
        break;
       case 'WEEK':
        $d0 = strtotime("-1 week");
        $d0a = strtotime("-1 week + ".$this->_plage." minutes");
        $d1 = time();
        break;
       case 'MONTH':
         $d0 = strtotime("-1 months");
         $d0a = strtotime("-1 months + ".$this->_plage." minutes");
         $d1 = time();
         break;
       case 'MONTH-1':
         $d0 = mktime(0, 0, 0, date("m")-1  , 1, date("Y"));
         $d0a = mktime(0, $this->_plage, 0, date("m")-1  , 1, date("Y"));
         $d1a = mktime(0, 0, 0, date("m")  , 1, date("Y"));
         $d1 = strtotime("-".$this->_plage." minutes", $d1a);
          break;
       case 'THISMONTH':
         $d0 = mktime(0, 0, 0, date("m")  , 1, date("Y"));
         $d0a = mktime(0, $this->_plage, 0, date("m")  , 1, date("Y"));
         $d1 = time();
         break;
       case 'YEAR':
         $d0 = strtotime("-1 years");
         $d0a = strtotime("-1 years + ".$this->_plage." minutes");
         $d1 = time();
         break;

      default:
       $d0 = strtotime("-1 hours");
       $d0a = strtotime("-1 hours + ".$this->_plage." minutes");
       $d1 = time();
       break;
    }
    // echo date('Y-m-d\TH:i:00',$d0)."</br>\r\n";
    // echo date('Y-m-d\TH:i:00',$d0a)."</br>\r\n";
    // echo date('Y-m-d\TH:i:00',$d1)."</br>\r\n";
    // echo date('Y-m-d\TH:i:00',$d1a)."</br>\r\n";

    $ListIndexesStart = $this->sdk__getIndexesBetween(date('Y-m-d\TH:i:00',$d0), date('Y-m-d\TH:i:00',$d0a));
    $ListIndexesStart = $ListIndexesStart[0];
    if ($d1a == 0) {
      $ListIndexesEnd = $this->sdk__getLastIndexes();
    } else {
      $ListIndexesEnd = $this->sdk__getIndexesBetween(date('Y-m-d\TH:i:00',$d1), date('Y-m-d\TH:i:00',$d1a));
      $ListIndexesEnd = $ListIndexesEnd[0];
    }

    if ($this->error || count($ListIndexesStart) == 0 || count($ListIndexesEnd) == 0)
    {
      return $this->error;
    } else {

      //print_r($ListIndexesStart);
      //print_r($ListIndexesEnd);
      switch ($this->typeContrat) {

        case "BASE":
         $powerUsed['total'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         $powerUsed['base'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         break;

        case "HEURE_CREUSE_HEURE_PLEINE":
         $powerUsed['total'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb']+$ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         $powerUsed['HC'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         $powerUsed['HP'] = ($ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         break;

        case "TEMPO":
         $powerUsed['total'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb']+$ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb']+$ListIndexesEnd['bbrhcjw']-$ListIndexesStart['bbrhcjw']+$ListIndexesEnd['bbrhpjw']-$ListIndexesStart['bbrhpjw']+$ListIndexesEnd['bbrhcjr']-$ListIndexesStart['bbrhcjr']+$ListIndexesEnd['bbrhpjr']-$ListIndexesStart['bbrhpjr'])/1000;
         $powerUsed['HCJB'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         $powerUsed['HPJB'] = ($ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         $powerUsed['HCJW'] = ($ListIndexesEnd['bbrhcjw']-$ListIndexesStart['bbrhcjw'])/1000;
         $powerUsed['HPJW'] = ($ListIndexesEnd['bbrhpjw']-$ListIndexesStart['bbrhpjw'])/1000;
         $powerUsed['HCJR'] = ($ListIndexesEnd['bbrhcjr']-$ListIndexesStart['bbrhcjr'])/1000;
         $powerUsed['HPJR'] = ($ListIndexesEnd['bbrhpjr']-$ListIndexesStart['bbrhpjr'])/1000;
         break;

        case "EJP":
         $powerUsed['total'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb']+$ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         $powerUsed['HN'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         $powerUsed['HP'] = ($ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         break;
        default:
         $powerUsed = 0;
      }
      $this->error = "";
      return $powerUsed;
    }

  }

  function sdk_getPowerUsedBeetween($dateFrom = NULL, $dateTo = NULL)
  {
   //get the power used in kWh from date to date
    if ($dateFrom == NULL) {
      $dateFrom = date('Y-m-d\TH:i:s',mktime(date("H"), date("i"), date("s"), date("m"), date("d")-1, date("Y")));
    }
    if ($dateTo == NULL) {
      $dateTo = date('Y-m-d\TH:i:s');
    }

    $d0 = strtotime($dateFrom." -".$this->_plage." minutes");
    $d0a = strtotime($dateFrom);
    $d1 = strtotime($dateTo." -".$this->_plage." minutes");
    $d1a = strtotime($dateTo);

    // echo date('Y-m-d\TH:i:s',$d0)." ";
    // echo date('Y-m-d\TH:i:s',$d0a)." ";
    // echo date('Y-m-d\TH:i:s',$d1)." ";
    // echo date('Y-m-d\TH:i:s',$d1a);

    $ListIndexesStart = $this->sdk__getIndexesBetween(date('Y-m-d\TH:i:00',$d0), date('Y-m-d\TH:i:00',$d0a));
    $ListIndexesStart = $ListIndexesStart[0];
    $ListIndexesEnd = $this->sdk__getIndexesBetween(date('Y-m-d\TH:i:00',$d1), date('Y-m-d\TH:i:00',$d1a));
    $ListIndexesEnd = $ListIndexesEnd[0];

    if ($this->error || count($ListIndexesStart) == 0 || count($ListIndexesEnd) == 0)
    {
      return $this->error;
    } else {

      // print_r($ListIndexesStart);
      // print_r($ListIndexesEnd);
      switch ($this->typeContrat) {

        case "BASE":
         $powerUsed['total'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         $powerUsed['base'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         break;

        case "HEURE_CREUSE_HEURE_PLEINE":
         $powerUsed['total'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb']+$ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         $powerUsed['HC'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         $powerUsed['HP'] = ($ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         break;

        case "TEMPO":
         $powerUsed['total'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb']+$ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb']+$ListIndexesEnd['bbrhcjw']-$ListIndexesStart['bbrhcjw']+$ListIndexesEnd['bbrhpjw']-$ListIndexesStart['bbrhpjw']+$ListIndexesEnd['bbrhcjr']-$ListIndexesStart['bbrhcjr']+$ListIndexesEnd['bbrhpjr']-$ListIndexesStart['bbrhpjr'])/1000;
         $powerUsed['HCJB'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         $powerUsed['HPJB'] = ($ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         $powerUsed['HCJW'] = ($ListIndexesEnd['bbrhcjw']-$ListIndexesStart['bbrhcjw'])/1000;
         $powerUsed['HPJW'] = ($ListIndexesEnd['bbrhpjw']-$ListIndexesStart['bbrhpjw'])/1000;
         $powerUsed['HCJR'] = ($ListIndexesEnd['bbrhcjr']-$ListIndexesStart['bbrhcjr'])/1000;
         $powerUsed['HPJR'] = ($ListIndexesEnd['bbrhpjr']-$ListIndexesStart['bbrhpjr'])/1000;
         break;

        case "EJP":
         $powerUsed['total'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb']+$ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         $powerUsed['HN'] = ($ListIndexesEnd['baseHchcEjphnBbrhcjb']-$ListIndexesStart['baseHchcEjphnBbrhcjb'])/1000;
         $powerUsed['HP'] = ($ListIndexesEnd['hchpEjphpmBbrhpjb']-$ListIndexesStart['hchpEjphpmBbrhpjb'])/1000;
         break;
        default:
         $powerUsed = 0;
      }
      $this->error = "";
      return $powerUsed;
    }

  }

  function sdk_getCurrentIntensity()
  //Get current intensity
  {
    $LastCurrents = $this->sdk__getLastCurrents();
    if ($this->error  || count($LastCurrents) == 0)
    {
      return $this->error;
    } else {
      $CurrentIntensity['total'] = $LastCurrents['iinst1']+$LastCurrents['iinst2']+$LastCurrents['iinst3'];
      $CurrentIntensity['i1'] = $LastCurrents['iinst1'];
      $CurrentIntensity['i2'] = $LastCurrents['iinst2'];
      $CurrentIntensity['i3'] = $LastCurrents['iinst3'];
      return $CurrentIntensity;
    }
  }


  /*function sdk_D2l($login, $password, $numModule = 1)*/
  function __construct($login, $password, $numModule = 1)
  {
      $this->_login = $login;
      $this->_password = $password;
      $this->_numModule = $numModule-1;

      if ($this->sdk__auth())
       {
         $this->sdk__getD2lIds();
         $this->typeContrat = $this->sdk__getTypeContrat();
       }
  }

}

/********* MAIN PROGRAM *********/

//-------------- Parametres
$typeOrdre = getArg('type');
$d2lUsername = getArg('user');
$d2lPassword = getArg('pass');
$d2lNumber = getArg('number');
$d2lfromdate = getArg('fromdate',false,date('d-m-Y'));
$d2ltodate = getArg('todate',false,date('d-m-Y'));

//$D2l = new sdk_D2l($loginD2lReel, $passwordD2lReel);
$D2l = new sdk_D2l($d2lUsername, $d2lPassword, $d2lNumber);
if ($D2l->error)
{
  echo $D2l->error;
} else {
  switch ($typeOrdre) {
    case 'lastindex':
      $IndexActuel = $D2l->sdk_getIndexes();
      $eestatus = "<root><indexes>";

      foreach($IndexActuel as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</indexes></root>";
      break;

    case 'currentintensity':
      $CurrentIntensity = $D2l->sdk_getCurrentIntensity();
      $eestatus = "<root><intensity>";

      foreach($CurrentIntensity as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }

      $eestatus .= "</intensity></root>";
      break;

    case 'powerlasthour':
      $PowerUsedLastHour = $D2l->sdk_getPowerUsedLast('HOUR');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastHour as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerlastday':
      $PowerUsedLastDay = $D2l->sdk_getPowerUsedLast('DAY');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastDay as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerlastday-1':
      $PowerUsedLastDay = $D2l->sdk_getPowerUsedLast('DAY-1');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastDay as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerthisday':
      $PowerUsedLastDay = $D2l->sdk_getPowerUsedLast('THISDAY');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastDay as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerlastmonth':
      $PowerUsedLastMonth = $D2l->sdk_getPowerUsedLast('MONTH');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastMonth as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerlastmonth-1':
      $PowerUsedLastMonth = $D2l->sdk_getPowerUsedLast('MONTH-1');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastMonth as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerthismonth':
      $PowerUsedLastMonth = $D2l->sdk_getPowerUsedLast('THISMONTH');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastMonth as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

   case 'powerlastyear':
     $PowerUsedLastYear = $D2l->sdk_getPowerUsedLast('YEAR');
     $eestatus = "<root><power>";

     foreach($PowerUsedLastYear as $cle => $value)
     {
       $eestatus .= "<".$cle.">".$value."</".$cle.">";
     }
     $eestatus .= "</power></root>";
     break;

   case 'initialdata':
     $initialData = $D2l->sdk_getInitialData();
     $eestatus = "<root>";

     foreach($initialData as $cle => $value)
     {
       $eestatus .= "<".$cle.">".$value."</".$cle.">";
     }
     $eestatus .= "</root>";
     break;

   case 'powerfromto':
     $PowerFromTo = $D2l->sdk_getPowerUsedBeetween($d2lfromdate,$d2ltodate);
     $eestatus = "<root><power>";

     foreach($PowerFromTo as $cle => $value)
     {
       $eestatus .= "<".$cle.">".$value."</".$cle.">";
     }
     $eestatus .= "</power></root>";
     break;



    default:
      // code...
      break;
  }
  echo $eestatus;
}


?>
