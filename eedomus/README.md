Bonjour,

Voici un script pour intÃ©grer dans l'interface eedomus les consommations mesurÃ©es par un module D2L (ERL WiFi pour compteur Linky) : http://eesmart.fr/modulesd2l/erl-wifi-compteur-linky/

Ca va vous permettre de
- afficher l'index du compteur linky (total, HP, HC,...)
- afficher l'intensitÃ© actuelle (en A)
- afficher la consommation (en kWh) de la derniere heure, jour, mois, annÃ©e

La version eedomus est disponible sur le store eedomus
La classe php (et sa doc) utilisable pour d'autres box ou projets domotiques est disponible ici :
https://github.com/aussitot/eedomus.d2l

##Installation :

En fonction de votre contrat (BASE, HC/HP, EJP, TEMPO) choisissez les capteurs qui seront installÃ©s (en plus de ceux communs)
Le numéro de module permet de gérer les comptes qui disposent de plusieurs modules D2L. Si vous n'avez qu'un module laisser cette valeur à 1. Si vous en avez plusieurs 1 pour le premier, 2 pour le deuxième, etc....

##Le plugin

Le module renvoi les informations suivantes :

- Index total - Mis à jour toutes les heures
- Index HC/HP (ou HN/HP pour les contrats EJP, ou HC/HP Jours bleu/blanc/rouge pour les contrat TEMPO) - Mis à jour toutes les heures
- Intensité en A  - Mis Ã  jour toutes les minutes
- Puissance consommée lors des 60 dernières minutes  - Mis à jour toutes les heures
- Puissance consommée lors des 24 dernières heures  - Mis à jour toutes les heures
- Puissance consommée lors des 30 derniers jours  - Mis à jour toutes les heures
- Puissance consommée lors des 365 derniers jours  - Mis à jour toutes les heures


Il existe d'autres données remontées pour lesquelles je n'ai pas créé de capteur pour ne pas surcharger le plugin mais que vous pouvez ajouter manuellement selon votre type de contrat
```php
//pour les installations triphasées
d2l_intensity
/root/intensity/i1
/root/intensity/i2
/root/intensity/i3
```
```php
//pour les contrats tempo
d2l_powerlasthour, d2l_powerlastday, d2l_powerlastmonth, d2l_powerlastyear
/root/power/HCJB
/root/power/HPJB
/root/power/HCJW
/root/power/HPJW
/root/power/HCJR
/root/power/HPJR
```
```php
//pour les contrats EJP
d2l_powerlasthour, d2l_powerlastday, d2l_powerlastmonth, d2l_powerlastyear
/root/power/HN
/root/power/HP
```

Pour obtenir la consommation en kWh du jour prÃ©cÃ©dent (et pas sur les dernieres 24H):
`
//Pour J-1
http://localhost/script/?exec=d2l.php&type=powerlastday-1&user=[VAR1]&pass=[VAR2]&number=[VAR3]
//Pour M-1
http://localhost/script/?exec=d2l.php&type=powerlastmonth-1&user=[VAR1]&pass=[VAR2]&number=[VAR3]
`
Pour obtenir l'ensembles des infos brut remontÃ©es par le module D2L :
`
http://localhost/script/?exec=d2l.php&type=initialdata&user=[VAR1]&pass=[VAR2]&number=[VAR3]
`
