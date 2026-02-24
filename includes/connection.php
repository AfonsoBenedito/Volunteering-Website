<?php
$dbhost = getenv('DB_HOST') ?: 'appserver-01.alunos.di.fc.ul.pt';
$dbuser = getenv('DB_USER') ?: 'asw024';
$dbpass = getenv('DB_PASS') ?: 'agentebec';
$dbname = getenv('DB_NAME') ?: 'asw024';
// Cria a ligação à BD
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
// Verifica a ligação à BD
if (mysqli_connect_error()) {
  die("Database connection failed: " . mysqli_connect_error());
}
?>