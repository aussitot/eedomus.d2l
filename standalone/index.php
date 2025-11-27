<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require("config.php"); //remplacez cette ligne par vos login/password Consospy ($loginD2lReel="xxxx"; $passwordD2lReel="yyyyy";)
require("eedomus.lib.php"); //eedomus emulation lib
require("phpD2lAPI.php"); //php class API for D2l

$typeOrdre = getArg('type');
if ($typeOrdre == "") { $typeOrdre = "powerlastyear"; }

$D2l = new D2l($loginD2lReel, $passwordD2lReel);
if ($D2l->error)
{
  echo $D2l->error;
} else {
  switch ($typeOrdre) {
    case 'lastindex':
      $IndexActuel = $D2l->getIndexes();
      $eestatus = "<root><indexes>";

      foreach($IndexActuel as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</indexes></root>";
      break;

    case 'currentintensity':
      $CurrentIntensity = $D2l->getCurrentIntensity();
      $eestatus = "<root><intensity>";

      foreach($CurrentIntensity as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }

      $eestatus .= "</intensity></root>";
      break;

    case 'powerlasthour':
      $PowerUsedLastHour = $D2l->getPowerUsedLast('HOUR');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastHour as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerlastday':
      $PowerUsedLastDay = $D2l->getPowerUsedLast('DAY');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastDay as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerlastday-1':
      $PowerUsedLastDay = $D2l->getPowerUsedLast('DAY-1');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastDay as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerthisday':
      $PowerUsedLastDay = $D2l->getPowerUsedLast('THISDAY');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastDay as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerlastmonth':
      $PowerUsedLastMonth = $D2l->getPowerUsedLast('MONTH');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastMonth as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerlastmonth-1':
      $PowerUsedLastMonth = $D2l->getPowerUsedLast('MONTH-1');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastMonth as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

    case 'powerthismonth':
      $PowerUsedLastMonth = $D2l->getPowerUsedLast('THISMONTH');
      $eestatus = "<root><power>";

      foreach($PowerUsedLastMonth as $cle => $value)
      {
        $eestatus .= "<".$cle.">".$value."</".$cle.">";
      }
      $eestatus .= "</power></root>";
      break;

   case 'powerlastyear':
     $PowerUsedLastYear = $D2l->getPowerUsedLast('YEAR');
     $eestatus = "<root><power>";

     foreach($PowerUsedLastYear as $cle => $value)
     {
       $eestatus .= "<".$cle.">".$value."</".$cle.">";
     }
     $eestatus .= "</power></root>";
     break;

   case 'initialdata':
     $initialData = $D2l->getInitialData();
     $eestatus = "<root>";

     foreach($initialData as $cle => $value)
     {
       $eestatus .= "<".$cle.">".$value."</".$cle.">";
     }
     $eestatus .= "</root>";
     break;

   case 'powerfromto':
     $PowerFromTo = $D2l->getPowerUsedBeetween($d2lfromdate,$d2ltodate);
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
