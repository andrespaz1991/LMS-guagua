<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT']."/comun/autoload.php");
require_once $_SERVER['DOCUMENT_ROOT'].'/comun/lib/dompdf/vendor/autoload.php';
@session_start();
if(isset($_GET['id_plan'])){
$planeacion=new Planeacion($_GET['id_plan']);
$todas_planeaciones= $planeacion->mostrar_todas_planeaciones();
}
if(!isset($_GET['id_plan'])){
  $planeacion=new planeacion();
$todas_planeaciones= $planeacion->mostrar_todas_planeaciones($_GET['id_materia'],$_GET['nombre_grado']);

}
function ajustar_contenido($contenido){
  return $contenido = substr($contenido, 0, -1);
}

$html="";

$pagina=0;
foreach ($todas_planeaciones as $key => $plan) {

$pagina++;

$planeacion=new planeacion($plan[0]);

$institucion=new institucion($_SESSION['id_institucion']);

if($pagina<>1){

$html.=' <div style="page-break-after:always;"></div>';

}

$docente=new Persona($planeacion->docente);

$instancia_materia=new Materias($planeacion->materia);

$html.='<div style="overflow: hidden;

text-align:center;

  margin-bootom:10px;

  padding: 20px 10px;" class="header">';
  $lugar=$_SERVER['DOCUMENT_ROOT']."/comun//sga-data/foto/hst.png";
 #$html.='<img style="width:10%;position:absolute;" src="'.$lugar.'"></img>';
$html.='<a href="#default" class="logo"><strong>'.$institucion->nombre_institucion.'<br>'.$docente->nombre.' '.$docente->apellido.'</strong> </br> <strong>'.strtoupper($instancia_materia->nombre_materia).'</strong></a>';

#$html.='<img style="width:10%;position:absolute;margin-left:70%;margin-top:-4%" src="../comun/sga-data/foto/'.$institucion->logo_institucion.'"></img>';

$html.='</div>';





$html.='<table border="2" style="width: 100%">';

$html.='<tr>';

$html.='<th>Número</th>';

$html.='<th colspan="2">Contenido</th>';

$html.='<th>Fecha</th>';

$html.='</tr>';



$html.='<tr>';

$html.='<th>'.$planeacion->orden.'</th>';

$html.='<th colspan="2">'.ajustar_contenido($planeacion->contenido_plan).'</th>';

$html.='<th>Fecha:'.Fecha::formato_fecha($planeacion->fecha_plan).'</th>';

$html.='</tr>';

$html.='<tr>';

$html.='<th colspan="4">Objetivo:'.ajustar_contenido($planeacion->objetivos_plan).'</th>

</tr>';

$html.='<tr>';

$html.='<th>Plan</th>';

$html.='<th>Estrategia:</th>';

$html.='<th>Actividad:</th>';

$html.='<th>Recurso:</th>';

$html.='</tr>';





$html.='<tr>';

$html.='<th>Plan A</th>';

$html.='<th>'.ajustar_contenido($planeacion->estrategiaa).'</th>';

$html.='<th>'.ajustar_contenido($planeacion->plana['Actividad']).'</th>';

$html.='<th>'.ajustar_contenido($planeacion->plana['Recurso']).'</th>';

$html.='</tr>';

$html.='<tr>';

$html.='<th>Plan B</th>';

$html.='<th>'.ajustar_contenido($planeacion->planb['estrategiaplanb']).'</th>';

if(empty($planeacion->planb['Actividadplanb'])){

	$planeacion->planb['Actividadplanb']="";

}

$html.='<th>'.ajustar_contenido($planeacion->planb['Actividadplanb']).'</th>';

$html.='<th>'.ajustar_contenido($planeacion->planb['Actividadplanb']).' </th>';

$html.='</tr>';

$html.='<tr>';

$html.='<th colspan="4">Observaciones: </th>';

$html.='</tr>';

$html.='</table>';

$html.='<table border="2"><br>';

$html.='<tr><th colspan="6">Recurso Educativo Digital Implementado</th></tr>';

$html.='<tr>';

$html.='<th>Id</th>';

$html.='<th>Recurso Educativo</th>';

$html.='<th>formato</th>';

$html.='<th>Visitas</th>';

$html.='<th>Fecha de registro</th>';

$html.='<th>Descripción</th>';

$html.='</tr>';
$recursoseducativos=json_decode($planeacion->red);
if(!empty($recursoseducativos)){
foreach ($recursoseducativos as $idred => $recurso) {
  if(!empty($recurso)){
$red =new Red($recurso);
$html.='<tr>';
$html.='<th>'.$red->id_red.'</th>';
$html.='<th>'.$red->titulo_red.'</th>';
$html.='<th>'.$red->formato.'</th>';
$html.='<th>'.$red->visitas.'</th>';
$html.='<th>'.Comun::formato_fecha($red->fecha).'</th>';
$html.='<th>'.Comun::puntos_suspensivos($red->descripcion,120).'</th>';
$html.='</tr>';
  }
}
}
$html.='</table>';
$html.='<footer style=" position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  color: black;
  text-align: center;">';
$html.='<p>'.Fecha::formato_fecha(date('d-m-Y')).',Página '.$pagina.' de '.count($todas_planeaciones).'</p>';
$html.='</footer>';
  }
 
  use Dompdf\Dompdf;
  #set_time_limit(27000);
  $mipdf = new DOMPDF();
  $mipdf->set_paper('A4', 'landascape'); 
  $mipdf->load_html($html,'UTF-8');
  $mipdf->render();
  $output = $mipdf->output();
  $mipdf->stream('seguimiento.pdf', array("Attachment" => 0) ); 
   ?>

