<?php 
ob_start();
 
echo '<center>';
require ("../comun/conexion.php");
require_once ("../comun/config.php");
require_once ("../comun/funciones.php");
if (isset($_POST['nombre_institucion'])){
$cod = 1;
 $imgavatar = "";
if (isset($_FILES['logo_institucion']) and $_FILES['logo_institucion']['error']==0){
$imgavatar = ", logo_institucion='' ";
$partes_nombre = explode (".",$_FILES['logo_institucion']['name']);
$ext = end($partes_nombre);
if (isset($_FILES['logo_institucion'])) $imgavatar = ", logo_institucion='logo.".$ext."' ";
if(isset($_POST["foto_old"])) $imgavatar = "";
if(isset($_POST["mifoto"])) $imgavatar = "";
}
$imgbanner = "";
if (isset($_FILES['banner_institucion']) and $_FILES['banner_institucion']['error']==0){
$imgbanner = ", banner_institucion='' ";
$partes_nombre = explode (".",$_FILES['banner_institucion']['name']);
$ext = end($partes_nombre);
if (isset($_FILES['banner_institucion'])) $imgbanner = ", banner_institucion='banner.".$ext."' ";
if(isset($_POST["banner_old"])) $imgbanner = "";
if(isset($_POST["mibanner"])) $imgbanner = "";
}
$sql = "UPDATE  institucion_educativa SET nombre_institucion ='".$_POST['nombre_institucion']."',formatos_no_permitidos ='".$_POST['formatos_no_permitidos']."',tamano_maximo_adjunto ='".$_POST['tamano_maximo_adjunto']."',autoregistrarse ='si' ".$imgavatar." ".$imgbanner." WHERE  id_institucion_educativa = '".$_POST['cod']."';";

 /*Se conecta a la BD y luego ejecuta la instrucción SQL*/ 
if ($actualizar = $mysqli->query($sql)) {
if (isset($_FILES['logo_institucion'])){
$sql_id = "SELECT logo_institucion FROM institucion_educativa WHERE id_institucion_educativa='".$_POST['cod']."';";
$user_id = $mysqli->query($sql_id);
if($row_id=$user_id->fetch_assoc())
$codfoto = $row_id['logo_institucion'];
@unlink("foto/".$codfoto);
@mkdir (READFILE_SERVER."/foto");
copy($_FILES['logo_institucion']['tmp_name'],READFILE_SERVER."/foto/logo.".$ext);
}
if (isset($_FILES['banner_institucion'])){
$sql_id = "SELECT banner_institucion FROM institucion_educativa WHERE id_institucion_educativa='".$_POST['cod']."';";
$user_id = $mysqli->query($sql_id);
if($row_id=$user_id->fetch_assoc())
$codbanner = $row_id['banner_institucion'];
@unlink("foto/".$codbanner);
@mkdir (READFILE_SERVER."/foto");
copy($_FILES['banner_institucion']['tmp_name'],READFILE_SERVER."/foto/banner.".$ext);
}
#Validamos si el registro fue ingresado con éxito
echo "<script>alert2('Modificación exitosa')</script>";
#echo '<meta http-equiv="refresh" content="1; url=perfil.php" />';
 }else{ 
echo "<script>alert2('Modificacion fallida','error')</script>";
}
#echo '<meta http-equiv="refresh" content="2; url=perfil.php" />';
} /*fin Actualizar*/ 
$sql = "SELECT * FROM  institucion_educativa WHERE id_institucion_educativa ='".$_POST['cod']."'"; 
$consulta = $mysqli->query($sql);
/*echo $sql;*/ 
$row=$consulta->fetch_assoc();
// `id_config`, `nombre_institucion`, `logo_institucion`
?>
<center>
<div class="jumbotron">
  <div class="container text-center">
    <h1 class="fip">Modificar Perfil de Institución</h1>
  </div>
</div>

</center>
<form id="form1" name="form1" method="post" action="configuraciones.php" enctype="multipart/form-data">
<p><label for="logo_institucion">Foto:</label></p>
<?php if (isset($row['logo_institucion']) and $row['logo_institucion']!=""){?>
<p><img id="img_foto" height="80" src="<?php echo READFILE_URL ?>/foto/<?php echo $row['logo_institucion']?>"></p>
<?php } ?>
<span id="span_img_foto">
<?php if (isset($row['logo_institucion']) and $row['logo_institucion']!=""){
?>
<input style="display:none" class="form-control" name="mifoto" type="text" id="mifoto" value="<?php echo $row['logo_institucion']?>" required>
<br><input onclick="document.getElementById('span_img_foto').innerHTML='<input style=\'color:#fff\' class=\' btn btn-default\' onchange=\'ValidarArchivo(this);mostrarImagen(this);\' name=\'logo_institucion\' type=\'file\' id=\'foto\' >';" style="color:#fff" class="btn btn-info" type="button" value="Cambiar Foto">
<?php
}else{
?><input onchange="ValidarArchivo(this);validar_resolución(this);mostrarImagen(this);" style="color:#fff" class="btn btn-info" name="logo_institucion"  type="file" id="foto">
<?php
}
?>
</span>
<br><p><label for="banner_institucion">banner:</label></p>
<?php if (isset($row['banner_institucion']) and $row['banner_institucion']!=""){?>
<p><img id="img_banner" height="80" src="<?php echo READFILE_URL ?>/foto/<?php echo $row['banner_institucion']?>"></p>
<?php } ?>
<span id="span_img_banner">
<?php if (isset($row['banner_institucion']) and $row['banner_institucion']!=""){
?>
<input style="display:none" class="form-control" name="mibanner" type="text" id="mibanner" value="<?php echo $row['banner_institucion']?>" required>
<br><input onclick="document.getElementById('span_img_banner').innerHTML='<input style=\'color:#fff\' class=\' btn btn-default\' onchange=\'ValidarArchivo(this);mostrarImagen(this);\' name=\'banner_institucion\' type=\'file\' id=\'banner\' >';" style="color:#fff" class="btn btn-info" type="button" value="Cambiar banner">
<?php
}else{
?><input onchange="ValidarArchivo(this);validar_resolución(this);mostrarImagen(this);" style="color:#fff" class="btn btn-info" name="banner_institucion"  type="file" id="banner">
<?php
}
?>
</span>
<br><br><!--input class="btn btn-warning" style="color:#fff" onclick="sinfoto();" type="button" value="Sin Foto"-->
</p>
<p>
<label for="nombre_institucion">Nombre Institución:</label>
<input autocomplete="off" style="width:350px" class="form-control"  name="nombre_institucion" type="text" id="nombre_institucion" value="<?php if (isset($row['nombre_institucion'])) echo $row['nombre_institucion']; ?>"></p>
</p>
<h1>Configuraciones sobre Adjuntos</h1>
<p>
<label for="formatos_no_permitidos">Formatos no permitidos<br>Escriba los formatos separados por una , (coma):</label><br>
<input autocomplete="off" style="width:350px" class="form-control"  name="formatos_no_permitidos" type="text" id="formatos_no_permitidos" placeholder="Ejemplo: exe,bin,zdf" value="<?php if (isset($row['formatos_no_permitidos'])) echo $row['formatos_no_permitidos']; ?>"></p>
</p>
<p>
 <!--MAX_FILE_SIZE (medido en bytes)-->
<label for="tamano_maximo_adjunto">Tamaño Máximo de archivo adjunto(Kb):</label><br>
<input autocomplete="off" style="width:350px" class="form-control"  name="tamano_maximo_adjunto" type="number" id="tamano_maximo_adjunto" value="<?php if (isset($row['tamano_maximo_adjunto'])) echo $row['tamano_maximo_adjunto']; ?>"></p>
</p>
<div class="col-md-4"></div>
<div class="col-md-4 form-group">
<label>Permitir el Autoregistro:</label><br>
<div class="form-control" >
<?php 
if (isset($row['autoregistrarse'])) {
$autoregistrarse = explode(",",$row['autoregistrarse']);
?>
<label><input <?php if(in_array("docente",$autoregistrarse)) echo " checked " ?> type="checkbox" name="autoregistro[]" id="autoregistro_docente" value="docente">&nbsp;Docente</label>
<label><input <?php if(in_array("estudiante",$autoregistrarse)) echo " checked " ?> type="checkbox" name="autoregistro[]" id="autoregistro_estudiante" value="estudiante">&nbsp;Estudiante</label>
<label><input <?php if(in_array("acudiente",$autoregistrarse)) echo " checked " ?> type="checkbox" name="autoregistro[]" id="autoregistro_acudiente" value="acudiente">&nbsp;Acudiente</label>
<?php } ?>
</div>
</div>
<button class="btn btn-info" type="submit">Actualizar</button>
</form>
<br>
</center>
<script>
//document.getElementById('menu_mi_perfil').className ='active '+document.getElementById('menu_mi_perfil').className;
</script>
<?php $contenido = ob_get_contents();
ob_clean();
include ("../comun/plantilla.php");
 ?>