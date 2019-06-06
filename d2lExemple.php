<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require("config.php");
require("phpD2lAPI.php"); //php class API for D2l

$D2l = new D2l($loginD2lReel, $passwordD2lReel);
if ($D2l->error)
{
  echo $D2l->error;
} else {

  $LastIndexes = $D2l->getLastIndexes();
  if ($D2l->error)
  {
    echo $D2l->error;
  } else {
    print_r($LastIndexes);
  }

  $IndexesBetween = $D2l->getIndexesBetween('2019-06-06T12:00:00','2019-06-06T12:10:00');
  if ($D2l->error)
  {
    echo $D2l->error;
  } else {
    print_r($IndexesBetween);
  }
}
?>
