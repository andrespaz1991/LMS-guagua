<?php
$filename = "libros.xls";
#header("Content-Type: application/vnd.ms-excel");
#header("Content-Disposition: attachment; filename=".$filename);
$sql='SELECT  usuario.telefono, usuario.correo, usuario.nombre,usuario.apellido,documentacion.* FROM `documentacion` inner join usuario where usuario.id_usuario= documentacion.id_usuario order by documentacion.id asc';
require("../comun/conexion.php");
$consulta=$mysqli->query($sql);
?>
<table border="2">
<tr>
<th>id</th>
<th>identificación</th>
<th>Nombre</th>
<th>Mensaje</th>
<th>telefono</th>
<th>telefono</th>
</tr>
<?php
while($row=$consulta->fetch_assoc()){
$mensaje= "Buen día ".$row['name'].", teniendo en cuenta el seguimiento realizado a su práctica hasta el momento me permito compartirle por correo electrónico  y mensajería instantánea los *documentos* que usted tiene pendientes *(obligatorios) para el desarrollo de su práctica y recepción de su bono*. En caso de no presentar algún documento usted debe realizar la carga del documento de forma inmediata en la carpeta compartida de google drive que presenta el nombre del practicante. Es importante recordar que el control de asistencia debe ser firmado por su jefe inmediato con 8 horas diarias desde la fecha de inicio  de su práctica descrito en el documento 'registro de practicas' que se encuentra en la carpeta previamente mencionada. A continuación presento la listado de la documentación con un si para documento entregado y No en caso de ausencia del documento firmado, quedo atento a cualquier inquietud.";
$mensaje.=" *1)Fotocopia de identificación*: ".$row['cedula']." </br> ";
$mensaje.=" *2)Fotocopia de carnet de eps o certificado de afiliación*  : ".$row['eps']." </br> ";
$mensaje.=" *3)ARL*  : ".$row['arl']." </br> ";
$mensaje.=" *4)Captura de actualización de su hoja de vida en la agencia publica de empleo (SENA) y postulaciones a oportunidades laborales:* ".$row['ape']." </br> ";
$mensaje.=" *5)Carta de presentación*: ".$row['carta_presentacion']." </br> ";
$mensaje.=" *6)Formato de Registro de inscripción:* ".$row['registro_inscripcion']." </br> ";
$mensaje.=' En la sub carpeta visita1 debe contener: <br>';
$mensaje.=" *7)Formato de Desempeño del practicante:* ".$row['desempeno_practicante']." </br> ";
$mensaje.=" *8)Formato de Avance del practicante:* ".$row['avance_practica']." </br> ";
$mensaje.=" *9)Control de asistencia :* ".$row['control_horas']." </br> ";
    echo ' <tr>
    <td>'.$row['id'].'</td>
    <td>'.$row['id_usuario'].'</td>
    <td>'.$row['apellido'].' '.$row['nombre'].'</td>
    <td>'.(($mensaje)).'</td>
    <td>'.($row['telefono']).'</td>
    <td>'.($row['correo']).'</td>
    </tr>';
}
?>
</table>