<?php
ob_start();
require("../comun/autoload.php");
?>

<form method="post" action="">
  <div class="form-group">
    <label for="exampleInputEmail1">Mensaje</label>
    <textarea class="form-control rounded-0" id="mensaje" name="mensaje" rows="10"></textarea>  
</div>
  <button type="submit" name="enviar" class="btn btn-primary">Enviar</button>
</form>

<?php
if(isset($_POST)){
$academico=new Academico();
$academico->id_asignacion="85";
$lista_Estudiantes= $academico->estudiantes_de_una_asignacion();
foreach($lista_Estudiantes as $clave => $estudiante){
#$data_estudiante=new Persona();
  $mesagge='';
  $cadena = explode(" ",$_POST['mensaje']);
    foreach ($cadena as $id => $dato){
      $mesagge.=$dato.' ';
      $dato= str_replace(" ","",$dato);

        if($dato[0]=="#"){
            $dato= str_replace("#","", $dato);
            $mesagge.=' '.$estudiante[$dato].' ';
            #echo "<br>".$dato;
        }     
        $mesagge= str_replace($dato,"",$mesagge);
        $mesagge= str_replace("#","",$mesagge);

    }
  echo "<pre>";
  print_r($mesagge);
  echo "</pre>";
}
}



$contenido = ob_get_contents();
ob_clean();
include ("../comun/plantilla.php");
?>