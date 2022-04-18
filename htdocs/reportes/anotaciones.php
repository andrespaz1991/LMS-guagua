<?php
ob_start(); ?>
<form action="" method="post">
<label>Fecha de inicio de consulta</label>
<input type="date" name="inicio" value="<?php echo date('Y-m-d') ?>"></input>
<label>Fecha de fin de consulta</label>
<input type="date" name="fin" value="<?php echo date('Y-m-d') ?>"></input>
<input type="submit" name="enviar" value="consultar"></input>
</form>
<?php
if(isset($_POST)){
    $inicio=$_POST['inicio'];
    $fin=$_POST['fin'];
}else{
    $inicio=date('Y-m-d');
    $fin=date('Y-m-d');
}
require("../comun/autoload.php");
require("../comun/conexion.php");
$sql='SELECT usuario.id_usuario,usuario.nombre,usuario.apellido,seguimiento.fecha_fin,seguimiento.observaciones FROM `seguimiento` inner join usuario on seguimiento.identificacion=usuario.id_usuario where grupo="85" and seguimiento.fecha_fin>"'.$inicio.'" and seguimiento.fecha_fin<"'.$fin.'" order by usuario.nombre asc
';
$consulta=$mysqli->query($sql);
?>
<table border="2">
<tr>
    <th>Identificación</th>
    <th>Nombre</th>
    <th>Fecha</th>
    <th>Observaciones</th>
</tr>
<?php
while($row=$consulta->fetch_assoc()){
echo '<tr>
    <td>'.$row["id_usuario"].'</td>
    <td>'.$row["apellido"].' '.$row["nombre"].'</td>
    <td>'.Fecha::formato_fecha_corta($row["fecha_fin"]).'</td>
    <td>'.$row["observaciones"].'</td>
</tr>';
}
?>
</table>
<?php
$contenido = ob_get_contents();
ob_clean();
include ("../comun/plantilla.php");
?>