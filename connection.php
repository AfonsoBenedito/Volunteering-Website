<?php
// $dbhost = "127.0.0.1";
// $dbuser = "root";
// $dbpass = "";
// $dbname = "teste asw1103";
$dbhost = "appserver-01.alunos.di.fc.ul.pt";
$dbuser = "asw024";
$dbpass = "agentebec";
$dbname = "asw024";
// Cria a ligação à BD
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
// Verifica a ligação à BD
if (mysqli_connect_error()) {
  die("Database connection failed: " . mysqli_connect_error());
}
?>