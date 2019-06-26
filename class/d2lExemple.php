<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require("config.php"); //remplacez cette ligne par vos login/password Consospy ($loginD2lReel="xxxx"; $passwordD2lReel="yyyyy";)
require("eedomus.lib.php"); //eedomus emulation lib
require("phpD2lAPI.php"); //php class API for D2l


$D2l = new D2l($loginD2lReel, $passwordD2lReel);
if ($D2l->error)
{

  echo $D2l->error;

} else {

  $IndexActuel = $D2l->getIndexes();
  if ($D2l->error)
  {
    echo $D2l->error;
  } else {
    echo "<p>Index actuel : ".$IndexActuel['total']." kWh</br>\r\n";
    echo "Index actuel HP : ".$IndexActuel['HP']." kWh</br>\r\n";
    echo "Index actuel HC : ".$IndexActuel['HC']." kWh</p>\r\n";
  }
/*
  $CurrentIntensity = $D2l->getCurrentIntensity();
  echo "<p>Intensité instantanée : ".$CurrentIntensity['total']." A</p>\r\n";

  $IndexDate = $D2l->getIndexes('2019-06-05');
  echo "<p>Index le 05/06/2019 : ".$IndexDate['total']." kWh</br>\r\n";
  echo "Index HP le 05/06/2019 : ".$IndexDate['HP']." kWh</br>\r\n";
  echo "Index HC le 05/06/2019 : ".$IndexDate['HC']." kWh</p>\r\n";

  echo "<p>Type de contrat : ".$D2l->typeContrat."</p>\r\n";

  $PowerUsedLastHour = $D2l->getPowerUsedLast('HOUR');
  echo "<p>kWh totals consommés au cours de la dernière Heure : ".$PowerUsedLastHour['total']." kWh</br>\r\n";
  echo "kWh totals HP consommés au cours de la dernière Heure : ".$PowerUsedLastHour['HP']." kWh</br>\r\n";
  echo "kWh totals HC consommés au cours de la dernière Heure : ".$PowerUsedLastHour['HC']." kWh</p>\r\n";

  $PowerUsedBeetween = $D2l->getPowerUsedBeetween('2019-06-10', '2019-06-11');
  echo "<p>kWh totals consommés le 10/06/2019 : ".$PowerUsedBeetween['total']." kWh</br>\r\n";
  echo "kWh totals HP consommés le 10/06/2019 : ".$PowerUsedBeetween['HP']." kWh</br>\r\n";
  echo "kWh totals HC consommés le 10/06/2019 : ".$PowerUsedBeetween['HC']." kWh</p>\r\n";

  $PowerUsedLastJM1 = $D2l->getPowerUsedLast('DAY-1');
  print_r($PowerUsedLastJM1);*/

  $initialData = $D2l->getInitialData();
  print_r($initialData);

}
?>
