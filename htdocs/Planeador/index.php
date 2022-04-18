<?php ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../comun/autoload.php");
#require_once("mysql.class.php");
#require_once("materias.class.php");
?>
<script>
$(document).ready(function() {
   mitoogle('#materia');
});
</script>
<?php
$mat=new Materias();
$miplaneacion=new Planeacion();
$academico=new Academico();
$materia=( $academico->consultar_materia($_GET['asignacion']));
$miplaneacion->materia2=$materia[0]->id_asignatura;
$mismaterias=(json_decode($mat->consultar_materias()));
if(isset($_GET['id'])) { 
  $actualizar="si";
  $planeacion=new Planeacion($_GET['id']);  
}else{
  $planeacion=new Planeacion(); 
    $actualizar="no";
  }

   #print_r($planeacion->red);

if(!empty($_GET['asignacion'])){
#echo $_GET['asignacion'];
$_GET['materia']=$materia[0]->id_asignatura;
$_GET['grado']=$materia[0]->nombre_categoria_curso;
$miorden= $miplaneacion->ultimo_plan($_GET['materia'],$_GET['grado']);
if(!empty($miorden[0])){
 $miorden= ($miorden[0]->orden_plan)+1;
}else{
  $miorden=1;
}
}
?>

 <div class="container">
  <div class="row">
    <form action="" method="post">
<input type="hidden" name="id_plantilla" value="<?php if(isset($_GET['id'])){ echo $_GET['id']; } ?>">
    <div  style="margin-top:12%;margin-left:7%">
     <?php require_once 'template/menu.php'; 
     ?>

</div>
    <div class="col-md-12" >
        <label>Materia</label>
<?php
 ?>

        <select class="form-control"  name="materia">

}

<?php       

foreach($mismaterias as $campo => $valor){ ?>
        <option
<?php if(isset($planeacion->materia) and $planeacion->materia==$valor[0] or ($miplaneacion->materia2==$valor[0])){
  echo "selected";
}

?>        value="<?php echo $valor[0]; ?>"><?php echo $valor[1]; ?></option>

<?php } ?>

         </select>

</div>

    <div class="col-md-6">

<?php

$grados = $miplaneacion->consultar_grado();

 ?>

<label>Grado</label>

        <select class="form-control" name="grado">

<?php foreach ($grados as $key => $value) {    ?> 

  <option

<?php 

if(isset($_GET['grado']) and $_GET['grado']==$value[1]){

  echo "selected";

}elseif (isset($planeacion->grado) and $planeacion->grado==$value[1]){

  echo "selected";

} 

?> value="<?php echo $value[1]; ?>"><?php echo $value[1]; ?></option> <?php

 } ?>

         </select>

        </div>

<div class="col-md-3">

    <label>Tiempo(Horas)    </label>

<input class="form-control" placeholder="2" type="number" name="tiempo_plan" value="<?php

if(isset($planeacion->tiempo_plan)){

  echo $planeacion->tiempo_plan;

}else{

 echo "2" ;

}

 ?>">

</div>



<div class="col-md-3"> 

<label>Orden</label>       

<input class="form-control" type="number" name="orden" value="<?php

if(!empty($planeacion->orden)){

    echo $planeacion->orden;

}else{

  if(isset($miorden)){

echo $miorden;

}else{

  echo "2" ;

}

 

}

 ?>">

</div>



<div class="col-md-12">        

               <input style="display: none" class="form-control" type="date" name="fecha_plan" value="<?php

if(!empty($planeacion->fecha_plan)){

    echo $planeacion->fecha_plan;

}else{

     echo date('Y-m-d');

} ?>">

      </div>









<div class="col-md-5">  



 <div  class="control-group" id="contenido">

<label>Contenido</label>

<?php  echo $miplaneacion->datalist("contenido_plan","consultar_contenido"); ?>

<input type="hidden" id="countcontenido" value="1">

<input class="form-control" list="contenido_plan"  autocomplete="off"  autofocus="" class="form form-control" value="<?php  # echo $valor; ?>"  class="input" id="contenido1" name="contenido[]" type="text" placeholder="contenido" />

<button id="add" onclick="adicionar('contenido');" class="btn add-more btn-danger" type="button">+</button>

<?php 



if(!empty($planeacion->contenido_plan)){

$contenido = explode(",",$planeacion->contenido_plan);

foreach ($contenido as $clave => $valor) { 

if($valor<>""){

  echo "<script>

  adicionar('contenido','$valor','si');</script>"; } } }?>

</div>

</div>

<div class="col-md-5" style="display: inline;">        



         <label>Objetivo de clase</label>

<?php  

 $miplaneacion->consultar_objetivos();

 $miplaneacion->datalist("objetivos_plan","consultar_objetivos"); ?>

 <div class="control-group" id="objetivos">

<input  type="hidden"  id="countobjetivos" value="1">

<input list="objetivos_plan"  class="form-control"  id="objetivos1" name="objetivos[]" type="text" placeholder="Objetivos" />

<button  onclick="adicionar('objetivos');" class="btn add-more btn-danger" type="button">+</button>

<?php 

if(!empty($planeacion->objetivos_plan)){

$objetivos = explode(",",$planeacion->objetivos_plan);

foreach ($objetivos as $clave => $valor) { 

if($valor<>""){echo "<script>adicionar('objetivos','$valor','$actualizar');</script>"; } } }?>

</div>



      </div>





   <div class="col-md-12" align="center" style="color:black">        

   <label><a  target="_blank" href="referente/taxonomia.png">

<font  color=#000000> Plan A </font></a></label></div>

<div class="col-md-4">        

        <label>Estrategias</label>

        <?php 

$miplaneacion->datalist2("estrategia","mostrar_todas_estrategias"); ?>

 <div class="control-group" id="estrategia">

<input type="hidden" id="countestrategia" value="1">

<input list="estrategia"  class="form-control"  id="estrategia1" name="estrategia[]" type="text" placeholder="estrategia" />

<button  onclick="adicionar('estrategia');" class="btn add-more btn-danger" type="button">+</button>

<?php 

if(!empty($planeacion->estrategiaa)){

$estrategia = explode(",",$planeacion->estrategiaa);

foreach ($estrategia as $clave => $valor) { 

if($valor<>""){

  echo "<script>adicionar('estrategia','$valor','$actualizar');</script>"; }

   }

 } 

?>

</div>

      </div>

      <div class="col-md-4">        

        <label>Actividades</label>

        <?php echo $miplaneacion->datalist2("Actividad","mostrar_todas_actividad"); ?>



 <div class="control-group" id="Actividad">

<input type="hidden" id="countActividad" value="1">

<input list="Actividad" class="form-control"  id="Actividad1" name="Actividad[]" type="text" placeholder="Actividad" />

<button  onclick="adicionar('Actividad');" class="btn add-more btn-danger" type="button">+</button>

<?php 



if(!empty($planeacion->Actividada)){

$Actividad = explode(",",$planeacion->Actividada);

foreach ($Actividad as $clave => $valor) { 

  if($valor<>""){echo "<script>adicionar('Actividad','$valor','$actualizar');</script>"; } } } 

?>

</div>

</div>

<div class="col-md-4">        

        <label>Recursos</label>

        <?php echo $miplaneacion->datalist2("Recurso","mostrar_todas_recursos"); ?>



 <div class="control-group" id="Recurso">

<input type="hidden" id="countRecurso" value="1">

<input list="Recurso" class="form-control"  id="Recurso1" name="Recurso[]" type="text" placeholder="Recurso" />

<button  onclick="adicionar('Recurso');" class="btn add-more btn-danger" type="button">+</button>

<?php 

if(!empty($planeacion->Recursoa)){
$Recurso = explode(",",$planeacion->Recursoa);
foreach ($Recurso as $clave => $valor) { 
if($valor<>""){echo "<script>adicionar('Recurso','$valor','$actualizar');</script>"; } } } 

?>

</div>

</div>

</hr>



<hr>

<div ><!--para separar -->

    <div align="center" class="col-md-12">        

   <label>Plan B</label></div>

    <div class="col-md-4">        



        <label>Estrategias</label>

   <?php echo $miplaneacion->datalist2("estrategiaplanb","mostrar_todas_estrategias"); ?>

 <div class="control-group" id="estrategiaplanb">

<input type="hidden" id="countestrategiaplanb" value="1">

<input list="estrategiaplanb"  class="form-control" id="estrategiaplanb1" name="estrategiaplanb[]" type="text" placeholder="estrategia" />

<button  onclick="adicionar('estrategiaplanb');" class="btn add-more btn-danger" type="button">+</button>

<?php 

if(!empty($planeacion->estrategiab)){

$estrategiab = explode(",",$planeacion->estrategiab);

foreach ($estrategiab as $clave => $valor) { 

if($valor<>""){echo "<script>adicionar('estrategiaplanb','$valor','$actualizar');</script>"; } } } 

?>

</div>

</div>

<div class="col-md-4">        

        <label>Actividades</label>

        <?php echo $miplaneacion->datalist2("Actividadplanb","mostrar_todas_actividad"); ?>

 <div class="control-group" id="Actividadplanb">

<input type="hidden" id="countActividadplanb" value="1">

<input list="Actividadplanb" class="form-control" id="Actividadplanb1" name="Actividadplanb[]" type="text" placeholder="Actividad" />

<button  onclick="adicionar('Actividadplanb');" class="btn add-more btn-danger" type="button">+</button>

<?php 

if(!empty($planeacion->Actividadb)){

$Actividadb = explode(",",$planeacion->Actividadb);

foreach ($Actividadb as $clave => $valor) { 

if($valor<>""){echo "<script>adicionar('Actividadplanb','$valor','$actualizar');</script>"; } } } 

?>

</div>

</div>

<div class="col-md-4">        

        <label>Recursos</label>

<?php echo $miplaneacion->datalist2("Recursosplanb","mostrar_todas_recursos"); ?>

 <div class="control-group" id="Recursosplanb">

<input type="hidden" id="countRecursosplanb" value="1">

<input list="Recursosplanb" class="form-control" id="Recursosplanb1" name="Recursosplanb[]" type="text" placeholder="Recurso" />

<button  onclick="adicionar('Recursosplanb');" class="btn add-more btn-danger" type="button">+</button>

<?php

if(!empty($planeacion->Recursob)){

$Recursosplanb = explode(",",$planeacion->Recursob);

foreach ($Recursosplanb as $clave => $valor) { 

if($valor<>""){echo "<script>adicionar('Recursosplanb','$valor','$actualizar');</script>"; } } } 

?>

</div>

</div>

<div>

</hr>

<hr>

</hr>

</hr>

                <div class="col-md-12">  

                <label>Observaciones</label>      
                <div id="sample">
 
<textarea name="observaciones_plan"   rows="4" cols="100">
   <?php
if(isset($planeacion->observaciones_plan)){
  echo $planeacion->observaciones_plan;
}

 ?>

</textarea>



                </div>





           
<div class="col-md-5">  

 <div style="display:none"  class="control-group" id="red">

<label>Red</label>

<input type="hidden" id="countred" value="1">

<input class="form-control" list="red"  autocomplete="off"  autofocus="" class="form form-control" value="<?php   echo $valor; ?>"  class="inputred" id="red1" name="red[]" type="text" placeholder="red" />

<button id="addred" onclick="adicionar('red');" class="btn add-more btn-danger" type="button">+</button>

</div>

</div>

<script type="text/javascript">

  function llenarred(red){
          var inputs = $('input[name^=red]');
          var count = inputs.length;
          var inputasignar=count;
var b = document.getElementById(red); 
var estado = b.getAttribute("activo");
if(estado=="off"){
    document.getElementById('red'+inputasignar).value=red;
document.getElementById('red'+inputasignar).setAttribute("red",red);
      document.getElementById('addred').click();
     obj = document.getElementById(red);
b.setAttribute("activo", "on");  
obj.style.backgroundColor='#CCCCCC';
}
if(estado=="on"){
  var totalpara=document.getElementById("countred").value;  
for (var i=1;i<totalpara;i++)
    {
      if(document.getElementById("red"+i).value==red){
        document.getElementById('removered'+i).click();
      }
       }
    b.setAttribute("activo", "off"); 
  obj.style.backgroundColor='#FAFBFC';
}
  }
</script>



<?php 



require_once("../comun/config.php");
#require_once("../comun/autoload.php");
require (SGA_COMUN_SERVER.'/conexion.php');
$sqlmateria='select id_asignatura from asignacion,materia where
asignacion.id_asignatura = materia.id_materia and asignacion.id_asignacion="'.$_GET['asignacion'].'"';
$consultan = $mysqli -> query($sqlmateria) ;
while ($rowa = $consultan ->fetch_assoc()){ 
mired($rowa['id_asignatura'],$parametro_buqueda="",$campo="",$planeacion->red);
}

function mired($id_materia,$parametro_buqueda,$campo,$red=""){

require '../comun/conexion.php';

#require '../comun/autoload.php';

require_once ("../comun/lib/Zebra_Pagination/Zebra_Pagination.php");

 /*

    $records_per_page = 6;

    $pagination = new Zebra_Pagination();

    $pagination->records_per_page($records_per_page);

    $cookiepage="page_motivo";

$funcionjs="buscar();";

$pagination->fn_js_page("$funcionjs");



//$funcionjs="buscar();";

//$pagination->fn_js_page("$funcionjs");



$pagination->cookie_page($cookiepage);

$pagination->padding(false);

unset($_COOKIE["$cookiepage"]);

if (isset($_COOKIE["$cookiepage"])) $_GET['page'] = $_COOKIE["$cookiepage"];

#echo $_GET['page'];

if(empty($_GET['page'])){

  $orden= $pagination->get_page();

}else{

  $orden= $_GET['page'];

}

#echo $orden;

*/





$persona=new Persona($_SESSION['id_usuario']);

require_once '../comun/funciones.php';









$sql="SELECT * FROM `red` WHERE CHARACTER_LENGTH((JSON_SEARCH(`materia_red`, 'all',$id_materia)))>3";

if ($parametro_buqueda!=""){

$sql.= ''; 

$parametro_buqueda_array = explode(" ",$parametro_buqueda);

foreach ($parametro_buqueda_array as $id => $parametro_buquedai){

$tabla='red';

if($campo=="nombre"){ $tabla ='usuario' ;}

if($campo=="nombre_materia"){ $tabla ='materia' ;}

if($campo=="nivel_eductivo"){ $parametro_buquedai = '["'.$parametro_buquedai.'"]' ;

}

$sql.= " and concat(LOWER(".$tabla.".".$campo.")) LIKE '%".mb_strtolower($parametro_buquedai, 'UTF-8')."%' ";

}

}

$consultan = $mysqli -> query($sql) ;

#$pagination->records($consultan->num_rows);



#$sql .=  " LIMIT ".(($orden - 1)*$records_per_page) . ", " .$records_per_page;





#echo $sql;



$consultan = $mysqli -> query($sql) ;

$resultados[] = $consultan->num_rows;



$materia="";

$cat="";

$nivel_educativo_estudiante ="";

if($_SESSION['rol']=="estudiante" or $_SESSION['rol']=="acudiente" ){

$año_lectivo = ano_lectivo();

$nivel_educativo_estudiante = nivel_educativo_de_estudiante($_SESSION['id_usuario'],$año_lectivo);

}

while ($rowa = $consultan ->fetch_assoc()){ 

/*

$niveles = json_decode($rowa['nivel_eductivo']);

 $valor_alto = max($niveles);

*/

if( $_SESSION['rol']=='admin' or  $_SESSION['rol']=='docente'){$pertinencia = 1;}

else {$pertinencia = 0;}

if ($materia!=$rowa['materia_red']){

    $materia=$rowa['materia_red'];

    $estado_materia=true;

}else{

    $estado_materia=false;

}

if($cat!=$rowa['materia_red']){

    $cat=$rowa['materia_red'];

    $estado_cat=true;

}else{

    $estado_cat=false;

}

if($estado_materia==true){ ?>

</div>

<?php }

if($estado_materia==true){?>
<script type="text/javascript" src="../comun/js/funciones.js"></script>

    <div  class="col-sm-12">
        <div class="row"><div>
        <div id="tooglemateria"  style="align:center;background-color:#f2721d;height:5px; "><span style="float:right;opacity:0.7"><?php echo " Resultados Encontrados:".$consultan->num_rows; ?></span></div>
       <p title='clic para desplegar' align="center" onclick="mitoogle('#materia')" >Agregar recursos educativos para <?php
 $materia_nombre=$materia;

 #require_once ("../comun/autoload.php");

$instanciamaterias=new Materias($id_materia);

        echo $instanciamaterias->nombre_materia; ?></p>

    <?php if(!isset($actual)) $actual="#id_".$materia; ?>

    </div>

</div></div>



<?php } ?>

<?php


if($estado_materia==true){ //Controla toogle?>
        <p onclick="mitoogle('#cat_<?php echo $materia.$cat; ?>')" class="Abckids"><?php if(isset($rowa['nombre_categoria_curso'])) echo $rowa['nombre_categoria_curso']; ?></p>



<div id="materia" class="cats" >

<?php }

if($pertinencia ==1){



 ?>



<script>

$(function(){

    $.contextMenu({

        selector: '.context-menu-one', 

        trigger: 'hover',

        delay: 500,

        callback: function(key, options) {

    if(key=="Nuevo RED"){window.location='../red/nuevo_red.php';}

    if(key=="Estadisticas"){window.location='../reportes/RED/estadisticas_red.php';}

    

            var m = "clicked: " + key;

        //    window.console && console.log(m) || alert(m); 

        },

        items: {

            <?php if($_SESSION['rol']=='admin' or $_SESSION['rol']=='docente' ) { ?>

            "Nuevo RED": {name: "Nuevo RED"},

            <?php } ?>

            "Estadisticas": {name: "Estadisticas"},

            "sep1": "---------",

            "Salir": {name: "Salir"}

        }

    });

});

//document.getElementById('txt_buscar_red').focus();

function menu_contextual(red,nombre,formato){

 $.contextMenu({

            selector: '.f_inicio'+red, 

            callback: function(key, options) {

             if(key=="Modificar"){

               

            window.location='../red/nuevo_red.php?id_red='+red;

}



if(key=="Descargar"){

            window.location='../comun/funciones.php?ruta_red='+red;

}



if(key=="materia"){

  window.open('../red/visor_red.php?red='+red, '_blank');



//            window.open = "../red/visor_red.php?red=red,'_blank'" ;

}





             if (key=="Eliminar"){

               var confirmar = window.confirmeliminar2("¿Está seguro que desea eliminar "+nombre+" ?");

    if (confirmar) {

                                 window.location='../comun/funciones.php?elred='+red;



    }

             

             }

            },

            items: {

            "materia": {name: nombre, icon: ""},

            "sep1": "---------",

                "Descargar": {name: "Descargar", icon: "edit"},



                "Modificar": {name: "Modificar", icon: "edit"},

                "Eliminar": {name: "Eliminar", icon: "delete"},

                "sep2": "---------",

                "quit": {name: "Salir", icon: function(){

                    return 'context-menu-icon context-menu-icon-quit';

                }}

            }

        });



        $('.f_inicio').on('click', function(e){

            console.log('clicked', this);

        })    

}



</script>

 <div ondblclick="window.open('../red/visor_red.php?red=<?php echo $rowa['id_red']; ?>', '_blank');" activo="off" onclick="llenarred('<?php echo $rowa['id_red']; ?>');" onContextMenu="menu_contextual('<?php echo $rowa['id_red']; ?>','<?php echo  $rowa['titulo_red']; ?>.<?php echo  $rowa['formato']; ?>');" onclick="location.href = '../red/visor_red.php?red=<?php echo $rowa['id_red']; ?>&formato=<?php echo $rowa['formato']; ?>&enlace=<?php echo $rowa['enlace']; ?>&scorm=<?php echo $rowa['scorm']; ?>' "  <?php

 @session_start();

 if($rowa['responsable']==$_SESSION['id_usuario'] or $_SESSION['id_usuario']=="admin" ){ ?> 

 onContextMenu="menu_contextual('<?php echo $rowa['id_red']; ?>''<?php echo  $rowa['titulo_red']; ?>.<?php echo  $rowa['formato']; ?>');" <?php } ?> style="width:160px;margin-bottom:15px;" id="<?php echo $rowa['id_red']; ?>" name="red" align="center" class="col-sm-2 f_inicio<?php echo $rowa['id_red']; ?>">

   <?php mis_red_favoritos($rowa['id_red'], $rowa['estrellas']); ?>

        <h3 title="<?php echo $rowa['titulo_red'] ; ?>" ><strong><?php   $rowa['nivel_eductivo'] = str_replace("[", "", $rowa['nivel_eductivo']);$rowa['nivel_eductivo'] = str_replace("]", "", $rowa['nivel_eductivo']);

        $rowa['nivel_eductivo'] = str_replace('"','', $rowa['nivel_eductivo']);

        echo Comun::puntos_suspensivos($rowa['titulo_red'],15); ?></strong></h3>

<img style="width:50px;margin-right:40px"  class="img-responsive" align="right" style="margin-top:-5%;max-width: 100%;" width="15%" src="<?php echo   consultar_link_icono($rowa['icono_red']); ?>        

"></img>
   <!--span style="background-size: 40px 40px;margin-top:-10px;margin-left:-20px;"   title = " <?php echo $rowa['descripcion'].'Nivel Educativo:'.$rowa['nivel_eductivo'].', Monedas para ver:'.$rowa['cantidad_estrellas'];  ?>" class="<?php echo $rowa['icono_red']; ?>"/-->
        <?php 
              
                 
        
        if($persona->puntos>=$rowa['cantidad_estrellas'] or $_SESSION['rol']=='admin' or $_SESSION['rol']=='admin'){  } ?>

<div>

</div>

</div> 





<?php

} //fin validación de pertinencia del nivel de formación del recurso para el estudiante y acudiente 

  $acumulador_de_resultados_consulta[]=$resultados;

  } 
  if(isset($_GET['id'])){
  foreach(json_decode($red) as $clave => $valor){
    echo "<script>
    document.getElementById('$valor').click();
    </script>";
    }
}
  
?>

<br>

<div  class="text-center col-sm-12">

    <?php  #  $pagination->render();    ?>

    </div>

</div>
<?php
     
}

?>



</div>

     

                <div class="col-md-12">        

                <label>Estoy Seguro

<input type="checkbox" name="seguro" required/>

                </label></div>

                <div class="col-md-12">        

<input class="btn btn-success" type="submit" name="guardar" value="guardar">

            </div></div>



        </div>

  </div>

</div>

</form>



  <?php
if(!empty($_POST['seguro'])){
#mb_strtolower($_POST,'UTF-8');
if(!empty($_POST['id_plantilla'])){
$planeacion=new Planeacion($_POST['id_plantilla']);
}else{
$planeacion=new Planeacion();  
}
echo "<pre>";
print_r($_POST);
echo "</pre>";

foreach ($_POST['contenido'] as $contenido) {
$planeacion->contenido_plan=$contenido;
if($contenido<>"") $planeacion->insertar_contenido();
}

foreach ($_POST['objetivos'] as $objetivo) {
$planeacion->objetivos_plan=$objetivo;
if($objetivo<>"")  $planeacion->insertar_objetivos();
}

foreach ($_POST['estrategia'] as $estrategia) {
$planeacion->estrategia=$estrategia;
if($estrategia<>"") $planeacion->insertar_estrategia();
}

foreach ($_POST['Actividad'] as $Actividad) {
$planeacion->actividad=$Actividad;
if($Actividad<>"")  $planeacion->insertar_actividad();
}

foreach ($_POST['Recurso'] as $Recurso) {
$planeacion->recurso=strtolower($Recurso);
if($Recurso<>"")  $planeacion->insertar_recursos();
}



foreach ($_POST['estrategiaplanb'] as $estrategia) {
$planeacion->estrategia=strtolower($estrategia);
if($estrategia<>"")  $planeacion->insertar_estrategia();
}

foreach ($_POST['Actividadplanb'] as $Actividad) {
$planeacion->actividad=strtolower($Actividad);
if($Actividad<>"") $planeacion->insertar_actividad();
}

foreach ($_POST['Recursosplanb'] as $Recurso) {
$planeacion->recurso=strtolower($Recurso);
if($Recurso<>"") $planeacion->insertar_recursos();
}

if(!empty($_POST['red'])) {
  $_POST['red'] = array_filter($_POST['red']);
  $planeacion->red= json_encode($_POST['red']);
}







//si el array esta





$planeacion->fecha_plan= $_POST['fecha_plan'];

$planeacion->materia= $_POST['materia'];

$planeacion->grado= $_POST['grado'];

$planeacion->orden= $_POST['orden'];

$planeacion->tiempo_plan= $_POST['tiempo_plan'];

$planeacion->contenido_plan= json_encode($_POST['contenido'],JSON_UNESCAPED_UNICODE);

$planeacion->objetivos_plan= json_encode($_POST['objetivos'],JSON_UNESCAPED_UNICODE);

$plana = new \stdClass();

$plana->estrategia= $_POST['estrategia'] ;

$plana->Actividad= $_POST['Actividad'] ;

$plana->Recurso= $_POST['Recurso'] ;

$planeacion->plana= json_encode($plana,JSON_UNESCAPED_UNICODE);



$planb = new \stdClass();

$planb->estrategiaplanb=($_POST['estrategiaplanb'] );

$planb->Actividadplanb=($_POST['Actividadplanb'] );

$planb->Recursosplanb=($_POST['Recursosplanb'] );

$planeacion->planb= json_encode($planb,JSON_UNESCAPED_UNICODE);

#

if(isset($_POST['observaciones_plan'])){

  $planeacion->observaciones_plan=$_POST['observaciones_plan'];

}

if(isset($_POST['tiempo_plan'])){

 $planeacion->tiempo_plan=$_POST['tiempo_plan'];

}

if(isset($_POST['id_plantilla'])){

  $res=$planeacion->insertar_planeacion($_POST['id_plantilla']);

}else{

  echo  $planeacion->insertar_planeacion();  

}

echo "<script>alert2('registro exitoso');window.location='../cursos/curso.php?asignacion=".$_GET['asignacion']."'</script>";


}




$contenido = ob_get_contents();
ob_clean();
include ("../comun/plantilla.php");

?>