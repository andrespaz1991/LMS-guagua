<form action ="" ENCTYPE="multipart/form-data" method="POST">

   <input type="file" name="datos"/>

    	<input name="enviar" type="submit" value="importar"/>
</form>
<?php
if (isset($_POST['enviar'])){
    require '../comun/conexion.php';    
    $archivo= $_FILES['datos']['tmp_name'];

    while ($celdas = fgetcsv ($archivo_abierto, 1000, ";")){  //obtenemos las celdas del 
        echo "<pre>";
        print_r($celdas);
        echo "</pre>";

    }
    