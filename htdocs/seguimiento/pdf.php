<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
@session_start();
require_once '../comun/lib/dompdf/vendor/autoload.php';
$html='';
require_once("../comun/config.php");
require_once("../clases/Fecha.Class.php");
require_once("../clases/Comun.Class.php");
require_once("../clases/Clase_mysqli.Class.php");
require_once("../clases/Institucion.Class.php");
require_once("../clases/Persona.Class.php");
require '../comun/conexion.php';
 $sql ='select * from usuario, seguimiento where usuario.id_usuario = seguimiento.identificacion ';
 if(!empty($_GET['estudiante'])){
  $sql.=' and usuario.id_usuario = "'.$_GET['estudiante'].'"  ';
 }
  if(!empty($_GET['f'])){
$sql.=' and id_seguimiento ="'.$_GET['f'].'" ';
 }
$consultarid = $mysqli->query($sql);
$html.='<table width="100%" border="2" style="text-align: center; border: black" ><tr>';
    $html.="   <TH   rowspan='5' style='border:  black;'>";
       if(isset($_SESSION['logo'])){  
    $html.=  "<img src='img/".$_SESSION['logo']."'  height='120' >";
     } 
      $html.='</TH>';
    $html.=' <TD class="color"  ROWSPAN="2" align="center">';
     $html.="  <strong>";
      if(isset($_SESSION['institucion'])){ 
$institucion=new institucion($_SESSION['institucion']);
        $html.= $institucion->nombre_institucion; } 
      $html.= "</strong>";
       $html.= "</TD>    ";
         $html.= '<TD class="color">';
      $html.=' <strong>  Codigo</strong>
      </TD>
      </TR>
      <TR>
      <TD class="color">
 <strong>pagina 1 de 1</strong>
      </TD>
      </TR>
      <TR>
      <TD class="color" ROWSPAN="2" align="center">
<strong>Asesorias del docente';
 if(isset($_SESSION['nombre'])){ $html.= $_SESSION['nombre'].' '.$_SESSION['apellido']; } else{
  $html.= "Andres Paz Burbano";
} 
$html.='</strong>
     </TD>
      <TD class="color">
  <strong>    versi&oacute;n</strong>
      </TD>
      </TR>
      <TR>
      <TD class="color"><strong> fecha: ';
    $html.= date("d-m-Y");
$html.=' </strong>
      </TD>
      </TR>
    </table>';
$html.="<div align='center'>";
$persona=new Persona($_GET['estudiante']);
$html.= '<h1>Informaci&oacute;n del estudiante</h1>';
$html.= '<label><strong>Identificacion</strong> </label>';
$html.= $persona->id_usuario.'<br>';
$html.= '<label><strong>Nombre </strong></label>';
$html.= $persona->nombre.'<br>';
$html.= '<img style="width:20%;height:8%;position:absolute;margin-left:70%;margin-top:-5% " height="50" src="../sga-data/foto/'.$persona->foto.'">';
$html.= '<label><strong>Télefono</strong> </label>';
$html.= $persona->telefono.'<br>'; 
$html.= '<label><strong>Dirección</strong> </label>';
$html.= $persona->direccion.'<br>';                        
$html.= '</div><h1 align="center">Seguimiento</h1>';
$html.= '<table border="2" align="center" style="text-align: center; border: black" >';
$html.= '<thead>';
$html.= '<tr>';
$html.=  '<th>Seguiento</th>';
$html.=  '<th>Contenido</th>';
$html.=  '<th>observaciones</th>';
$html.=  '<th>asistio</th>';
$html.=  '<th>asesoria tecnica</th>';
$html.=  '<th>Fecha y hora</th>';
 $html.= '</tr>';    
 $html.= '</thead>';    
$consultarid;
$contador=0;
while ($ruw = $consultarid -> fetch_assoc()){      
$contador++; 
if ($contador % 2 == 0) {
    $html.= '<tr class="impar">';
  }else{
   $html.= '<tr class="par">';
  }
$html.=  '<td>'.$ruw['id_seguimiento'].'</td>';
$html.=  '<td>'.$ruw['contenido'].'</td>';
$html.=  '<td style="border: 1px solid; 
padding: 10px; 
width: 300px;
word-wrap: break-word;">'.$ruw['observaciones'].'</td>';
$html.=  '<td>'.$ruw['asistio'].'</td>';
$html.=  '<td>'.$ruw['asesoria_tecnica'].'</td>';
$html.=  '<td>'.Fecha::formato_fecha($ruw['fecha_fin']).' <br> '.Fecha::formato_hora($ruw['hora']).'<br> a '.Fecha::formato_hora($ruw['hora_fin']).'</td>';
 $html.= '</tr>';        
   }
$html.= '</table>';
use Dompdf\Dompdf;
#set_time_limit(27000);
$mipdf = new DOMPDF();
$mipdf->set_paper('A4', 'landascape'); 
$mipdf->load_html($html,'UTF-8');
$mipdf->render();
$output = $mipdf->output();
$mipdf->stream('seguimiento.pdf', array("Attachment" => 0) );

?>

