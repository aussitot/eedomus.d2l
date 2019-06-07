<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
   * phpD2lAPI
   *
   * class php pour D2l (ERL WiFi pour compteur Linky)
   * API Documentation : https://consospyapi.sicame.io/swagger/index.html
   *
   * @author twitter:@havok
   * @version 2019.06.07
   *
   */

   class D2l {

     public $version = '0.1';
     public $error = null;
     public $typeContrat = null;

     //authentication:
     private $_login;
     private $_password;
     private $_isAuth = false;

     private $_APILoginUrl = 'https://consospyapi.sicame.io/api';
     private $_APIHost = 'sicame.io';

     private $_numModule;
     private $_idModule;
     private $_APIKey;
     private $_APIExpirationDate;

     private function _auth()
     {
       /* Connexion */

       $infoCurl = null; //pour récupérer les info curl

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

       /*** Intial curl
       // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
       $ch = curl_init();

       curl_setopt($ch, CURLOPT_URL, $this->_APILoginUrl.'/D2L/Security/GetAPIKey');
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"login\": \"$this->_login\", \"password\": \"$this->_password\"}");
       curl_setopt($ch, CURLOPT_POST, 1);

       $headers = array();
       $headers[] = 'Accept: application/json';
       $headers[] = 'Content-Type: application/json';
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

       $result = curl_exec($ch);
       $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

       if ($httpCode != 200) {
         $this->error = "Return code is {$httpCode} \n".curl_error($ch);
         return false;
       }
       curl_close ($ch);

       $params = null;
       $params = json_decode($result, true);
       ****/

       $this->_APIKey = $params['apiKey'];
       $this->_APIExpirationDate = $params['validTo'];
       //$this->_networkId = key($params['networks']);

       $this->_isAuth = true;
       $this->error = "";
       return true;
     }

     private function _getD2lIds()
     {
       //Provide a list of all D2L modules accessible for one client (authentication by APIKey)
      // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/

      $infoCurl = null; //pour récupérer les info curl

      $headers = array();
      $headers[] = 'Accept: application/json';
      $headers[] = 'APIKey: '.$this->_APIKey;

      $return = httpQuery($this->_APILoginUrl.'/D2L/D2Ls','GET',NULL,NULL,$headers,false,false,$infoCurl);
      if ($infoCurl['http_code'] != 200) {
        $this->error = "Return code is $infoCurl[http_code]\n";
        return false;
      }
      $params = sdk_json_decode($return);

      /******** initial curl
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $this->_APILoginUrl.'/D2L/D2Ls');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


      $headers = array();
      $headers[] = 'Accept: application/json';
      $headers[] = 'APIKey: '.$this->_APIKey;

      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      if ($httpCode != 200) {
        $this->error = "Return code is {$httpCode} \n".curl_error($ch);
        return false;
      }
      curl_close ($ch);

      $params = null;
      $params = json_decode($result, true);
      */

      $this->_idModule = $params[$this->_numModule]["idModule"];
      $this->error = "";
      return true;

     }

     private function _getLastIndexes()
     {
       //Get last indexes retreived by a specific D2L

       $infoCurl = null; //pour récupérer les info curl

       $headers = array();
       $headers[] = 'Accept: application/json';
       $headers[] = 'APIKey: '.$this->_APIKey;

       $return = httpQuery($this->_APILoginUrl.'/D2L/D2Ls/'.$this->_idModule.'/LastIndexes','GET',NULL,NULL,$headers,false,false,$infoCurl);
       if ($infoCurl['http_code'] != 200) {
         $this->error = "Return code is $infoCurl[http_code]\n";
         return false;
       }
       $params = sdk_json_decode($return);

       /******** initial curl
       $ch = curl_init();

       curl_setopt($ch, CURLOPT_URL, $this->_APILoginUrl.'/D2L/D2Ls/'.$this->_idModule.'/LastIndexes');
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

       $headers = array();
       $headers[] = 'Accept: application/json';
       $headers[] = 'APIKey: '.$this->_APIKey;

       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

       $result = curl_exec($ch);
       $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

       if ($httpCode != 200) {
         $this->error = "Return code is {$httpCode} \n".curl_error($ch);
         return false;
       }
       curl_close ($ch);

       $params = null;
      $params = json_decode($result, true);
      **********/
      $this->error = "";
       return $params;

     }

     private function _getIndexesBetween($dateFrom, $dateTo)
     {
       //Get indexes retreived by a specific D2L between two dates
       $infoCurl = null; //pour récupérer les info curl

       $headers = array();
       $headers[] = 'Accept: application/json';
       $headers[] = 'APIKey: '.$this->_APIKey;

       $return = httpQuery($this->_APILoginUrl.'/D2L/D2Ls/'.$this->_idModule.'/IndexesBetween?from='.$dateFrom.'&to='.$dateTo,'GET',NULL,NULL,$headers,false,false,$infoCurl);
       if ($infoCurl['http_code'] != 200) {
         $this->error = "Return code is $infoCurl[http_code]\n";
         return false;
       }
       $params = sdk_json_decode($return);


       /******** initial curl
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $this->_APILoginUrl.'/D2L/D2Ls/'.$this->_idModule.'/IndexesBetween?from='.$dateFrom.'&to='.$dateTo);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

       $headers = array();
       $headers[] = 'Accept: application/json';
       $headers[] = 'APIKey: '.$this->_APIKey;

       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

       $result = curl_exec($ch);
       $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

       if ($httpCode != 200) {
         $this->error = "Return code is {$httpCode} \n".curl_error($ch);
         return false;
       }
       curl_close ($ch);

       $params = null;
       $params = json_decode($result, true);
       */
       $this->error = "";
       return $params;

     }

     function getTypeContrat()
     {
       //Get the subscribed contract type (based on last index)

       $infoCurl = null; //pour récupérer les info curl

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

     function getIndexes($AtDate = null)
     //Get index
     {
       if (!$AtDate)
       {
         $LastIndexes = $this->_getLastIndexes();
       } else {
         $d0 = mktime(23,59,0,substr($AtDate,5,2),substr($AtDate,8,2),substr($AtDate,0,4));
         $d1 = mktime(0,0,0,substr($AtDate,5,2),substr($AtDate,8,2)+1,substr($AtDate,0,4));
         //echo date('Y-m-d\TH:i:00',$d0);
         //echo date('Y-m-d\TH:i:00',$d1);

         $ListIndexes = $this->_getIndexesBetween(date('Y-m-d\TH:i:00',$d0), date('Y-m-d\TH:i:00',$d1));
         $LastIndexes = $ListIndexes[0];
       }
       if ($this->error)
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

     function getPowerUsedLast($lastperiod = 'HOUR')
     {
       //get the power used in kWh for last MIN,HOUR,DAY,WEEK,MONTH,YEAR

       //pour ne pas surcharger le serveur on demande la consommation au début et à la fin de la période (et pas pour toute la période)
       switch ($lastperiod) {
         case 'MIN':
          $d0 = strtotime("-1 minutes");
          $d0a = time();
          $d1 = time();
          break;
         case 'HOUR':
          $d0 = strtotime("-1 hours");
          $d0a = strtotime("-1 hours + 1 minutes");
          $d1 = time();
          break;
         case 'DAY':
          $d0 = strtotime("-1 days");
          $d0a = strtotime("-1 days + 1 minutes");
          $d1 = time();
          break;
          case 'WEEK':
           $d0 = strtotime("-1 week");
           $d0a = strtotime("-1 week + 1 minutes");
           $d1 = time();
           //          echo date('Y-m-d\TH:i:00',$d0);
           //          echo date('Y-m-d\TH:i:00',$d1);
           break;
          case 'MONTH':
            $d0 = strtotime("-1 months");
            $d0a = strtotime("-1 months + 1 minutes");
            $d1 = time();
            break;
          case 'YEAR':
            $d0 = strtotime("-1 years");
            $d0a = strtotime("-1 years + 1 minutes");
            $d1 = time();
            break;

         default:
          $d0 = strtotime("-1 hours");
          $d0a = strtotime("-1 hours + 1 minutes");
          $d1 = time();
          break;
       }
       //          echo date('Y-m-d\TH:i:00',$d0);
       //          echo date('Y-m-d\TH:i:00',$d1);

       $ListIndexesStart = $this->_getIndexesBetween(date('Y-m-d\TH:i:00',$d0), date('Y-m-d\TH:i:00',$d0a));
       $ListIndexesStart = $ListIndexesStart[0];
       $ListIndexesEnd = $this->_getLastIndexes();

       if ($this->error)
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

     function getPowerUsedBeetween($dateFrom, $dateTo)
     {
       //get the power used in kWh from date to date
     }

     function getCurrentIntensity()
     //Get current intensity
     {
       $LastIndexes = $this->_getLastIndexes();
       if ($this->error)
       {
         return $this->error;
       } else {
         return $LastIndexes['iinst1']+$LastIndexes['iinst2']+$LastIndexes['iinst3'];
       }
     }


     function __construct($login, $password, $numModule = 1)
     {
         $this->_login = $login;
         $this->_password = $password;
         $this->_numModule = $numModule-1;

         if ($this->_auth())
          {
            $this->_getD2lIds();
            $this->typeContrat = $this->getTypeContrat();
          }
     }

   }
?>
