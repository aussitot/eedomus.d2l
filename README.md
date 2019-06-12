# eedomus.d2l
class php et script eedomus pour le module D2L (ERL WiFi pour compteur Linky) : http://eesmart.fr/modulesd2l/erl-wifi-compteur-linky/

## phpD2lAPI
### Constructor
```php
$D2l = new D2l($loginD2l, $passwordD2l);
```
### Méthodes
```php
$D2l->getIndexes($AtDate = null); //Array
//Get indexes

$D2l->getPowerUsedLast($lastperiod = 'HOUR'); //Array
//get the power used in kWh for last MIN,HOUR,DAY,WEEK,MONTH,YEAR

$D2l->getPowerUsedBeetween($dateFrom, $dateTo); //Array
//get the power used in kWh from date to date

$D2l->getCurrentIntensity(); //String
//Get current intensity
```
### Propriétés
```php
$D2l->version; //API Version
$D2l->error; //Last error
$D2l->typeContrat; //Get the subscribed contract type (based on last index)
```

### Exemples
```php
require("eedomus.lib.php"); //eedomus emulation lib
require("phpD2lAPI.php"); //php class API for D2l

$loginD2lReel="xxxx"; //login consospy
$passwordD2lReel="yyyyy"; // password consospy

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

  echo "<p>Intensité instantanée : ".$D2l->getCurrentIntensity()." A</p>\r\n";

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

}
```
