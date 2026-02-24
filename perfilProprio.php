<?php

session_start();

include 'includes/connection.php';

$UserEmail = $_SESSION['User'];

if ($_SESSION['Tipo'] == 'Vol'){
    $comandoSql = "SELECT Username FROM voluntariosVC19 WHERE ('$UserEmail' = Email)";

    $resultadoQuery = mysqli_query($conn, $comandoSql);

    $res = mysqli_fetch_array($resultadoQuery, MYSQLI_NUM)[0];

    header('Location: perfilVoluntario.php?Username='.$res);
    exit;


}else if ($_SESSION['Tipo'] == 'Inst'){

    $comandoSql = "SELECT Nome FROM instituicoesVC19 WHERE ('$UserEmail' = Email)";

    $resultadoQuery = mysqli_query($conn, $comandoSql);

    $res = mysqli_fetch_array($resultadoQuery, MYSQLI_NUM)[0];

    header('Location: perfilInstituicao.php?Nome='.$res);
    exit;

}


?>