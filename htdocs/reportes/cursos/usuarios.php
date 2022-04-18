<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
@session_Start();
if($_SERVER["SERVER_NAME"] == "localhost")
{
  $ruta="../..";
}
else
{
  $ruta=$_SERVER['DOCUMENT_ROOT'].'/';
} 
$ruta_foto="../../sga-data/foto/";
require_once($ruta."comun/config.php");
require_once($_SERVER['DOCUMENT_ROOT']."/comun/funciones.php");
require $ruta.'comun/conexion.php';
require_once("../../comun/autoload.php");
require_once("../../clases/Control_ingreso.Class.php");
require_once '../../comun/lib/dompdf/vendor/autoload.php';
$institucion=new Institucion($_SESSION['id_institucion']);
$contenidoBinariologo = file_get_contents($institucion->logo_institucion);
$imagenComoBase64logo = base64_encode($contenidoBinariologo);
?>
<?php
use Dompdf\Dompdf;
$academico=new Academico();
$control_ingreso =new control_ingreso;
if(isset($_POST['id_asignacion'])){
   $id_asignacion=$_POST['id_asignacion'];
}
if(isset($_GET['id_asignacion'])){
  $id_asignacion=$_GET['id_asignacion'];
}
$porcentajes_asistencia= $control_ingreso->cantidad_asistencia($id_asignacion);
$academico->id_asignacion=$id_asignacion;
$datos_curso=$academico->consultar_materia($id_asignacion)[0];

$listado_Estudiantes=($academico->estudiantes_de_una_asignacion());
$html='<style type="text/css">
    .logo {
    font-family: "Arial Narrow";
    font-size: 14px;
}
.IEM{
    font-size:24px; 
}
</style>

<style media="print">
     #iemlogo {
      visibility:visible;
     } 
     #logoa{
       margin-left:2cm;
     }
     #logob{
      margin-rigth:200;
      position:absolute;
    }
     
</style>';
$html.="<img id='logoa' height='60' src='data:image/jpeg;base64,$imagenComoBase64logo'></img>";
$html.="<img  style='margin-left:800px;' height='60' src='data:image/jpeg;base64,$imagenComoBase64logo'></img>";
$html.='<h2 align="center">REPORTE ESTUDIANTES DE '.mb_strtoupper($datos_curso->nombre_materia, 'UTF-8').' </h2>';
      $html.='<table align="center" border="2"><tr>
<th>Identificación</th>
<th>nombre</th>
<th>Foto</th>
<th>usuario</th>
<th>Teléfono</th>
<th>Tipo de Sangre</th>
<th>Correo</th>
<th>Visitas</th>
<th>Estado</th>
<th>Ultimo ingreso</th>
</tr>';
#$listado_Estudiantes
foreach($listado_Estudiantes as $clave =>$row){
$estudiante=new Persona($row['id_usuario']);
$usuario=$estudiante->usuario;
$foto = $ruta_foto.$estudiante->foto;

$contenidoBinario = file_get_contents($foto);
$imagenComoBase64 = base64_encode($contenidoBinario);


$html.='<tr>
<td>'.$estudiante->id_persona.'</td>
<td>'.$estudiante->apellido.' '.$row['nombre'].'</td>';
$html.="<td><img  height='60' src='data:image/jpeg;base64,$imagenComoBase64'></img></td>";
$html.='<td>'.$estudiante->usuario.'</td>
<td>';
if($estudiante->telefono<>"null"){
  $html.= $estudiante->telefono;
}
$html.='</td>
<td>'.$estudiante->tipo_sangre.'</td>
<td>';
if($estudiante->correo<>"null"){
  $html.=$estudiante->correo ;
}
$html.='</td>
<td>'.$estudiante->num_visitas.'</td>
<td>'.$estudiante->estado.'</td>';
$fecha = new DateTime($estudiante->ultima_sesion) ;
$fechaordenada = $fecha -> format('d/m/Y');
$fechas = explode(" ", $estudiante->ultima_sesion);
$hora =date("g:i a",strtotime($fechas[0]));
if($estudiante->ultima_sesion=="0000-00-00 00:00:00"){
$html.="<td></td> ";
}
else{
$html.='<td>'.($fechaordenada).' <br/> '.$hora.'</td>';
}
$html.='</tr>';
}
$html.='</table>';
$html.='<footer style=" position: fixed; bottom: 0px; left: 0px; right: 0px; ">Generado por la plataforma Guagua: '.date('d-m-Y / g:i:s a').'</footer>';

#inscripcion;
if(isset($_POST['id_inscripcion'])){
require_once($_SERVER['DOCUMENT_ROOT']."/comun/autoload.php");
$estudiante = new Persona($usuario);
$fecha = new Fecha;
$academico= New Academico($_POST['id_asignacion']);
$registro_asistencia=$estudiante->consultar_asistencia($_POST['id_asignacion']);
$teacher=$academico->consultar_docente($_POST['id_asignacion']);
$informacion_materia = $academico->consultar_materia($_POST['id_asignacion']);
$docente = new persona($teacher[0]['id_docente']);
$html.='<table style="margin-top:5%" border="2" align="center">
  <tr><th colspan="2"> Asistencia ';
$html.='</th></tr>';
$html.='<tr>
    <th>Fecha</th>
    <th>Asistencia</th>
  </tr>';
  $cantidad_clases= count($registro_asistencia);
  $datos['si']=0;
  $datos['no']=0;
       foreach ($registro_asistencia as $key => $value) { 

$html.='<tr>

    <td>'.$fecha->formato_fecha($value['fecha']).'</td>

    <td>'.$value['asistencia'].'</td></tr>';

    if($value['asistencia']=="si"){

     $datos['si']=$datos['si']+1;

    }

    if($value['asistencia']=="no"){

     $datos['no']=$datos['no']+1;

    }

}  

$html.='</table>';
}
else{
$fecha = new Fecha;
if (isset($_GET['id_asignacion'])) {
 $_POST['id_asignacion']= $_GET['id_asignacion'];
}
# fin inscripcion;
$academico= New Academico($id_asignacion);
$datos_curso=$academico->consultar_materia($id_asignacion)[0];
$fechas=$academico->fechas_asistencia($id_asignacion);
$estudiantes= $academico->estudiantes_de_una_asignacion();
$html.='<div style="page-break-before:always"></div> ';
$html.="<img  height='60' src='data:image/jpeg;base64,$imagenComoBase64logo'></img>";
$html.="<img  style='margin-left:800px;' height='60' src='data:image/jpeg;base64,$imagenComoBase64logo'></img>";
$html.=  "
<h2 id='titulo' align='center'>
ASISTENCIA ".mb_strtoupper($datos_curso->nombre_materia, 'UTF-8').' <br> ('.mb_strtoupper($datos_curso->nombre_categoria_curso, 'UTF-8').' )</h2>';
$html.= "<table border='2'>";
$html.=  "<tr>";
$html.=  "<th>Id estudiante</th>";
$html.=  "<th>Foto</th>";
$html.=  "<th>Nombre estudiante</th>";

foreach ($fechas as $key2 => $fechas_asistencia) {
$html.=  "<th title='".$fechas_asistencia['fecha']."'>";
$html.= Fecha::formato_fecha_corta($fechas_asistencia['fecha']);
$html.=  "</th>";
}

$html.=  "<th>SI</th>";

$html.=  "<th>No</th>";

$html.=  "<th>TOTAL</th>";
$html.=  "<th>% de asistencia</th>";

$html.= "</tr>";

 $datos['si']=0;

  $datos['no']=0;


foreach ($estudiantes as $key => $estudiante_asistencia) {
$estudiante = new persona($estudiante_asistencia['id_usuario']);
$html.= "<tr align='center'>";
$html.= "<td>";
$html.=$estudiante_asistencia['id_usuario'];
$html.= "</td>";
$html.= "<td style='background-color:white'>";
$foto = '../../sga-data/foto/'.$estudiante->foto;
$contenidoBinario = file_get_contents($foto);
$imagenComoBase64 = base64_encode($contenidoBinario);
$html.="<img  height='60' src='data:image/jpeg;base64,$imagenComoBase64'></img>";
$html.= "</td>";
$html.= "<td>";
$html.=$estudiante->apellido.' '.$estudiante->nombre;
$html.= "</td>";
$estudiante = new persona($estudiante_asistencia['id_usuario']);


$datoses[$estudiante_asistencia['id_usuario']]['si']=0;

$datoses[$estudiante_asistencia['id_usuario']]['no']=0;

foreach ($fechas as $key2 => $fechas_asistencia) {

$informacion_asistencia=$estudiante->consultar_asistencia($_POST['id_asignacion'],$fechas_asistencia['fecha']);

$html.=  "<td>";

if(!empty($informacion_asistencia)){

if($informacion_asistencia[0]['asistencia']=="si"){

     $datos['si']=$datos['si']+1;  

     $datoses[$estudiante_asistencia['id_usuario']]['si']=$datoses[$estudiante_asistencia['id_usuario']]['si']+1;  

    }

    if($informacion_asistencia[0]['asistencia']=="no"){

$html.="<strong>";

     $datos['no']=$datos['no']+1;

     $datoses[$estudiante_asistencia['id_usuario']]['no']=$datoses[$estudiante_asistencia['id_usuario']]['no']+1;

    }

if($informacion_asistencia[0]['asistencia']=="no"){

$html.=strtoupper($informacion_asistencia[0]['asistencia']);

}else{

  $html.=($informacion_asistencia[0]['asistencia']);

}

if($informacion_asistencia[0]['asistencia']=="no"){

$html.="</strong>";

}

}

$html.=  "</td>";



}

if(empty($datoses[$estudiante_asistencia['id_usuario']]['si'])){

  $datoses[$estudiante_asistencia['id_usuario']]['si']=0;

}

if(empty($datoses[$estudiante_asistencia['id_usuario']]['no'])){

  $datoses[$estudiante_asistencia['id_usuario']]['no']=0;

}

$html.=  "<td>".$datoses[$estudiante_asistencia['id_usuario']]['si']."</td>";

$html.=  "<td>".$datoses[$estudiante_asistencia['id_usuario']]['no']."</td>";

$total=$datoses[$estudiante_asistencia['id_usuario']]['si']+$datoses[$estudiante_asistencia['id_usuario']]['no'];

$html.="<td>".$total."</td>";
$html.="<td>";
if(!empty($porcentajes_asistencia[$datoses[$estudiante_asistencia['id_usuario']]]['cantidad'])){
	$html.=$porcentajes_asistencia[$datoses[$estudiante_asistencia['id_usuario']]]['cantidad'].'%';
}else{
	$html.='0'.'%';
}
$html.="</td>";

$html.=  "</tr>";

}

$html.= "</table>";

}

echo $html;

$comun=new Comun();

$persona=new Persona();

$persona->graficar($datos,"Asistencia general del curso","PieChart","1400","600");

$Academico= new Academico();

$reporte=$Academico->reporte_notas();

if(!empty($reporte)){

?>

<table border="2">

  <tr>

  <th>Taller</th>

  <th>Valoración</th>

  <th>Fecha</th>

  </tr>

  <?php

   foreach($reporte as $key =>$value) {?>

  <tr>

  <th><?php echo $value['nombre_actividad']; ?></th>

  <th><?php echo $value['valoracion']; ?></th>

  <th><?php echo $value['fecha_entrega']; ?></th>

  </tr>

<?php } } ?>

</table>

</div>

<?php

$contenido = ob_get_contents();

ob_clean();

#include ("../../comun/plantilla.php");



$mipdf = new DOMPDF();
$mipdf ->set_paper("A4", "landscape");
$mipdf ->load_html($html,'UTF-8');
$mipdf ->render();
$datos_curso=$academico->consultar_materia($id_asignacion)[0];
if(isset($_GET['guardar'])){
  $output = $mipdf->output();
  file_put_contents('seguimiento/'.$datos_curso->nombre_materia.'/estudiantes/ Estudiantes '.$datos_curso->nombre_materia.'.pdf', $output);
  echo "<script>window.close();</script>";
}else{
  $mipdf->stream(' Reporte de estudiantes '.$datos_curso->nombre_materia.".pdf", array("Attachment"=>0));
 }





?>

