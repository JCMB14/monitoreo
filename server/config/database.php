<?php
$host='mysql-jeanmerino.alwaysdata.net';
$user='350407';
$password='sistemas1428';
$database='jeanmerino_monitoreoweb';
$mysqli= new mysqli($host,$user,$password,$database);
if($mysqli->connect_errno)
{
    echo "Error al conectar a la base de dato: ".$mysqli->connect_errno.'<br>';
}else{
    echo "conectado";
}

?>