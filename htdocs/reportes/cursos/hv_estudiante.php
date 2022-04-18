<?php
@ob_start();
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require("../../comun/config.php");
require("../../comun/funciones.php");
require_once(RUTA_LOCAL."/comun/autoload.php");
require (SGA_COMUN_SERVER.'/conexion.php');
require_once '../../comun/lib/dompdf/vendor/autoload.php';
$html='';
$rutaImagen = "../../sga-data/foto/Banner.png";
$ruta_foto_estudiante="../../sga-data/foto/";
require_once '../../comun/config.php';
require_once(RUTA_LOCAL."/comun/autoload.php");
require_once RUTA_LOCAL.'/comun/lib/dompdf/vendor/autoload.php';
$institucion=new Institucion($_SESSION['id_institucion']);
$ruta_foto_estudiante="../../sga-data/foto/";
$seguimiento=new Seguimiento();
$academico=new Academico();
#$parametroguardar='1';
$asignacion='85';  
$datos_curso=$academico->consultar_materia($asignacion)[0];
$todos=1;
if(!isset($_GET['inscripcion'])){
  $alumnos=$academico->listar_estudiantes_asignacion($asignacion);  
}else{
  $asignacion=$_GET['asignacion'];
  $alumnos=$academico->listar_estudiantes_asignacion($asignacion,$_GET['inscripcion']);
}

$datos_materia=$academico->consultar_materia($asignacion)[0];
use Dompdf\Dompdf;
set_time_limit(27000);
$parametroguardar=3;

foreach($alumnos as $clave => $documento){ 
  $estudiante=new Persona($documento['id_estudiante']);
  $nombre_estudiante_completo=$estudiante->nombre.' '.$estudiante->apellido;
    $html=reporte_estudiante($documento['id_inscripcion'],$documento['id_estudiante'],$nombre_estudiante_completo,$asignacion,$institucion->logo_institucion,$datos_materia->nombre_materia);
   $mipdf = new DOMPDF();
  $mipdf->set_paper("A4", "landascape"); 
  $mipdf->load_html($html,'UTF-8');
  $mipdf->render();
  $output = $mipdf->output();
 # file_put_contents('seguimiento/'.utf8_decode(trim($nombre_estudiante_completo)).'.pdf', $output);

 if($parametroguardar==1){
  $output = $mipdf->output();
  file_put_contents('seguimiento/'.$datos_curso->nombre_materia.'/actividades/ Observaciones de actividades '.$datos_curso->nombre_materia.'.pdf', $output);
  echo "<script>window.close();</script>";
}

if($parametroguardar==2){
  $output = $mipdf->output();
  $mipdf->stream($nombre_estudiante_completo );
}
if($parametroguardar==3){
  $mipdf->stream($nombre_estudiante_completo , array("Attachment" => 0));
}

}

/*
for ($i=0; $i < 2; $i++) { 
  $html='Hola Mundo';
  $mipdf = new DOMPDF();
  $mipdf->set_paper("A4", "landascape"); 
  $mipdf->load_html($html,'UTF-8');
  $mipdf->render();
  $output = $mipdf->output();
  file_put_contents('seguimiento/hola'.$i.'.pdf', $output);
  #$mipdf->render();
}
*/


function reporte_estudiante($inscripcion,$estudiantes,$nombre,$asignacion,$logo,$materia){

  $html='';
  
  $rutaImagen = "../../sga-data/foto/Banner.png";
  
  $ruta_foto_estudiante="../../sga-data/foto/";
  
  require_once '../../comun/config.php';
  
  require_once(RUTA_LOCAL."/comun/autoload.php");
  
  require_once RUTA_LOCAL.'/comun/lib/dompdf/vendor/autoload.php';
  
  $nombre_estudiante_completo=mb_strtoupper($nombre);
  
  $estilo="<style>
  
    #footer { position: fixed;}
  
    @import url(https://fonts.googleapis.com/css?family=Roboto);
  
    body {
  
        font-family: 'Roboto', sans-serif;
  
      }
  
    .separator {
  
       
  
        border: 1px solid #C3C3C3;
  
      }
  
      #card {
  
        border-radius: 5px;
  
        position: absolute;
  
  
  
      
  
        -webkit-box-shadow: 10px 10px 93px 0px rgba(255, 0, 0, 0.75);
  
        -moz-box-shadow: 10px 10px 93px 0px rgba(0, 0, 0, 0.75);
  
        box-shadow: 10px 10px 93px 0px rgba(0, 0, 0, 0.75);
  
      }
  
      .right {
  
    
  
      }
  
      .thumbnail {
  
        float: left;
  
        position: relative;
  
        left: 30px;
  
        top: -30px;
  
        height: 320px;
  
        width: 630px;
  
      }
  
      p {
  
        text-align: justify;
  
        font-size: 0.95rem;
  
        color: #4B4B4B;
  
      }
  
      .footer {
  
        
  
      }
  
      #title, span {display: inline;}
  
      /** Define the margins of your page **/
  
      @page {
  
          margin: 100px 25px;
  
      }
  
  
  
      header {
  
          
  
  
  
          /** Extra personal styles **/
  
        
  
      }
  
  
  
      footer {
  
          position: fixed; 
  
       }
  
    </style>";
  
    $html= $estilo;
  
    $html.='<h3 style="fon-size:40px;color:white;position:absolute;margin-top:24%;margin-left:5%" >OBSERVACIONES DE PRACTICANTE (INSTITUTO JOSÉ MUTIS) </h3>';
  #$materia; 
    $html.='<p style="fon-size:12px;color:black;position:absolute;margin-top:33%;margin-left:65%" ></p>';
  #|'.Fecha::formato_fecha(date('Y-m-d')).'|;
    $rutaImagen2l = "mutis.png";
      $contenidoBinario2l = file_get_contents($rutaImagen2l);
        $imagenComoBase642l = base64_encode($contenidoBinario2l);
  #      $html.="<img style='width:25%;position:absolute;margin-top:23%;margin-left:60%' src='data:image/jpeg;base64,$imagenComoBase642l'>";
  
   $contenidoBinario = file_get_contents($rutaImagen);
  
  $imagenComoBase64 = base64_encode($contenidoBinario);
  
    $html.="<img style='margin-top:-12%;margin-left:-8%' src='data:image/jpeg;base64,$imagenComoBase64'>";
  
    $html.='<h3 STYLE="font-family:"Tahoma (Títulos)";margin-left:7.7cm;margin-top:2%;font-size:32px;font-weight: bold;">'.$nombre_estudiante_completo.'</h3></p><br>';
  
    $rutaImagen2 = "../../sga-data/foto/footer.png";
  
    $contenidoBinario2 = file_get_contents($rutaImagen2);
  
      $imagenComoBase642 = base64_encode($contenidoBinario2);
  
      require_once(RUTA_LOCAL."/comun/autoload.php");
  
      $academico=new Academico();
  
     $html.=$academico->reporte_actividades($estudiantes,$asignacion);
  
     $html.='<footer>
  
    '."<img style='margin-top:83%;margin-left:-7%' src='data:image/jpeg;base64,$imagenComoBase642'>".'
  
  </footer>';
  
  $html.=' <div style="page-break-after:always;"></div> ';
  
    return $html;
  
  }
?>  

