<?php
ob_start();
require("../comun/config.php");
require_once(RUTA_LOCAL."/comun/autoload.php");
if(isset($_GET['asignacion'])){
  $asignacion=$_GET['asignacion'];  
}else{
  $asignacion=$_GET['id_asignacion'];
}

$academico=new Academico($asignacion);
$actividad=new Actividad();
$listado_estudiantes=$academico->estudiantes_de_una_asignacion();
$lista_actividades= $actividad->actividades_por_asignacion($asignacion);
$datos_curso=$academico->consultar_materia($asignacion)[0];
$html='
<h2 align="center">Seguimiento de actividades </h2>
<table border="1">
<tr><th>Estudiante</th>';
foreach($lista_actividades as $idactividad => $info_actividad){
  $html.="<th>";
  $html.=($lista_actividades[$idactividad]['id_actividad']); #$lista_actividades[$idactividad]['nombre_actividad']
  $html.="</th>";
}
$html.="<th>promedio</th></tr> ";

foreach($listado_estudiantes as $id => $info_estudiante){
  $html.= "<tr><td>";
  $html.= $info_estudiante['apellido'].' '.$info_estudiante['nombre'];
  $html.= "</td>";
  foreach($lista_actividades as $idactividad => $info_actividad){
    $html.= "<td>";
    $actividad=$lista_actividades[$idactividad]['id_actividad'];
    $info_estudiante['id_inscripcion'];
    $inf_notas=$academico->seguimiento_actividades($actividad,$info_estudiante['id_inscripcion']);
    $html.=($inf_notas[0]['valoracion']);
    $html.= "</td>";
  }
  $promedio=$academico->promedio_actividades($info_estudiante['id_inscripcion'],$asignacion);
  $html.= "<td>".$promedio[0]['promedio']."</td>";
  $html.= "</tr>";
}
$html.=' 
</table >
<h2 align="center">Actividades</h2>
<table border="1">
  <tr>
<th>id</th>
<th>Nombre Actividad</th>
<th>Evaluable</th>
<th>Fecha publicación</th>
<th>Fecha entrega</th>
</tr>';
?>
<?php
foreach($lista_actividades as $idactividad => $info_actividad){
$html.= "<tr>";
$html.= "<td>".$lista_actividades[$idactividad]['id_actividad']."</td>";
$html.= "<td>".$lista_actividades[$idactividad]['nombre_actividad']."</td>";
$html.= "<td>".$lista_actividades[$idactividad]['evaluable']."</td>";
$html.= "<td>".fecha::formato_fecha($lista_actividades[$idactividad]['fecha_publicacion']).' a  las '.$lista_actividades[$idactividad]['hora_publicacion']."</td>";
$html.= "<td>".fecha::formato_fecha($lista_actividades[$idactividad]['fecha_entrega']).' a  las '.$lista_actividades[$idactividad]['hora_entrega']."</td>";
$html.= "</tr>";
}
require_once '../comun/lib/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
$mipdf= new DOMPDF();
$mipdf->set_paper('A4', "landscape");
$mipdf->load_html($html,'UTF-8');
$mipdf->render();


if(!isset($_GET['guardar'])){
  $mipdf->stream(' Seguimiento de actividades '.$datos_curso->nombre_materia.".pdf", array("Attachment"=>0));
 }else{
  $output = $mipdf->output();
  file_put_contents('../reportes/cursos/seguimiento/'.$datos_curso->nombre_materia.'/actividades/ Seguimiento de actividades '.$datos_curso->nombre_materia.'.pdf', $output);
  echo "<script>window.close();</script>";
 }


?>
