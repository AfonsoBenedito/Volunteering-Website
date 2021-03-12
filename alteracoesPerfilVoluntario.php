<?php 

session_start();

include 'connection.php';

$Username = htmlspecialchars($_POST['alterarUsername']);
$Nome = htmlspecialchars($_POST['alterarNome']);
$Apelido = htmlspecialchars($_POST['alterarApelido']);
$Bio = htmlspecialchars($_POST['alterarBiografia']);
$Nasc = htmlspecialchars($_POST['alterarNascimento']);
$Genero = htmlspecialchars($_POST['alterarGenero']);
$Intresses = htmlspecialchars($_POST['alterarIntresses']);
$PopAlvo = htmlspecialchars($_POST['alterarPopAlvo']);
$Disponibilidade = htmlspecialchars($_POST['alterarDisponibilidade']);

$UserEmail = $_SESSION['User'];

$arrayCerto = array();
$arrayErrado = array();

// echo "Recebido: '$Nome','$Bio','$Nasc','$Genero','$Intresses','$PopAlvo','$Disponibilidade'";

if ($Username != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET Username = '$Username' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Username');
    } else {   
        array_push($arrayErrado, 'Username');
    }
}

if ($Nome != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET Nome = '$Nome' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Nome');
    } else {   
        array_push($arrayErrado, 'Nome');
    }
}

if ($Apelido != ""){

    $comandoSql = "UPDATE voluntariosVC19 SET Apelido = '$Apelido' WHERE (Email = '$UserEmail')";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Apelido');
    } else {   
        array_push($arrayErrado, 'Apelido');
    }
}

if ($Bio != ""){
    $comandoSql = "UPDATE voluntariosVC19 SET Biografia = '$Bio' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Biografia');
    } else {   
        array_push($arrayErrado, 'Biografia');
    }
}

if ($Nasc != ""){
    $comandoSql = "UPDATE voluntariosVC19 SET Nascimento = '$Nasc' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Data de Nascimento');
    } else {   
        array_push($arrayErrado, 'Data de Nascimento');
    }
}

if ($Genero != ""){
    $comandoSql = "UPDATE voluntariosVC19 SET Genero = '$Genero' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Género');
    } else {   
        array_push($arrayErrado, 'Género');
    }
}

if ($Intresses != ""){
    $comandoSql = "UPDATE voluntariosVC19 SET Intresses = '$Intresses' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Intresses');
    } else {   
        array_push($arrayErrado, 'Intresses');
    }
}

if ($PopAlvo != ""){
    $comandoSql = "UPDATE voluntariosVC19 SET PopAlvo = '$PopAlvo' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'População Alvo');
    } else {   
        array_push($arrayErrado, 'População Alvo');
    }
}


if ($Disponibilidade != ""){
    $comandoSql = "UPDATE voluntariosVC19 SET Disponibilidade = '$Disponibilidade' WHERE Email = '$UserEmail'";

    $resultadoMudar = mysqli_query($conn, $comandoSql);

    if ($resultadoMudar){
        array_push($arrayCerto, 'Disponibilidade');
    } else {   
        array_push($arrayErrado, 'Disponibilidade');
    }
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

header('Location: editarPerfil.php');
exit;



?>