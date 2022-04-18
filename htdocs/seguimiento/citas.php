<?php 

ob_start();

#include 'menu.php';

require('../comun/autoload.php'); 

#require('conexion.php'); 

#require 'fecha.class.php';

#require 'mysql.class.php';

#require 'persona.class.php';

?>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.0/tinymce.min.js" integrity="sha512-XaygRY58e7fVVWydN6jQsLpLMyf7qb4cKZjIi93WbKjT6+kG/x4H5Q73Tff69trL9K0YDPIswzWe6hkcyuOHlw==" crossorigin="anonymous"></script>

  <script>tinymce.init({ selector:'textarea' });</script>



<?php

#echo '<center>';

require("../comun/conexion.php");

?>

<script>tinymce.init({ selector:'textarea' });</script>

<style type="text/css">

	.vertical-menu {

  width: 600px; /* Set a width if you like */

    position: absolute;

}



.vertical-menu a {



  color: black; /* Black text color */



  display: inline-block; /* Make the links appear below each other */



  padding: 12px; /* Add some padding */



  text-decoration: none; /* Remove underline from links */



}



.botones {



      background-color: white; 



      color: black; 



      border: 2px solid #008CBA;



}



</style>

<div class="vertical-menu">

<a class="botones" href="citas.php">Citas</a> 

<a class="botones" href="seguimiento.php">seguimiento</a> 

<a class="botones" href="estadisticas.php">estadisticas</a> 

</div>



<?php 

function buscar_seguimiento($datos="",$reporte=""){

require("../comun/conexion.php");

$sql = "SELECT `seguimiento`.`id_seguimiento`, `seguimiento`.`identificacion`, `seguimiento`.`cita`, `seguimiento`.`asistio`, `seguimiento`.`tipo_asesoria`, `seguimiento`.`hora`, `seguimiento`.`fecha_asesoria`, `seguimiento`.`contenido`, `seguimiento`.`observaciones`, `seguimiento`.`asesoria_tecnica`, `seguimiento`.`fecha_fin`, `seguimiento`.`hora_inicio`, `seguimiento`.`hora_fin`, `seguimiento`.`docente`, `usuario`.`nombre` as docentenombre_docente FROM `seguimiento`  inner join `usuario` on `seguimiento`.`docente` = `usuario`.`id_usuario`  ";







$datosrecibidos = $datos;

$datos = explode(" ",$datosrecibidos);

$datos[]="";

$cont =  0;

$sql .= ' WHERE ';

if($_SESSION['rol']=="docente"){

	$sql .= "  seguimiento.docente=".$_SESSION['id_usuario']."   ";

}

	



$values=array("", "casa", "coche", "", "azul", "");

 

$datos=array_filter($datos, "strlen");



	if(!empty($datos)){

		$sql.=' and ';

foreach ($datos as $id => $dato){



$sql .= ' concat(LOWER(`seguimiento`.`id_seguimiento`)," ", LOWER(`seguimiento`.`identificacion`)," ", LOWER(`seguimiento`.`cita`)," ", LOWER(`seguimiento`.`asistio`)," ", LOWER(`seguimiento`.`tipo_asesoria`)," ", LOWER(`seguimiento`.`hora`)," ", LOWER(`seguimiento`.`fecha_asesoria`)," ", LOWER(`seguimiento`.`contenido`)," ", LOWER(`seguimiento`.`observaciones`)," ", LOWER(`seguimiento`.`asesoria_tecnica`)," ", LOWER(`seguimiento`.`fecha_fin`)," ", LOWER(`seguimiento`.`hora_inicio`)," ", LOWER(`seguimiento`.`hora_fin`)," ", LOWER(`usuario`.`nombre`)," ") LIKE "%'.mb_strtolower($dato, 'UTF-8').'%"';

$cont ++;

if (count($datos)>1 and count($datos)<>$cont){

$sql .= " and ";

}

}

}

$sql .=  " ORDER BY `seguimiento`.`id_seguimiento` desc LIMIT ";

if (isset($_COOKIE['numeroresultados_seguimiento']) and $_COOKIE['numeroresultados_seguimiento']!="") $sql .=$_COOKIE['numeroresultados_seguimiento'];

else $sql .= "10";

#echo $sql;



$consulta = $mysqli->query($sql);

 ?>

<div align="center">

<table border="1" id="tbseguimiento">

<thead>

<tr>

<th># Seg</th>

<th>Estudiante</th>

<th>Asistio</th>

<th>Tipo Asesoria</th>

<th>Contenido</th>

<th>Observaciones</th>

<th>Asesoria Tecnica</th>

<th>Fecha Asesoria</th>

<th>Hora Inicio</th>

<th>Hora Fin</th>

<th>Docente</th>

<th>Reporte</th>

<?php



if ($reporte==""){ ?>

<th colspan="2"><form id="formNuevo" name="formNuevo" method="post" action="citas.php">

<input name="cod" type="hidden" id="cod" value="0">

<input class="btn btn-success" type="submit" name="submit" id="submit" value="Nuevo">

</form>

</th>

<?php } ?>

</tr>

</thead><tbody>

<?php 

while($row=$consulta->fetch_assoc()){

 ?>

<tr>

<td><?php echo $row['id_seguimiento']?></td>

<td><?php 

$persona=new Persona($row['identificacion']);

echo '<a target="_blank" href="../seguimiento/pdf.php?estudiante='.$row['identificacion'].'">'.$persona->nombre.'</a>'; ?></td>

<td><?php echo $row['asistio']?></td>

<td><?php echo $row['tipo_asesoria']?></td>

<td><?php echo $row['contenido']?></td>

<td><?php echo $row['observaciones']?></td>

<?php $datosasesoria_tecnica = array("si" => "si", "no" => "no"); ?>

<td><?php 

if(!empty($datosasesoria_tecnica[$row['asesoria_tecnica']])){

 echo $datosasesoria_tecnica[$row['asesoria_tecnica']]	;

}

 ?></td>

<?php $meses = array ('','\\E\\n\\e\\r\\o','\\F\\e\\b\\r\\e\\r\\o','\\M\\a\\r\\z\\o','\\A\\b\\r\\i\\l','\\M\\a\\y\\o','\\J\\u\\n\\i\\o','\\J\\u\\l\\i\\o','\\A\\g\\o\\s\\t\\o','\\S\\e\\p\\t\\i\\e\\m\\b\\r\\e','\\O\\c\\t\\u\\b\\r\\e','\\N\\o\\v\\i\\e\\m\\b\\r\\e','\\D\\i\\c\\i\\e\\m\\b\\r\\e'); ?>



<td><?php echo  date("d \\d\\e ".$meses[date("n",strtotime($row['fecha_fin']))]."  \\d\\e\\l \\a\\ñ\\o Y ",strtotime($row['fecha_fin'])); ?></td>

<td><?php echo Fecha::formato_hora($row['hora_inicio'])?></td>

<td><?php echo Fecha::formato_hora($row['hora_fin'])?></td>

<td><?php echo $row['docentenombre_docente']?></td>

<td><a target="_blank" href="pdf.php?estudiante=<?php echo $row['identificacion']; ?>&f=<?php echo $row['id_seguimiento']; ?>"><img src ="img/pdf.png"></a></td> 



<?php if ($reporte==""){ ?>

<td>

<form id="formModificar" name="formModificar" method="post" action="citas.php">

<input name="cod" type="hidden" id="cod" value="<?php echo $row['id_seguimiento']?>">

<input class="btn btn-primary" type="submit" name="submit" id="submit" value="Modificar">

</form>

</td>

<td>

<input type="image" src="img/eliminar.png" onClick="confirmeliminar('citas.php',{'del':'<?php echo $row['id_seguimiento'];?>'},'<?php echo $row['id_seguimiento'];?>');" value="Eliminar">

</td>

<?php } ?>

</tr>

<?php 

}/*fin while*/

 ?>

</tbody>

</table></div>

<?php 

}/*fin function buscar*/

if (isset($_GET['buscar'])){

buscar_seguimiento($_POST['datos']);

exit();

}



if (isset($_POST['del'])){

 /*Instrucción SQL que permite eliminar en la BD*/ 

$sql = 'DELETE FROM seguimiento WHERE id_seguimiento="'.$_POST['del'].'"';

 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/

if ($eliminar = $mysqli->query($sql)){

 /*Validamos si el registro fue eliminado con éxito*/ 

#echo 'Registro eliminado <meta http-equiv="refresh" content="1; url=citas.php" />'; 

}else{

#echo 'Eliminación fallida, por favor compruebe que la usuario no esté en uso

#<meta http-equiv="refresh" content="2; url=citas.php" />';

 

}

}

 ?>

<center>

	<br><br>

<h1>Cita y seguimiento</h1>

</center><?php 

if (isset($_POST['submit'])){

if ($_POST['submit']=="Registrar"){

 /*recibo los campos del formulario proveniente con el método POST*/ 

$sql = "INSERT INTO seguimiento (`identificacion`, `cita`, `asistio`, `tipo_asesoria`, `hora`, `fecha_asesoria`, `contenido`, `observaciones`, `asesoria_tecnica`, `fecha_fin`, `hora_inicio`, `hora_fin`, `docente`,grupo) VALUES ( '".$_POST['identificacion']."', '".$_POST['cita']."', '".$_POST['asesoria_tecnica']."', '".$_POST['tipo_asesoria']."', '".$_POST['hora_inicio']."', '".$_POST['fecha_fin']."', '".utf8_encode($_POST['contenido'])."', '".$_POST['observaciones']."', '".$_POST['asesoria_tecnica']."', '".$_POST['fecha_fin']."', '".$_POST['hora_inicio']."', '".$_POST['hora_fin']."', '".$_POST['docente']."','".$_POST['grupo']."')";

#echo $sql;

#echo "<pre>";

#print_r($_POST);

#echo "</pre>";

#exit();

 if ($insertar = $mysqli->query($sql)) {

 /*Validamos si el registro fue ingresado con éxito*/ 

echo 'Registro exitoso';

echo '<meta http-equiv="refresh" content="1; url=citas.php" />';

}else{ 

echo 'Registro fallido';

echo '<meta http-equiv="refresh" content="1; url=citas.php" />';

}

} /*fin Registrar*/ 

if ($_POST['submit']=="Nuevo"){



$textoh1 ="Registrar";

$textobtn ="Registrar";



echo '<form align="center" id="form1" name="form1" method="post" action="citas.php">

<h1>'.$textoh1.'</h1>';

?>

<p><input name="grupo" type="hidden" id="grupo" value="<?php if (isset($_POST['asignacion']))  echo $_POST['asignacion'] ?><?php if (isset($row['grupo']))  echo $row['grupo'] ?>" size="120" required></p>



<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id_seguimiento']))  echo $row['id_seguimiento'] ?>" size="120" required></p>

<?php 

echo '<p><input class=""name="id_seguimiento"type="hidden" id="id_seguimiento" value="';if (isset($row['id_seguimiento'])) echo $row['id_seguimiento'];echo '"';echo '></p>';

require_once('../comun/autoload.php'); 

$persona=new Persona(); ?>

<?php



echo '<p><label for="identificacion">Identificacion:</label>';

echo $persona->datalist("usuario"); 

echo '<input  label="hola" name="identificacion"type="text"  list="usuario" min="0" id="identificacion" value="';if (isset($row['identificacion'])) echo $row['identificacion'];if (isset($_POST['estudiante'])) echo $_POST['estudiante'];echo '"';echo '></p>';

echo '<p><input class=""name="cita"type="hidden" id="cita" value="';if (isset($row['cita'])) echo $row['cita'];echo '"';echo '></p>';

echo '<p><input class=""name="asistio"type="hidden" id="asistio" value="';if (isset($row['asistio'])) echo $row['asistio'];echo '"';echo '></p>';



?>

<datalist list="tipo">

<option>personal</option>

<option>personal</option>

</datalist>

<?php



echo '<p><label for="tipo_asesoria">Tipo Asesoria:</label><input class=""name="tipo_asesoria"type="text"  list="tipo" id="tipo_asesoria" value="';if (isset($row['tipo_asesoria'])) echo $row['tipo_asesoria'];echo '"';echo '></p>';

echo '<p><input class=""name="hora"type="hidden" id="hora" value="';if (isset($row['hora'])) echo $row['hora'];echo '"';echo '></p>';

echo '<p><input class=""name="fecha_asesoria"type="hidden" id="fecha_asesoria" value="';if (isset($row['fecha_asesoria'])) echo $row['fecha_asesoria'];echo '"';echo '></p>';

$resultado=$persona->datos_contenido("contenido"); 

echo '<p><label for="contenido">Contenido:</label><input class=""name="contenido"type="text" id="contenido" list="contenido" value="';if (isset($row['contenido'])) echo $row['contenido'];echo '"';echo '></p>';

echo '<p><label for="observaciones">Observaciones:</label></p><p><textarea class="" name="observaciones" cols="60" rows="10" id="observaciones" >';if (isset($row['observaciones'])) echo $row['observaciones'];echo '</textarea></p>';

echo '<p><label for="asesoria_tecnica">Asesoria Tecnica:</label><br><input type="radio" class="" name="asesoria_tecnica" id="asesoria_tecnica[1]"  value="si"';

if(!isset($row['asesoria_tecnica'])){

	echo " checked ";

}

if (isset($row['asesoria_tecnica']) and $row['asesoria_tecnica'] =="si") echo " checked ";echo '><label>si</label><br><input type="radio" class="" name="asesoria_tecnica" id="asesoria_tecnica[2]"  value="no"';if (isset($row['asesoria_tecnica']) and $row['asesoria_tecnica'] =="no") echo " checked ";echo '><label>no</label><br></p>';

echo '<p><label for="fecha_fin">Fecha :</label><input class=""name="fecha_fin"type="date" id="fecha_fin" value="';

if (!isset($row['fecha_fin'])){

	echo date('Y-m-d');

}

if (isset($row['fecha_fin'])) echo $row['fecha_fin'];echo '"';echo '></p>';

echo '<p><label for="hora_inicio">Hora Inicio:</label><input class=""name="hora_inicio"type="time" id="hora_inicio" value="';if (isset($row['hora_inicio'])) {echo $row['hora_inicio'];}

else{ echo date('H:i'); }

echo '"';echo '></p>';

echo '<p><label for="hora_fin">Hora Fin:</label><input class=""name="hora_fin"type="time" id="hora_fin" value="';if (isset($row['hora_fin'])) { echo $row['hora_fin']; } else{

echo date('H:i', strtotime('+ 2 hours'));

	; } echo '"';echo '></p>';

echo '<p><label for="docente">Docente:</label>';

$sql14= "SELECT id_docente,nombre_docente,apellido_docente FROM docente;";

echo '<select class="" name="docente" id="docente" >';

echo '<option value="">Seleccione una opci&oacute;n</option>';

$consulta14 = $mysqli->query($sql14);

while($row14=$consulta14->fetch_assoc()){

echo '<option value="'.$row14['id_docente'].'"';if (isset($row['docente']) and $row['docente'] == $row14['id_docente'] or $row['docente']="1085290375" ) echo " selected ";echo '>'.$row14['nombre_docente'].' '.$row14['apellido_docente'].'</option>';

}

echo '</select></p>';



echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>

</form>';

} /*fin nuevo*/ 

if ($_POST['submit']=="Modificar"){



$sql = "SELECT `id_seguimiento`, `identificacion`, `cita`, `asistio`, `tipo_asesoria`, `hora`, `fecha_asesoria`, `contenido`, `observaciones`, `asesoria_tecnica`, `fecha_fin`, `hora_inicio`, `hora_fin`, `docente` FROM `seguimiento` WHERE id_seguimiento ='".$_POST['cod']."' Limit 1"; 

$consulta = $mysqli->query($sql);

 /*echo $sql;*/ 

$row=$consulta->fetch_assoc();



$textoh1 ="Modificar";

$textobtn ="Actualizar";

echo '<form id="form1" name="form1" method="post" action="citas.php">

<h1>'.$textoh1.'</h1>';

?>



<p><input name="cod" type="hidden" id="cod" value="<?php if (isset($row['id_seguimiento']))  echo $row['id_seguimiento'] ?>" size="120" required></p>

<?php 

echo '<p><input class=""name="id_seguimiento"type="hidden" id="id_seguimiento" value="';if (isset($row['id_seguimiento'])) echo $row['id_seguimiento'];echo '"';echo '></p>';

echo '<p><label for="identificacion">Identificacion:</label><input class=""name="identificacion"type="text" list="usuario"  min="0" id="identificacion" value="';if (isset($row['identificacion'])) echo $row['identificacion'];echo '"';echo '></p>';

echo '<p><input class=""name="cita"type="hidden" id="cita" value="';if (isset($row['cita'])) echo $row['cita'];echo '"';echo '></p>';

echo '<p><input class=""name="asistio"type="hidden" id="asistio" value="';if (isset($row['asistio'])) echo $row['asistio'];echo '"';echo '></p>';

echo '<p><label for="tipo_asesoria">Tipo Asesoria:</label><input class=""name="tipo_asesoria"type="text" id="tipo_asesoria" value="';if (isset($row['tipo_asesoria'])) echo $row['tipo_asesoria'];echo '"';echo '></p>';

echo '<p><label for="hora">Hora:</label><input class=""name="hora"type="time" id="hora" value="';if (isset($row['hora'])) echo $row['hora'];echo '"';echo '></p>';

echo '<p><input class=""name="fecha_asesoria"type="hidden" id="fecha_asesoria" value="';if (isset($row['fecha_asesoria'])) echo $row['fecha_asesoria'];echo '"';echo '></p>';

echo '<p><label for="contenido">Contenido:</label><input class=""name="contenido"type="text" id="contenido" value="';if (isset($row['contenido'])) echo $row['contenido'];echo '"';echo '></p>';

echo '<p><label for="observaciones">Observaciones:</label></p><p><textarea class="" name="observaciones" cols="60" rows="10"id="observaciones" >';if (isset($row['observaciones'])) echo $row['observaciones'];echo '</textarea></p>';

echo '<p><label for="asesoria_tecnica">Asesoria Tecnica:</label><br><input type="radio" class="" name="asesoria_tecnica" id="asesoria_tecnica[1]"  value="si"';if (isset($row['asesoria_tecnica']) and $row['asesoria_tecnica'] =="si") echo " checked ";echo '><label>si</label><br><input type="radio" class="" name="asesoria_tecnica" id="asesoria_tecnica[2]"  value="no"';if (isset($row['asesoria_tecnica']) and $row['asesoria_tecnica'] =="no") echo " checked ";echo '><label>no</label><br></p>';

echo '<p><label for="fecha_fin">Fecha Fin:</label><input class=""name="fecha_fin"type="date" id="fecha_fin" value="';if (isset($row['fecha_fin'])) echo $row['fecha_fin'];echo '"';echo '></p>';

echo '<p><label for="hora_inicio">Hora Inicio:</label><input class=""name="hora_inicio"type="time" id="hora_inicio" value="';if (isset($row['hora_inicio'])) echo $row['hora_inicio'];echo '"';echo '></p>';

echo '<p><label for="hora_fin">Hora Fin:</label><input class=""name="hora_fin"type="time" id="hora_fin" value="';if (isset($row['hora_fin'])) echo $row['hora_fin'];echo '"';echo '></p>';

echo '<p><label for="docente">Docente:</label>';

$sql14= "SELECT id_docente,nombre_docente FROM docente;";

echo '<select class="" name="docente" id="docente" >';

echo '<option value="">Seleccione una opci&oacute;n</option>';

$consulta14 = $mysqli->query($sql14);

while($row14=$consulta14->fetch_assoc()){

echo '<option value="'.$row14['id_docente'].'"';if (isset($row['docente']) and $row['docente'] == $row14['id_docente']) echo " selected ";echo '>'.$row14['nombre_docente'].'</option>';

}

echo '</select></p>';



echo '<p><input type="submit" name="submit" id="submit" value="'.$textobtn.'"></p>

</form>';

} /*fin modificar*/ 

#echo "<pre>";

#print_r($_POST);

#echo "</pre>";

if ($_POST['submit']=="Actualizar"){

 /*recibo los campos del formulario proveniente con el método POST*/ 

$cod = $_POST['cod'];

 /*Instrucción SQL que permite insertar en la BD */ 

$sql = "UPDATE seguimiento SET id_seguimiento='".$_POST['id_seguimiento']."', identificacion='".$_POST['identificacion']."', cita='".$_POST['cita']."', asistio='".$_POST['asistio']."', tipo_asesoria='".$_POST['tipo_asesoria']."', hora='".$_POST['hora']."', fecha_asesoria='".$_POST['fecha_asesoria']."', contenido='".($_POST['contenido'])."', observaciones='".$_POST['observaciones']."', asesoria_tecnica='".$_POST['asesoria_tecnica']."', fecha_fin='".$_POST['fecha_fin']."', hora_inicio='".$_POST['hora_inicio']."', hora_fin='".$_POST['hora_fin']."', docente='".$_POST['docente']."'WHERE  id_seguimiento = '".$cod."';";

exit();

/* echo $sql;*/ 

 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 

if ($actualizar = $mysqli->query($sql)) {

 /*Validamos si el registro fue ingresado con éxito*/

echo 'Modificación exitosa';

#echo '<meta http-equiv="refresh" content="1; url=citas.php" />';

 }else{ 

#echo 'Modificacion fallida';

}

#echo '<meta http-equiv="refresh" content="2; url=citas.php" />';

} /*fin Actualizar*/ 

 }else{ 

 ?>

<center>

<b><label>Buscar: </label></b><input type="search" id="buscar" onkeyup ="buscar(this.value);" onchange="buscar(this.value);"  style="margin: 15px;">

<b><label>N° de Resultados:</label></b>

<input type="number" min="0" id="numeroresultados_seguimiento" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10" onkeyup="grabarcookie('numeroresultados_seguimiento',this.value) ;buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultados_seguimiento',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultados_seguimiento',this.value);buscar(document.getElementById('buscar').value);" size="4" style="width: 40px;">

</center>

<span id="txtsugerencias">

<?php 

buscar_seguimiento();

 ?>

</span>

<?php 

}/*fin else if isset cod*/

echo '</center>';

 ?>

<script>

document.getElementById('menu_cita').className ='active '+document.getElementById('menu_cita').className;

</script>

<?php $contenido = ob_get_contents();

ob_clean();

include ("../comun/plantilla.php");

?>