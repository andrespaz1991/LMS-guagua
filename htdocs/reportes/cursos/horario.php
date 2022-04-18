<?php
ob_start();
require("../../comun/autoload.php");
require_once '../../clases/Academico.Class.php';
require_once '../../clases/Curso.Class.php';
#_GET['id_asignacion']="82";
$academico=new academico();
$asignacion=str_replace('"', "", $_GET['id_asignacion']);
$horarios= ($academico->consultar_horario_simple($asignacion));
$datos_curso=$academico->consultar_materia($asignacion)[0];
$nombremateria=$horarios[0]['nombre_materia'];
$html='';
$html.="<h3 align='center'>Horario ".$nombremateria.'</h3> <br>'; 
$html.='<table align="center" border="4"  cellpadding="10" cellspacing="20">';
$html.='<tr><th colspan="9" >'.$horarios[0]['nombre_materia']." Inicio: ".Fecha::formato_fecha($horarios[0]['fecha_inicio']).' hasta : '.Fecha::formato_fecha($horarios[0]['fecha_fin'])."</th>
        </tr>
<tr>
        <th>Hora Inicio</th>
        <th>Hora Fin</th>
        <th>Lunes</th>
        <th>Martes</th>
        <th>Mi&eacute;rcoles</th>
     	<th>Jueves</th>
    	<th>Viernes</th>
      	<th>Sabado</th>
    	<th>Domingo</th>
</tr>";
$diassemana=array();
foreach ($horarios as $key => $horario) {
$html.='<tr>';
$html.='<td>'.Fecha::formato_hora($horario["hora_inicio"]).'</td>';
$html.='<td>'.Fecha::formato_hora($horario["hora_fin"]).'</td>';
$nombre_asignatura=($academico->consultar_materia($asignacion));
$nombre_asignatura=($horarios[0]['nombre_materia']);
$html.='<td>'; if(strtoupper($horario["dia"])=="LUNES"){ $html.=$nombre_asignatura;} $html.='</td>';
$html.='<td>'; if(strtoupper($horario["dia"])=="MARTES"){ $html.=$nombre_asignatura;} $html.='</td>';
$html.='<td>'; if(strtoupper($horario["dia"])=="MIERCOLES"){ $html.=$nombre_asignatura;} $html.='</td>';
$html.='<td>'; if(strtoupper($horario["dia"])=="JUEVES"){ $html.=$nombre_asignatura;} $html.='</td>';
$html.='<td>'; if(strtoupper($horario["dia"])=="VIERNES"){ $html.=$nombre_asignatura;} $html.='</td>';
$html.='<td>'; if(strtoupper($horario["dia"])=="SABADO"){ $html.=$nombre_asignatura;} $html.='</td>';
$html.='<td>'; if(strtoupper($horario["dia"])=="DOMINGO"){ $html.=$nombre_asignatura;} $html.='</td>';
$html.="</tr>";
$diassemana[]=$horario["dia"];  } 
$html.='</table>';
#######################
$html.=' <div style="page-break-after:always;"></div> ';
$html.='
<h2 align="center">Seguimiento cronológico '.$nombremateria.' </h2>
<table border="2" align="center">
<tr>
<th>id</th>
<th>Día</th>
<th>fecha</th>
<th>horas</th>
</tr>';
$id=1;
$horas1=0;
$tminutos=0;
$i=0;
$inicio=DateTime::createFromFormat('Y-m-d',$horarios[0]['fecha_inicio'], new DateTimeZone('America/Bogota'));
while($inicio->format("Y-m-d")<=$horarios[0]['fecha_fin']){
$dias=array("domingo","lunes","martes","miercoles","jueves","viernes","sabado");
$inicio=DateTime::createFromFormat('Y-m-d',$horarios[0]['fecha_inicio'], new DateTimeZone('America/Bogota'));
$inicio->modify('+'.$i++.' day');
if(in_array(mb_strtolower($dias[$inicio->format("w")], 'UTF-8'),$diassemana)){
$html.='<tr>';
$html.='<td>'.$id++.'</td>';
$html.='<td>'.$dias[$inicio->format("w")].'</td>';
$html.='<td>'.Fecha::formato_fecha($inicio->format("Y-m-d")).'('.$inicio->format("d-m-Y").')</td>';
$html.='<td>'; 
foreach($horarios as $clave => $valor){
  if($dias[$inicio->format("w")]==$valor['dia']){
    $cantidad_horas=Fecha::restar_horas($valor["hora_fin"],$valor["hora_inicio"]);
    list($horas2,$minutos1,$segundos1)= Fecha::RestarHoras($valor["hora_fin"],$valor['hora_inicio']); 
     $horas1=$horas1+$horas2;
     $tminutos=$tminutos+$minutos1;
     $html.=($tminutos/60)+$horas1;
  } 
}
$html.='</td></tr>';
}
}
$html.='</table>';


 require_once '../../comun/lib/dompdf/vendor/autoload.php';
 use Dompdf\Dompdf;
 $mipdf= new DOMPDF();
 $mipdf->set_paper('A4', "landscape");
 $mipdf->load_html($html,'UTF-8');
 $mipdf->render();
   if(isset($_GET['guardar'])){
   $output = $mipdf->output();
   file_put_contents('seguimiento/'.$datos_curso->nombre_materia.'/horario/Horario '.$datos_curso->nombre_materia.'.pdf', $output);
   echo "<script>window.close();</script>";
  }else{
    $mipdf->stream(' horario '.$datos_curso->nombre_materia.' .pdf', array("Attachment"=>0));
  }
  