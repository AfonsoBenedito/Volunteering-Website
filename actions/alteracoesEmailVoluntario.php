<?php 

session_start();

include '../includes/connection.php';

$Email = htmlspecialchars($_POST['alterarEmail']);
$EmailConf = htmlspecialchars($_POST['alterarEmailConf']);
$Pass = htmlspecialchars($_POST['alterarEmailPass']);

$UserEmail = $_SESSION['User'];


$arrayCerto = array();
$arrayErrado = array();




if ($Email != $EmailConf){

    array_push($arrayErrado, 'Emails não coincidentes!');
} else {

    $comandoTesteEmail = "SELECT * FROM voluntariosVC19, instituicoesVC19 WHERE (voluntariosVC19.Email = '$Email') OR (instituicoesVC19.Email = '$Email');";

    $resultadoTesteEmail = mysqli_query($conn, $comandoTesteEmail);

    if (mysqli_num_rows($resultadoTesteEmail) > 0){

        array_push($arrayErrado, 'Email já associado a uma conta');

    } else {

        $comandoVerificarPass = "SELECT Pass FROM voluntariosVC19 WHERE (Email = '$UserEmail')";

        $verificarPass = mysqli_query($conn, $comandoVerificarPass);

        $PassBD = mysqli_fetch_array($verificarPass, MYSQLI_NUM)[0];

        if (!(password_verify($Pass,$PassBD))){
            array_push($arrayErrado, 'Palavras Passe errada!');
        } else {

            $comandoSql = "UPDATE voluntariosVC19 SET Email = '$Email' WHERE Email = '$UserEmail'";

            $alteracao = mysqli_query($conn, $comandoSql);

            if ($alteracao){
                array_push($arrayCerto, 'Email alterado!');
                $_SESSION['User'] = $Email;

            } else {
                array_push($arrayErrado, 'Email não alterado, erro inesperado!');
            }

        }

    }

    
}

$_SESSION['tipoAlteracoes'] = "Email";
$_SESSION['alteracoesCertas'] = $arrayCerto;
$_SESSION['alteracoesErradas'] = $arrayErrado;


// echo $arrayCerto[0];
// echo $arrayErrado[0];

header('Location: ../editarPerfil.php');
exit;

?>