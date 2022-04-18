<?php

require("../clases/Backup_Database.Class.php");
$Backup_Database=new Backup_Database();
$Backup_Database->copia_bd("educatecomco_guagua",TRUE,1);

?>