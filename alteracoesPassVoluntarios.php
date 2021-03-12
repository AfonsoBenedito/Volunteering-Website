<?php 

session_start();

include 'connection.php';

$PassAtual = htmlspecialchars($_POST['alterarPassAtual']);
$PassNova = htmlspecialchars($_POST['alterarPassNova']);
$PassNovaConf = htmlspecialchars($_POST['alterarPassNovaConf']);

$UserEmail = $_SESSION['User'];


$arrayCerto = array();
$arrayErrado = array();

if ($PassNova != $PassNovaConf){
    array_push($arrayErrado, 'Palavras Passes não coincidentes!');
} else {

    $comandoVerificarPass = "SELECT Pass FROM voluntariosVC19 WHERE (Email = '$UserEmail')";

    $verificarPass = mysqli_query($conn, $comandoVerificarPass);

    $PassBD = mysqli_fetch_array($verificarPass, MYSQLI_NUM)[0];



    if (!(password_verify($PassAtual,$PassBD))){
        array_push($arrayErrado, 'Palavras Passe Atual errada!');

    } else {

        $hashedPw = password_hash($PassNova, PASSWORD_DEFAULT);

        $comandoSql = "UPDATE voluntariosVC19 SET Pass = '$hashedPw' WHERE Email = '$UserEmail'";

        $alteracao = mysqli_query($conn, $comandoSql);

        if ($alteracao){
            array_push($arrayCerto, 'Palavra-Passe alterada!');
        } else {
            array_push($arrayErrado, 'Palavras Passe não alterada, erro inesperado!');
        }

    }

}

$_SESSION['tipoAlteracoes'] = "Pass";
$_SESSION['alteracoesCertas'] = $arrayCerto;
$_SESSION['alteracoesErradas'] = $arrayErrado;

header('Location: editarPerfil.php');
exit;