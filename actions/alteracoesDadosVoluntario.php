<?php 

session_start();

include '../includes/connection.php';

$Distrito = htmlspecialchars($_POST['alterarDistrito']);
$Concelho = htmlspecialchars($_POST['alterarConcelho']);
$Freguesia = htmlspecialchars($_POST['alterarFreguesia']);
$Telemovel = htmlspecialchars($_POST['alterarTelemovel']);
$CC = htmlspecialchars($_POST['alterarCC']);
$Conducao = htmlspecialchars($_POST['alterarConducao']);


$UserEmail = $_SESSION['User'];

$arrayCerto = array();
$arrayErrado = array();

if ($Distrito != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET Distrito = '$Distrito' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Distrito');
    } else {   
        array_push($arrayErrado, 'Distrito');
    }
}

if ($Concelho != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET Concelho = '$Concelho' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Concelho');
    } else {   
        array_push($arrayErrado, 'Concelho');
    }
}

if ($Freguesia != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET Freguesia = '$Freguesia' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Freguesia');
    } else {   
        array_push($arrayErrado, 'Freguesia');
    }
}

if ($Telemovel != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET Telemovel = '$Telemovel' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Telemóvel');
    } else {   
        array_push($arrayErrado, 'Telemóvel');
    }
}

if ($CC != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET CC = '$CC' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Cartão de Cidadão');
    } else {   
        array_push($arrayErrado, 'Cartão de Cidadão');
    }
}

if ($Conducao != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET Conducao = '$Conducao' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Condução');
    } else {   
        array_push($arrayErrado, 'Condução');
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

header('Location: ../editarPerfil.php');
exit;

?>