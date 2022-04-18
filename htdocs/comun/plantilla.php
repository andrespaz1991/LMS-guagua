<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/comun/config.php');
require_once($_SERVER['DOCUMENT_ROOT']."/comun/autoload.php");
@session_start();
if(!empty($_SESSION['id_institucion'])){
   $institucion=new Institucion($_SESSION['id_institucion']);
   $institucion->id_institucion_educativa=$_SESSION['id_institucion'];
if (!defined('NOMBRE_INSTITUCION')) define ("NOMBRE_INSTITUCION",
$institucion->nombre_institucion);
if (!defined('LOGO_INSTITUCION')) define ("LOGO_INSTITUCION",
$institucion->logo_institucion);
if (!defined('BANNER_INSTITUCION')) define ("BANNER_INSTITUCION",
$institucion->BANNER_INSTITUCION);
if(isset($_SESSION['rol'])){
setcookie('tipos',$institucion->formatos_no_permitidos);
setcookie('tamaño',$institucion->tamano_maximo_adjunto);
}
}

$plantilla = dirname(__FILE__)."/plantillas/plantilla_kids_m.php";
require_once (dirname(__FILE__)."/config.php");
include($plantilla);
?>
<script>
function cambioplantilla(e) {
tecla=(document.all) ? e.keyCode : e.which; 
if (tecla==80 && e.altKey) //80 = letra p 
   document.location.href="<?php echo SGA_URL; ?>/index.php?cambiar_plantilla";
}
</script>