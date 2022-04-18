<?php
require("comun/autoload.php");
$academico=new Academico;
$academico->id_asignacion =$_GET['nid'];
$lista= $academico->estudiantes_de_una_asignacion($_GET['nombre']);
#echo "<pre>";
#print_r($lista);
echo json_Encode($lista);
#echo "</pre>";
?>