<?php
require("../comun/autoload.php");
date_default_timezone_set('America/Bogota'); 
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once '../clases/Academico.Class.php';
require_once '../clases/Control_ingreso.Class.php';
$academico=new Academico();

if(isset($_POST['Inicio']) and !empty($_POST['Inicio'])){
$_SESSION["asistencia"]="no";
}else{
	$_SESSION["asistencia"]="si";
}

if(isset($_SESSION["asistencia"]) and $_SESSION["asistencia"]=="no"){
header('location:../index.php');
}

$academica = new Academico;
$listado=$academica->listar_estudiantes_asignacion($_GET['asignacion']);
if (!empty($_POST)) {
if(isset($_POST['registrar_asistencia']) and $_POST['registrar_asistencia']=="on"){
$horarios=($academico->consultar_horario_Asignacion($_GET['asignacion'],0,0));
$control_ingreso =new control_ingreso;
$control_ingreso->hora_ingreso =$horarios[0]['hora_inicio'];
$control_ingreso->hora_salida =$horarios[0]['hora_fin'];
$control_ingreso->fecha_ingreso=$_POST['fecha'];
$control_ingreso->fecha_salida=$_POST['fecha'];
$control_ingreso->grupo=$_GET['asignacion'];
$control_ingreso->insertar_control();
}
$respuesta=$academica->registrar_asistencia($_GET['asignacion']);


if($respuesta==1){
	echo "<script>alert2('registro exitoso');window.location='../cursos/curso.php?asignacion=".$_GET['asignacion']."'</script>";
}
}


$informacion_materia=$academica->consultar_materia();
$nombre_asignatura=($academico->consultar_materia($_GET['asignacion']));
#print_r($nombre_asignatura);
?>
<div class="jumbotron" >
	<div class="fip">
	<?php 
	echo $nombre_asignatura=($nombre_asignatura[0]->nombre_materia);
 ?>
</div>
</div>
<form action="" method="POST">
	<input  type="hidden" name="asignacion" value="<?php echo $_GET['asignacion']; ?>">
	<input type="hidden" name="docente" value="1085290375">
<table id="tabla_asistencia" align="center">
	<tr>
		<th colspan="4">
<label>Fecha de Asistencia</label>
	<input  type="date" name="fecha" value="<?php echo date('Y-m-d') ?>">
			<?php
#echo '<br>'.Fecha::formato_fecha(date('Y/m/d'));
echo '/ '.date('g:i:s a').'<br>';
?></th>
		</tr>
	<tr>
		<th>#</th>
		<th>Foto</th>
		<th>Nombre</th>
		<th>Asistencia</th>
	</tr>
<?php
$contador=1;
foreach ($listado as $key => $info_estudiante) {  
	$persona=new persona($info_estudiante['id_estudiante']); ?>
	<tr>
		<td><?php echo $contador++; ?></td>
		<td>
<img width="100px" src="<?php echo SGA_COMUN_SOLOSGA_DATA.'/'.$persona->foto ?>"></img>
			</td>
		<td><?php echo $persona->nombre.' '.$persona->apellido.'('.$info_estudiante['id_estudiante'].')' ?></td>
		<td>
<style type="text/css">
	.radio{
		
	}
</style>

<input  checked type="radio" name="asistencia[<?php echo $info_estudiante['id_estudiante'] ?>]" value="si">
			  <label for="usr">Si</label>
<input  type="radio" name="asistencia[<?php echo $info_estudiante['id_estudiante'] ?>]" value="no">
<label for="usr">No</label>
<input type="radio" name="asistencia[<?php echo $info_estudiante['id_estudiante'] ?>]" value="permiso">
<label for="usr">Permiso</label>
		</td>
	</tr>
<?php } ?>
</table>
<label>Registrar Ingreso docente</label>
<input checked="true" type="checkbox" name="registrar_asistencia">
<br>
<input class="btn btn-warning" type="submit" name="" value="Guardar">
</form>

<form action="" method="post">
	<input class="btn btn-success" type="submit" name="Inicio" value="No quiero llamar asistencia">
</form>




<?php
$contenido = ob_get_clean();
require '../comun/plantilla.php'; 
?>