<?php 

session_start();

include 'connection.php';

$Distrito = htmlspecialchars($_POST['alterarDistrito']);
$Concelho = htmlspecialchars($_POST['alterarConcelho']);
$Freguesia = htmlspecialchars($_POST['alterarFreguesia']);
$Morada = htmlspecialchars($_POST['alterarMorada']);
$NomeRepr = htmlspecialchars($_POST['alterarNomeRepresentante']);
$EmailRepr = htmlspecialchars($_POST['alterarEmailRepresentante']);



$UserEmail = $_SESSION['User'];

$arrayCerto = array();
$arrayErrado = array();


if ($Distrito != ""){

    $comandoSql = "UPDATE instituicoesVC19 SET Distrito = '$Distrito' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Distrito');
    } else {   
        array_push($arrayErrado, 'Distrito');
    }
}

if ($Concelho != ""){

    $comandoSql = "UPDATE instituicoesVC19 SET Concelho = '$Concelho' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Concelho');
    } else {   
        array_push($arrayErrado, 'Concelho');
    }
}

if ($Freguesia != ""){

    $comandoSql = "UPDATE instituicoesVC19 SET Freguesia = '$Freguesia' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Freguesia');
    } else {   
        array_push($arrayErrado, 'Freguesia');
    }
}

if ($Morada != ""){

    $comandoSql = "UPDATE instituicoesVC19 SET Morada = '$Morada' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Morada');
    } else {   
        array_push($arrayErrado, 'Morada');
    }
}

if ($NomeRepr != ""){

    $comandoSql = "UPDATE instituicoesVC19 SET NomeRepresentante = '$NomeRepr' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Nome do Representante');
    } else {   
        array_push($arrayErrado, 'Nome do Representante');
    }
}

if ($EmailRepr != ""){

    $comandoSql = "UPDATE instituicoesVC19 SET EmailRepresentante = '$EmailRepr' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Email do Representante');
    } else {   
        array_push($arrayErrado, 'Email do Representante');
    }
}

if (count($arrayCerto) > 0){
    echo "Foram alteradas com sucesso '$arrayCerto'";
}
if (count($arrayErrado) > 0){
    echo "Erro ao alterar '$arrayErrado'";
}

$_SESSION['tipoAlteracoes'] = "Dados";
$_SESSION['alteracoesCertas'] = $arrayCerto;
$_SESSION['alteracoesErradas'] = $arrayErrado;

header('Location: editarPerfilInstituicao.php');
exit;



?>