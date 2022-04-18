<?php 

$servidorbd = "217.61.128.194";
$usuariobd = "andreshstedu_guagua";
$clavebd = "Gugua profuturo";
$basededatos = "andreshstedu_guagua";

$mysqli = new mysqli ($servidorbd,$usuariobd,$clavebd,$basededatos);



if (mysqli_connect_errno()){



echo "error".mysqli_connect_errno();}else{



if($mysqli){



mysqli_set_charset($mysqli,'utf8');



#print_r($mysqli);



}



}



 ini_set('date.timezone', 'America/Bogota');



 date_default_timezone_set('America/Bogota');



 ?>



