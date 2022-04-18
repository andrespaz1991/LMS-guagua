<?php
$mysqli = new mysqli ('localhost','root','','guagua');
$sql='select * from seguimiento where id_seguimiento="305" ';
$consulta=$mysqli->query($sql);
while($row=$consulta->fetch_assoc()){
echo $row['observaciones'];
}

?>