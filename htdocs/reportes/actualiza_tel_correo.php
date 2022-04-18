<form action ="" ENCTYPE="multipart/form-data" method="POST">

   <input type="file" name="datos"/>

    	<input name="enviar" type="submit" value="importar"/>
</form>

<?php if(isset($_POST)){
   require("../comun/conexion.php");
   $archivo= $_FILES['datos']['tmp_name']; 
   if (($archivo_abierto = fopen($archivo, "r")) !== FALSE) { 
   while ($celdas = fgetcsv ($archivo_abierto,2000, ";")){
      $sql='UPDATE `usuario` SET `telefono`="'.$celdas[1].'",`correo`="'.$celdas[2].'" WHERE `id_usuario`="'.$celdas[0].'"';
      echo $sql.'<br>';
$consulta=$mysqli->query($sql);
   }
}
}