<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
@session_start();
unset($_SESSION['barra_busqueda']);
#require_once("../comun/config.php");
#require_once("../comun/funciones.php");
require("../comun/conexion.php");

setcookie('miruta',$_SERVER["QUERY_STRING"]);

if(isset($_GET['asignacion'])){

setcookie('asignacion',$_GET['asignacion']);

}

?>

<script type="text/javascript">

$(function() {

mitoogle('#lista_docentes');  

});

</script>

<?php

if ( isset($_GET['asignacion']) and  $_GET['asignacion'] != ""){

$_GET['asignacion']=mysqli_real_escape_string($mysqli,$_GET['asignacion']);

 $SqlAsignacion='select * from categoria_curso,asignacion,materia,usuario where

asignacion.id_categoria_curso=categoria_curso.id_categoria_curso and

asignacion.id_docente = usuario.id_usuario and 

asignacion.id_asignatura =materia.id_materia and';

if(!isset($_GET['categoria'])){

$SqlAsignacion.=' asignacion.id_asignacion="'.$_GET['asignacion'].'"';

}else {

$SqlAsignacion.=' categoria_curso.id_categoria_curso="'.$_GET['categoria'].'"';

}

$consultaAsignacion =$mysqli->query($SqlAsignacion);

 while($RowAsignacion= $consultaAsignacion -> fetch_assoc()){

 $portada_asignacion =$RowAsignacion['portada_asignacion'];

$nombre_cate = $RowAsignacion['nombre_categoria_curso'];

#setcookie('nombre_cate',$RowAsignacion['nombre_categoria_curso']);

$curso =$RowAsignacion['nombre_materia'];

#setcookie('curso',$RowAsignacion['nombre_materia']);



$cate =$RowAsignacion['id_categoria_curso'] ;



#setcookie('cate',$RowAsignacion['id_categoria_curso']);



setcookie('asignacion',$RowAsignacion['id_asignacion']); 



 $_SESSION['docente'] =$RowAsignacion['foto']; 



 $_SESSION['nombre_docente'] =$RowAsignacion['nombre'].' '.$RowAsignacion['apellido']; 



                     } 



     



                     }



?>



 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <link rel="stylesheet" href="/resources/demos/style.css">

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script>

  $( function() {

    $( "#draggable" ).draggable();

    $( "#droppable" ).droppable({

      drop: function( event, ui ) {

        $( this )

        var req = new XMLHttpRequest();

req.open("GET", "inscribir_estudiante.php?estudiante="+$("#draggable").attr("value")+"&asignacion=<?php echo $_GET['asignacion'];  ?>", false);



req.send();



//alert(req.responseText);



alert($("#draggable").attr("value")+" Registro exitoso");



buscar(); 



      }



    });



  } );



  </script>



<?php



function buscar_estudiante($datos="",$asignacion="",$cate=""){ 



require("../comun/conexion.php");



require_once("../comun/funciones.php");



require_once ("../comun/lib/Zebra_Pagination/Zebra_Pagination.php");







$records_per_page = 4;

$pagination = new Zebra_Pagination();

$pagination->records_per_page($records_per_page);

$cookiepage="page_motivo";

$funcionjs="buscar();";

$pagination->fn_js_page("$funcionjs");

$pagination->cookie_page($cookiepage);

$pagination->padding(false);



if (isset($_COOKIE["$cookiepage"])) $_GET['page'] = $_COOKIE["$cookiepage"];





#if (isset($_COOKIE["$cookiepage"])) $_GET['page'] = $_COOKIE["$cookiepage"];



$sql = 'SELECT * FROM inscripcion, usuario ';

$datosrecibidos = $datos;

$datos = explode(" ",$datosrecibidos);

$cont =  0;

$sql .= ' WHERE inscripcion.id_asignacion= "'.$_COOKIE['asignacion'].'" ';

$consutasinsql=$mysqli->query($sql);

$cantidad=$consutasinsql->num_rows;

foreach ($datos as $id => $dato){

$sql .= ' and

 usuario.id_usuario = inscripcion.id_estudiante and concat(usuario.id_usuario," ", usuario.clave," ", LOWER ( usuario.nombre )," ", LOWER (usuario.apellido)," ", LOWER (usuario.direccion)," ",usuario.direccion," ", LOWER ( usuario.direccion)," ") LIKE LOWER ( "%'.utf8_decode($dato).'%" )  ';

$consuta=$mysqli->query($sql);

$CantidadEstudiantes=$consuta->num_rows;

$pagination->records($consuta->num_rows);

$cont ++;

if (count($datos)>1 and count($datos)<>$cont){

$sql .= "";

}

}

if (isset ($_COOKIE['asignacion'])) { $miasignada =$_COOKIE['asignacion'] ; }

else {

  $_COOKIE['asignacion'] =$_COOKIE['asignacion'] ; }

$sql .=  '  ORDER BY usuario.apellido, usuario.nombre asc  LIMIT ';

if (isset($_COOKIE['numeroresultados']) and $_COOKIE['numeroresultados']!="") $sql .=$_COOKIE['numeroresultados'];

$sql .=  "  " . (($pagination->get_page() - 1) * $records_per_page) . ", " . $records_per_page;

#else $sql .= "100000";

#echo $sql;

$consulta = $mysqli->query($sql);

$cantidad= $consulta->num_rows;

?>

<center><p><?php echo $cantidad." Resultados de ".$CantidadEstudiantes." del total de  en página ".$pagination->get_page(); ?></p></center>



    <div class="table-responsive">       



<script>



$(function(){



    function createSomeMenu() {



        return {



            callback: function(key, options) {



if(key=="Nuevo"){window.location='actividad.php?asignacion='+ObtenerGetJavascript('asignacion'); }



if(key=="Modificar"){window.location='modificar_curso.php?asignacion='+ObtenerGetJavascript('asignacion'); } 



if(key=="Estudiantes del curso"){window.location='estudiante_curso.php?asignacion='+ObtenerGetJavascript('asignacion'); } 



if(key=="Reporte Valorativo"){window.open('../reportes/informe_valorativo.php?id_asignacion='+ObtenerGetJavascript('asignacion'),'_BLANK'); } 



if(key=="Estadisticas"){window.open('../reportes/promedio_estudiantil.php?asignacion='+ObtenerGetJavascript('asignacion'),'_BLANK'); } 
if(key=="reportes"){window.open('../reportes/cursos/informe_general.php?id_asignacion='+ObtenerGetJavascript('asignacion'),'_BLANK'); } 


            },



            items: {



               <?php @session_start(); if($_SESSION['rol']=="admin" or  $_SESSION['rol']=="docente") { ?>



                "Nuevo": {name: "Nueva Actividad"},



            "Modificar": {name: "Modificar Curso"},



<?php } ?>



                "Estudiantes del curso": {name: "Estudiantes del curso"},



                 "Estudiantes del curso": {name: "Estudiantes del curso"},



                 "Reporte Valorativo": {name: "Reporte Valorativo"},



                 "Estadisticas": {name: "Estadisticas"},

                 "reportes": {name: "reportes"},



            }



        };



    }



    $('.context-menu-one').on('mouseup', function(e){



        var $this = $(this);



        $this.data('runCallbackThingie', createSomeMenu);



        var _offset = $this.offset(),



            position = {



                x: _offset.left + 5, 



                y: _offset.top + 5



            }



        setTimeout(function(){ $this.contextMenu(position); }, 1000);



    });



    // setup context menu



    $.contextMenu({



        selector: '.context-menu-one',



        trigger: 'none',



        build: function($trigger, e) {



            e.preventDefault();



            return $trigger.data('runCallbackThingie')();



        }



    });



});



</script>        



        



        



        



<table class="table" border="1" width="90%" id="tb<?php //echo $nombretabla ?>">



<thead>

<th>#</th>

<th>Foto</th>

<th>Estudiante</th>

<th>Dirección</th>

<th>Acudientes</th>



<!--th>Foto</th-->



<!--th>Modificar</th-->







<th> <?php if($_SESSION['rol']<>"estudiante") { echo 'Retirar'; } else { echo 'Mensaje'; } ?> </th>



<?php if($_SESSION['rol']<>"estudiante") {  ?>



<th>



  <form   action="<?php echo SGA_REPORTES_URL.'/cursos/usuarios.php';?>" method="post"  target="_BLANK">



    <input  type="hidden" name="id_asignacion" value="<?php echo $_COOKIE['asignacion']; ?>"/>


    <input  title="Listado de Estudiantes" width="50px" type="image" name="" src="<?php echo SGA_COMUN_IMAGES.'/pdf.png'?>" value="Reporte"/>



    </form>



<form action="<?php echo SGA_REPORTES_URL.'/cursos/estudiantes_curso.php';?>" method="post">



  <input  type="hidden" name="id_asignacion" value="<?php echo $_COOKIE['asignacion']; ?>"/>



  <input width="60px" type="image" src="<?php echo SGA_COMUN_IMAGES.'/xls.png'?>">



</form>



    </th> <?php } ?>



</tr>



</thead><tbody>



<?php



$mistooltip = "";

$contadorestudiante=1;

while($row=$consulta->fetch_assoc()){ ?>

<tr

<?php if($row['estado_inscripcion']=='Retirado'){ echo "style='background-color:#E5E7E9;'"; } else { ?>

class="estudiante_drop" id_estudiante="<?php echo $row['id_estudiante']?>" fn-droppable="asignar_acudiente" fn-confirm="Esta seguro que desea asignar el acudiente [nombre_acudiente]([id_acudiente]) a el estudiante [nombre_estudiante]([id_estudiante])" dranganddrop-datos='{"id_estudiante":"<?php echo $row['id_estudiante']?>","nombre_estudiante":"<?php echo $row['apellido'].' '.$row['nombre']; ?>"}' <?php } ?> >

<td><?php echo $cont++ ?></td>

<td><img height="80" src="<?php echo READFILE_URL.'/foto/'.($row['foto']); ?>"></td>





<td>



<?php echo $row['apellido'].' '.$row['nombre'].'<br>'.$row['id_estudiante'];?></td>



<td><?php



if($row['direccion']=="null" or $row['direccion']=="NULL")



{



echo "";  



}else{



echo $row['direccion'];



}



?></td>



<td><?php



consultar_acudientes_estudiante($row['id_estudiante']);



//$sql_padre = 'select * from '



if($row['telefono']=="null" or $row['telefono']=="NULL" )



{



echo "";  



}else{



echo "<br>Teléfono ".$row['telefono'];



}







?></td>



<?php if($_SESSION['rol']<>"estudiante") { ?>







<td>



    <?php if($row['estado_inscripcion']<>'Retirado'){ ?>



<input type="image" src="<?php echo SGA_COMUN_URL.'/';?>img/eliminar.png" onClick="confirmeliminar2('estudiante_curso.php',{'del':'<?php echo $row['id_inscripcion'];?>','dol':'<?php echo $_COOKIE['miruta'];?>'},'<?php echo $row['apellido'].' '.$row['nombre'];?>','Esta seguro que desea retirar de este curso a ');" value="Eliminar">



</td><?php } } ?>



<td>



    <?php @session_start(); if($_SESSION['rol']<>"estudiante") {  ?>



<form  id="formModificar" name="formModificar" method="post" action="../usuario/usuario.php" ENCTYPE="multipart/form-data">



<input name="cod" type="hidden" id="cod" value="<?php echo $row['id_estudiante']; ?>">



<input name="u" type="hidden" id="u" value="<?php echo $_COOKIE['asignacion']; ?>">



<input type="hidden" name="submit" id="submit" value="Modificar"><button type="submit" class="btn btn-success">Modificar</button>



</form>















    <form target="_BLANK" method="post" action="<?php echo SGA_REPORTES_URL.'/cursos/usuarios.php';?>">



    <input  type="hidden" name="id_asignacion" value="<?php echo $_COOKIE['asignacion']; ?>"/>



<input type="hidden" name="id_inscripcion" value="<?php echo $row['id_inscripcion'];?>"/>



<input  width="50px" title="Información de <?php echo $row['nombre'].' '.$row['apellido'];?>" type="image" src="<?php echo SGA_COMUN_IMAGES.'/pdf.png'?>" name="" value="Reporte"  target="_blank"/>











</form>



<?php }



else { ?>



    <form method="post" action="<?php echo SGA_MENSAJE_URL ?>/redactar.php"; target="_blank">



<input type="hidden" name="responder_a" value="<?php echo $_GET['id_usuario'];?>">



<input type="hidden" name="responder_n" value="<?php echo $row['nombre']." ".$row['apellido'];?>">



<input type="hidden" name="responder_mensaje" value="">



<input  width="50px" title="" type="image" src="<?php echo SGA_COMUN_IMAGES.'/png/mensaje.png'?>" name="" />



</form>



<?php }















?>



</td>



</tr>



<?php 



$mistooltip .= '$( "#foto_est_'.$row['id_estudiante'].'" ).tooltip({ content: "<img height=\'120\' src=\''.READFILE_URL.'/foto/'.validarfoto($row['foto']).'\'></img>"});';



}//fin while



?>



</tbody>



</table>



<div class="text-center">



    <?php    $pagination->render2();    ?>



    </div>



</div>



<script>



function cargar_tooltips(){



<?php echo $mistooltip; ?>



}



</script>



<?php



}//fin function buscar



if (isset($_GET['buscar'])){



if(isset($_GET['cate'])) $res = $_GET['cate'];



buscar_estudiante($_POST['datos'],$var="");



exit();



}



if (isset($_POST['del'],$_POST['dol'])){



//Instrucción SQL que permite eliminar en la BD



 echo $sql = 'update  inscripcion set estado_inscripcion="Retirado" where id_inscripcion="'.$_POST['del'].'"';



//Se conecta a la BD y luego ejecuta la instrucción SQL



if ($eliminar = $mysqli->query($sql)){



//Validamos si el registro fue eliminado con éxito



?>



<script type="text/javascript">



alert ('Estudiante ha sido retirado del curso') ;  



</script>



<meta http-equiv="refresh" content="0; url=estudiante_curso.php?<?php echo $_POST['dol'] ;  // $_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"]; ?>" />



<?php



  



}else{ 



  



?>



<script type="text/javascript">



alert ('Verifique su información') ;  



</script>



<meta http-equiv="refresh" content="0; url=estudiante_curso.php?<?php echo $_POST['dol'] ;  // $_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"]; ?>" />



<?php



  



}



}



?>



<div id="droppable">Matricular estudiante</div>



<div  class="jumbotron" <?php if(isset($portada_asignacion) and $portada_asignacion<>""){ ?>



style="



background-image: url('<?php echo SGA_CURSOS_URL.'/'.  $portada_asignacion; ?>');no-repeat left center;



     -webkit-background-size: cover;



     -moz-background-size: cover;



     -o-background-size: cover; 



"



<?php } ?>> <form method="post" action="<?php echo SGA_MENSAJE_URL ?>/redactar.php"; target="_blank">



<input type="hidden" name="responder_a" value="<?php @session_start;  echo $_SESSION['id_usuario'];?>">



<input type="hidden" name="responder_n" value="<?php echo $_SESSION['nombre_docente'];?>">



    <input  type="image" style="margin-left: 900px;



    width: 10%;



    position: absolute;



    border-radius: 80%;"  title="Enviar Mensaje al docente <?php echo  $_SESSION['nombre_docente']; ?>"  src="<?php echo READFILE_URL.'/foto/'.$_SESSION['docente']; ?>" ></form>



  <input id="opciones_cursos2"  type="button" value="Opciones"  class="btn btn-warning context-menu-one" name=""/>



  </form>



    



  <div class="container text-center">



    <h1 style="font-size:45px;   <?php if(isset($portada_asignacion) and $portada_asignacion<>""){ 



echo 'opacity:0.01'; } ?>" 



    id="estudiantes_curso" class="fip" 



  



    > <?php



     $curso;



     require_once ('../comun/funciones.php');



    if (isset($curso )) { echo puntos_suspensivos(strtoupper($curso.' ('.$nombre_cate.')')); } 



if (isset($_POST['curso'])) { echo $_POST['curso'] ; }?> </h1>      



  </div>



</div>



<div class="container-fluid bg-3 text-center">    



 <div class="row">



<section>



<p align="center">



<br>



<p>



 



<?php



#print_r($_POST['submit']);



if (isset($_POST['submit'])){



    



switch ($_POST['submit']){



case "Inscribir":



    $fecha = date('d-m-Y');



//recibo los campos del formulario proveniente con el método POST



  $sql00 = 'select * from inscripcion,estudiante where inscripcion.id_estudiante ="'.$_POST['id_estudiante'].'" and inscripcion.id_asignacion = "'.$_COOKIE['asignacion'].'" ';



   $sql = 'INSERT INTO `inscripcion`(`id_estudiante`, `id_asignacion`, `fecha_inscripcion`) VALUES ("'.$_POST['id_estudiante'].'","'.$_COOKIE['asignacion'].'","'.$fecha.'")';



require '../comun/conexion.php';

$consulta00 = $mysqli -> query ($sql00);

if ($raw =$consulta00 ->fetch_assoc() ){

    echo "el estudiante ya se encuentra inscrito en este curso"; ?>



<meta http-equiv="refresh" content="2; url=estudiante_curso.php?<?php echo $_COOKIE['miruta'] ;  // $_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"]; ?>" />



<?php



                                        }

else {

require '../comun/conexion.php';

 $_POST['id_estudiante'] ;

 // echo $sql;

if ($insertar = $mysqli->query($sql)) {

//Validamos si el registro fue ingresado con éxito

echo 'Registro exitoso'; ?>

<meta http-equiv="refresh" content="2; url=estudiante_curso.php?<?php echo $_COOKIE['miruta'] ;  // $_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"]; ?>" />







  <?php



}



else{



echo 'Registro fallido'; ?>



<meta http-equiv="refresh" content="2; url=estudiante_curso.php?<?php echo $_COOKIE['miruta'] ;  // $_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"]; ?>" />







<?php



}



}



break;



case "Nuevo":



case "Nuevo":



echo '<form id="form1" name="form1" method="post" action="estudiante_curso.php">



<h1>Inscribir</h1>';?>



 <div class="form-group">



   <div class="col-xs-12">



<label>Estudiante</label>



 <input class="form-control" autocomplete ="off"   list="suggestionList" id="answerInput">



<datalist id="suggestionList">



  <?php 



require '../comun/conexion.php';



$sqles = 'select * from estudiante';



$consultaes = $mysqli -> query ($sqles);



while ($rowes = $consultaes ->fetch_assoc()) {?>

<option label ="<?php echo $rowes['SELECT * FROM inscripcion,`estudiante`, usuario WHERE inscripcion.id_asignacion= "151" and usuario.id_usuario = estudiante.id_estudiante and estudiante.id_estudiante = inscripcion.id_estudiante and concat(estudiante.id_estudiante," ", estudiante.id_usuario," ", usuario.clave," ", LOWER ( usuario.nombre )," ", LOWER (usuario.apellido)," ", LOWER (usuario.direccion)," ",usuario.direccion," ", LOWER ( usuario.direccion)," ") LIKE LOWER ( "%%" ) and estudiante.id_categoria_curso = "1" ORDER BY estudiante.id_estudiante LIMIT 10'] ; ?>" data-value="<?php echo $rowes['id_estudiante'] ; ?>" ><?php echo $rowes['nombre'].' '.$rowes['apellido'] ; ?></option>



<?php 



}



?>



</datalist>



</div></div>



<input type="hidden" name="id_estudiante" id="answerInput-hidden"></p>



<script>



    document.querySelector('input[list]').addEventListener('input', function(e) {



    var input = e.target,



        list = input.getAttribute('list'),



        options = document.querySelectorAll('#' + list + ' option'),



        hiddenInput = document.getElementById(input.id + '-hidden'),



        inputValue = input.value;



    hiddenInput.value = inputValue;



    for(var i = 0; i < options.length; i++) {



        var option = options[i];



        if(option.innerText === inputValue) {



            hiddenInput.value = option.getAttribute('data-value');



            break;



        }



    }



});



</script>







<?php



echo '<p><input type="submit" name="submit" id="submit" value="Inscribir"></p>



</form>';



break;



case "Actualizar":



//recibo los campos del formulario proveniente con el método POST



$cod = $_POST['cod'];



//Instrucción SQL que permite insertar en la BD sig_rol_documento`, `nom_rol_documento



 $sql = "UPDATE usuario SET id_usuario='".$_POST['id_estudiante']."', nombre='".$_POST['nombre']."', apellido='".$_POST['apellido']."', direccion='".$_POST['direccion']."' WHERE  id_usuario = '".$cod."';";



//echo $sql;



//Se conecta a la BD y luego ejecuta la instrucción SQL



if ($actualizar = $mysqli->query($sql)) {



//Validamos si el registro fue ingresado con éxito



echo 'Modificación exitosa';







?>



<meta http-equiv="refresh" content="2; url=estudiante_curso.php?<?php echo $_COOKIE['miruta'] ;  // $_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"]; ?>" />







<?php



}else{



echo 'Modificacion fallida';?>



<meta http-equiv="refresh" content="2; url=estudiante_curso.php?<?php echo $_COOKIE['miruta'] ;  // $_SERVER['PHP_SELF'].'?'.$_SERVER["QUERY_STRING"]; ?> " />



<?php



}?>



<?php



break;



case "Modificar":



$sql = "SELECT * FROM `usuario` WHERE id_usuario ='".$_POST['cod']."' Limit 1"; 



$consulta = $mysqli->query($sql);



//echo $sql;



if($row=$consulta->fetch_assoc())



{



echo '<form id="form1" name="form1" method="post" action="estudiante_curso.php">



<h1>Modificar </h1>';



echo '<p><input name="cod" type="hidden" id="cod" value="'.$row['id_usuario'].'" size="120" required></p>';



echo '<div class="form-group">



   <div class="col-xs-12">';



echo '<p><input  name="id_estudiante" type="hidden" id="id_estudiante"  maxlength="10" value="'.$row['id_usuario'].'"></p>';



echo '<p><label for="identificacion">Identificación estudiante:</label><input class="form-control" name="identificacion" type="number"  min="0"  max="999999999999" id="identificacion"  maxlength="12" value="'.$row['id_usuario'].'" required></p>';



echo '</div></div>';







echo '<div class="form-group">



   <div class="col-xs-12">';



echo '<p><label for="nombre">Nombre:</label><input class="form-control"  name="nombre" type="text"  maxlength="120" id="nombre"  maxlength="120" value="'.$row['nombre'].'" required></p>';



echo '</div></div>';







echo '<div class="form-group">



   <div class="col-xs-12">';



echo '<p><label for="apellido">Apellido:</label><input class="form-control"  name="apellido" type="text"  maxlength="120" id="apellido"  maxlength="120" value="'.$row['apellido'].'" required></p>';



echo '</div></div>';







echo '<div class="form-group">



   <div class="col-xs-12">';



echo '<p><label for="direccion">Dirección:</label><input class="form-control" name="direccion" type="text"  maxlength="120" id="direccion"  maxlength="120" value="'.$row['direccion'].'"></p>';



echo '</div></div>';







echo '



<p><input type="submit" name="submit" id="submit" value="Actualizar" class="btn btn-success"></p>



</form>';



}



break;



default:



//echo "Ingreso erroneo";



}//fin switch



}else{



?>



<b><!--label>Buscar: </label--></b><input autofocus style="z-index:1;margin-top:-270px;margin-left:220px;position:absolute;" type="text" id="buscar" placeholder="Estudiante.." autofocus  autofocus onfocus="grabarcookie('asignacion',asignacion.value);buscar(document.getElementById('buscar').value);"    onkeyup ="buscar(this.value);grabarcookie('asignacion',asignacion.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('asignacion',asignacion.value);buscar(document.getElementById('buscar').value);"  >



<b><!--label>N° de Resultados:</label--></b>



<input type="number"  min="0" id="numeroresultados" size="4" maxlength="4" style="width:50px;z-index:1;margin-top:-270px;margin-left:410px;position:absolute;" placeholder="Cantidad de resultados" title="Cantidad de resultados" value="10"    onkeyup="grabarcookie('numeroresultados',this.value);buscar(document.getElementById('buscar').value);" mousewheel="grabarcookie('numeroresultados',this.value);buscar(document.getElementById('buscar').value);" onchange="grabarcookie('numeroresultados',this.value);buscar(document.getElementById('buscar').value);" >



<input type="hidden" value="<?php if (isset($_GET['asignacion'])) echo $_GET['asignacion'] ; ?>" id="asignacion" placeholder="asignacion" title="asignacion"  onkeyup="grabarcookie('asignacion',this.value);buscar(document.getElementById('buscar').value);"  onchange="grabarcookie('asignacion',this.value);buscar(document.getElementById('buscar').value);">



</p>



<div class="row">



<?php if ($_SESSION['rol']=="admin" or $_SESSION['rol']=="docente" ){ ?>



<h4 onclick="mitoogle('#lista_docentes')"><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Matricular</h4>



<?php } ?>



    <div id="lista_docentes" class="col-md-2" <?php 



#if ($_COOKIE['checked_lista_docentes']!="true")



#echo 'style="display:none;"'; ?> >







<?php



if ($_SESSION['rol']=="admin" or $_SESSION['rol']=="docente"){ ?>



  <br>  <br>



<input type="search" id="datos_buscar_acudiente_para_asignar" placeholder="Buscar estudiante..." onkeyup="buscar_acudiente_para_asignar(this.value)">



<ul id="ul_buscar_acudiente_para_asignar">



<?php



buscar_acudiente_para_asignar();



?>



</ul>



<?php } ?>



</div>



<div id="lista_docentes" class="col-md-10">



    <span id="txtsugerencias">



    <?php



    buscar_estudiante();



    ?>



    </span>



</div>



</div>



<?php



#}//fin else if isset cod



?>



 </div>



</div><br>







<script>



$(document).ready(function() {



    setTimeout(function() { cargar_tooltips(); }, 4000);



});



buscar();



</script>



<?php



}

$contenido = ob_get_clean();

require ("../comun/plantilla.php");



?>



