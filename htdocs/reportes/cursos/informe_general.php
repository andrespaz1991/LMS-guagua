<?php @ob_start();  ?><h1 align="center">Reportes de asignatura</h1>
<?php
if(isset($_GET['borrar'])){
require("../../comun/config.php");
require_once(RUTA_LOCAL."/comun/autoload.php");
$comun = new Comun();
$comun->eliminar_directorio('seguimiento/');
mkdir("seguimiento/", 0700);
}

$informes[]="Planes"; 
$informes[]="Asistencia"; 
$informes[]="Valoraciones"; 
$informes[]="Horario"; 
$informes[]="Edunotas"; 
$informes[]="Anotaciones";
$informes[]="Estadistica"; 
?>
<form id="reporte" method="post" action="">
  <input type="hidden" name="asignacion" value="<?php echo $_GET['id_asignacion'] ?>"/>
<?php
foreach($informes as $id =>$nombre){ ?>
<div class="form-check">
  <input class="ch" class="form-check-input" type="checkbox" name="<?php echo $nombre;?>" value="" id="<?php echo $id ?>" checked>
  <label class="form-check-label" for="flexCheckChecked">
    <?php echo $nombre;?>
  </label>
  
</div>
<?php
}
?>
<div class="form-check">
<input class="form-check-input" type="checkbox" required name="seguro" value="si">
<label class="form-check-label" for="flexCheckChecked">Estoy seguro</label>
</div>
<input type="submit" value="guardar">
</form>
<?php
if(isset($_POST['seguro'])){
$asignacion=$_POST['asignacion'];
require("../../comun/config.php");
require_once(RUTA_LOCAL."/comun/autoload.php");
$academico=new Academico();
$datos_curso=$academico->consultar_materia($asignacion)[0];
$ruta="seguimiento/".$datos_curso->nombre_materia;
if(!is_dir($ruta)) {
  mkdir($ruta,0700);
}
if(isset($_POST['Planes'])){
  mkdir("seguimiento/".$datos_curso->nombre_materia.'/planes', 0700);
  echo "<script>
window.open('../../Planeador/reporte_todos.php?guardar&id_materia=".$datos_curso->id_curso."', '_blank');
</script>";
##########################
$plan=new Planeacion();
#$_GET['id_asignacion']="82";
$id_asignatura=$asignacion;
$datos_materia= $academico->consultar_materia($id_asignatura);
#mkdir("seguimiento/".$datos_materia[0]->nombre_materia.'/planes', 0700);
$info= $plan->recursos_plan($datos_materia[0]->id_curso);
$contador=0;
foreach($info as $clave => $valor){
  $contador++;
  #echo "<pre>";
  #echo "Contenido: ";
  $contenido=(json_decode($valor->contenido_plan,true)[0]);
  $ruta="seguimiento/".$datos_materia[0]->nombre_materia."/planes/".$contador.'.'.$contenido;
  $ruta2='../reportes/cursos/'.$ruta.'/'.$contenido.'.pdf';
    echo "<script>
  window.open('../../Planeador/reporte_todos.php?ruta=".$ruta2."&guardar&id_plan=".$valor->id_plan."', '_blank');
   </script>";

  mkdir("seguimiento/".$datos_materia[0]->nombre_materia.'/planes/'.$contador.'.'.$contenido, 0700);
  #print_r(json_decode($valor->contenido_plan,true)[0]);

  #echo "<br> Recursos: ";
  $recursos=json_decode($valor->red,true);
  if(empty($recursos)){
    #echo "no hay recurso";
  }else{
    foreach( $recursos as $key => $value){
      $red=new Red($value);
      if($red->adjunto=="no"){
        $file = fopen($ruta.'/'.$red->titulo_red.".txt", "w");
        fwrite($file, $red->enlace . PHP_EOL);
        fclose($file);
       # echo "<pre>";
       # print_r($red->enlace);
       # echo "</pre>";   
      }else{

       # echo "descargar";
      }
     
    }
  }

}
##########################
}
if(isset($_POST['Asistencia'])){
  if(!is_dir("seguimiento")) {
    mkdir("seguimiento",0700);
  }
  mkdir("seguimiento/".$datos_curso->nombre_materia.'/estudiantes', 0700);
  echo "<script>
window.open('usuarios.php?guardar&id_asignacion=".$asignacion."', '_blank');
</script>";
}

if(isset($_POST['Valoraciones'])){
  mkdir("seguimiento/".$datos_curso->nombre_materia.'/actividades', 0700);
  echo "<script>
window.open('../informe_valorativo.php?guardar&id_asignacion=".$asignacion."', '_blank');
</script>";
}

if(isset($_POST['Horario'])){
  mkdir("seguimiento/".$datos_curso->nombre_materia.'/horario', 0700);
  echo "<script>
window.open('horario.php?guardar&id_asignacion=".$asignacion."', '_blank');

</script>";
}

if(isset($_POST['Edunotas'])){
  mkdir("seguimiento/".$datos_curso->nombre_materia.'/anotaciones', 0700);
  echo "<script>
window.open('hv_estudiante.php?guardar=1&asignacionw=".$asignacion."', '_blank');

</script>";
}

if(isset($_POST['Anotaciones'])){
  echo "<script>
window.open('reporte_edunotas.php?guardar&asignacion=".$asignacion."', '_blank');

</script>";
}
header('Location: informe_general.php');
}


$carpeta = @scandir('seguimiento');
if (count($carpeta) > 2){
  require_once("../../comun/config.php");
require_once(RUTA_LOCAL."/comun/autoload.php");

$zip = new ZipArchive();
$comun=new Comun();
if(isset($_GET['id_asignacion'])){
  $asignacion=$_GET['id_asignacion'];
}else{
  $asignacion=$_POST['asignacion'];
}

require_once("../../comun/config.php");
require_once(RUTA_LOCAL."/comun/autoload.php");
$academico=new Academico();
$datos_curso=$academico->consultar_materia($asignacion)[0];

$dir = 'seguimiento/'.$datos_curso->nombre_materia.'/';
$nombre_archivo=$datos_curso->nombre_materia.".zip";
$rutaFinal = "../resultado/";
if(!file_exists($rutaFinal)){
  mkdir($rutaFinal);
}
$archivoZip = $nombre_archivo;
if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
  $comun->agregar_zip($dir, $zip);
  $zip->close();
  rename($archivoZip, "$rutaFinal/$archivoZip");
  
  if (file_exists($rutaFinal. "/" . $archivoZip)) {
    echo "Proceso Finalizado!! <br/><br/>
                Descargar: <a href='$rutaFinal/$archivoZip'>$archivoZip</a>&nbsp<a href='informe_general.php?borrar'>Borrar</a>";
  } else {
    echo "Error, archivo zip no ha sido creado!!";
  }
}

}else{
  echo 'VACIO';
}



/*
require_once("../../comun/config.php");
require_once(RUTA_LOCAL."/comun/autoload.php");
$comun=new Comun();
$ruta='seguimiento/';
$carpeta= $comun->listar_directorios_ruta($ruta);
echo $comun->descargar_zip('seguimiento/resultado/',"ofimatica","seguimiento/"); 

/*
require_once("../../comun/config.php");
require_once(RUTA_LOCAL."/comun/autoload.php");
$comun=new Comun();
echo $comun->descargar_zip('example2/',"copia.zip","seguimiento/"); 
*/



###################### comprimir
// Include and initialize ZipArchive class
#require_once 'ZipArchiver.class.php';


######################
$contenido = ob_get_contents();
ob_clean();
include ("../../comun/plantilla.php");
?>