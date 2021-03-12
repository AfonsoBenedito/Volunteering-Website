<?php

session_start();

include "connection.php";

$NomeInst = htmlspecialchars($_POST['alterarNomeInstituicao']);
$DescricaoInst = htmlspecialchars($_POST['alterarDescricao']);
$TelemovelInst = htmlspecialchars($_POST['alterarTelemovel']);

$UserEmail = $_SESSION['User'];

$arrayCerto = array();
$arrayErrado = array();

if ($NomeInst != ""){

    $comandoSql = "UPDATE instituicoesVC19 SET Nome = '$NomeInst' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Nome Instituição');
    } else {   
        array_push($arrayErrado, 'Nome Instituição');
    }
}

if ($DescricaoInst != ""){

    $comandoSql = "UPDATE instituicoesVC19 SET Descricao = '$DescricaoInst' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Descrição');
    } else {   
        array_push($arrayErrado, 'Descrição');
    }
}

if ($TelemovelInst != ""){
    
    $comandoSql = "UPDATE instituicoesVC19 SET Telemovel = '$TelemovelInst' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Telemóvel');
    } else {   
        array_push($arrayErrado, 'Telemóvel');
    }
}

if (count($arrayCerto) > 0){
    echo "Foram alteradas com sucesso '$arrayCerto'";
}

if (count($arrayCerto) > 0){
    echo "Erro ao alterar '$arrayErrado'";
}



if (count($arrayCerto) > 0){
    echo "Foram alteradas com sucesso '$arrayCerto'";
}
if (count($arrayErrado) > 0){
    echo "Erro ao alterar '$arrayErrado'";
}

$_SESSION['tipoAlteracoes'] = "Perfil";
$_SESSION['alteracoesCertas'] = $arrayCerto;
$_SESSION['alteracoesErradas'] = $arrayErrado;

header('Location: editarPerfilInstituicao.php');
exit;

?>
