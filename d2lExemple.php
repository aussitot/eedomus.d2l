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


  /*$IndexActuel = $D2l->getIndexes();
  echo "<p>Index actuel : ".$IndexActuel['total']." kWh</br>\r\n";
  echo "Index actuel HP : ".$IndexActuel['HP']." kWh</br>\r\n";
  echo "Index actuel HC : ".$IndexActuel['HC']." kWh</p>\r\n";

  echo "<p>Intensité instantanée : ".$D2l->getCurrentIntensity()." A</p>\r\n";

  $IndexDate = $D2l->getIndexes('2019-06-05');
  echo "<p>Index le 05/06/2019 : ".$IndexDate['total']." kWh</br>\r\n";
  echo "Index HP le 05/06/2019 : ".$IndexDate['HP']." kWh</br>\r\n";
  echo "Index HC le 05/06/2019 : ".$IndexDate['HC']." kWh</p>\r\n";

  echo "<p>Type de contrat : ".$D2l->typeContrat."</p>\r\n";

  $PowerUsedLastHour = $D2l->getPowerUsedLast('DAY');
  echo "<p>kWh totals consommés au cours des dernières 24H : ".$PowerUsedLastHour['total']." kWh</br>\r\n";
  echo "kWh totals HP consommés au cours des dernières 24H : ".$PowerUsedLastHour['HP']." kWh</br>\r\n";
  echo "kWh totals HC consommés au cours des dernières 24H : ".$PowerUsedLastHour['HC']." kWh</p>\r\n";*/

  $D2l->getPowerUsedBeetween('2019-06-05', '2019-06-06');

}
?>
