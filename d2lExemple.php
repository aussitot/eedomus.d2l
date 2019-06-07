<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require("config.php");
require("eedomus.lib.php"); //eedomus emulation lib
require("phpD2lAPI.php"); //php class API for D2l


$D2l = new D2l($loginD2lReel, $passwordD2lReel);
if ($D2l->error)
{
  echo $D2l->error;
} else {

  // $LastIndexes = $D2l->getLastIndexes();
  // if ($D2l->error)
  // {
  //   echo $D2l->error;
  // } else {
  //   print_r($LastIndexes);
  // }

  // $IndexesBetween = $D2l->getIndexesBetween('2019-06-05T23:57:00','2019-06-05T23:58:00');
  // if ($D2l->error)
  // {
  //   echo $D2l->error;
  // } else {
  //   print_r($IndexesBetween);
  // }

  echo "<p>Index actuel : ".$D2l->getIndexesTotal()." kWh</p>\r\n";
  echo "<p>Intensité instantanée : ".$D2l->getCurrentIntensity()." A</p>\r\n";
  echo "<p>Index le 05/06/2019 : ".$D2l->getIndexesTotal('2019-06-05')." kWh</p>\r\n";
  echo "<p>Type de contrat : ".$D2l->typeContrat."</p>\r\n";

}
?>
