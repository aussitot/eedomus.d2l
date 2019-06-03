<?php
require("config.php");
require("phpD2lAPI.php"); //php class API for D2l

$D2l = new D2l($loginD2l, $passwordD2l);
$Ids = $D2l->getD2lIds();
print_r($Ids);
 ?>
