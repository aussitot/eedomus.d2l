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
