<?php
include_once (__DIR__."/RoutingController.php");
$routing=new RoutingController();
$controller= $routing->getController($_SERVER["REQUEST_URI"]);
include_once (__DIR__.'/'.$controller);
?>

