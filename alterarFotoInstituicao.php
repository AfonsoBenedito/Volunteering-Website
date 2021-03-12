<?php

session_start();

$arrayCerto = array();
$arrayErrado = array();

include 'connection.php';

$UserEmail = $_SESSION['User'];

$comandoSql = "SELECT Nome FROM instituicoesVC19 WHERE (Email = '$UserEmail');";

$Nome =urlencode(mysqli_fetch_array(mysqli_query($conn, $comandoSql), MYSQLI_NUM)[0]);

$nomeBase = basename($_FILES["carregarFoto"]["name"]);

$extensao = strtolower(pathinfo($nomeBase,PATHINFO_EXTENSION));


$target_dir = "assets/FotosInstituicao/";
$target_file = $target_dir . $Nome . "." . $extensao;
$uploadOk = 1;

if(isset($_POST["submit"])) {

    $check = getimagesize($_FILES["carregarFoto"]["tmp_name"]);
    if($check !== false) {
        echo "Ficheiro é uma imagem - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        array_push($arrayErrado, "O ficheiro não é uma imagem.");
        $uploadOk = 0;
    }

}


if ($_FILES["carregarFoto"]["size"] > 500000) {
    array_push($arrayErrado, "O ficheiro é demasiado grande.");
    $uploadOk = 0;
}


if($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png") {
    array_push($arrayErrado, "Só permitidos ficheiros JPG, JPEG e PNG.");
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Ficheiro não pode levar upload";

} else {
    if (move_uploaded_file($_FILES["carregarFoto"]["tmp_name"], $target_file)) {
        echo "O ficheiro ". htmlspecialchars( basename( $_FILES["carregarFoto"]["name"])). " levou upload.";
    } else {
        array_push($arrayErrado, "Houve um erro com o seu ficheiro.");
    }
}

if ($uploadOk == 1){

    $comandoInsert  = "UPDATE instituicoesVC19 SET ImagePath = '$target_file' WHERE (Email = '$UserEmail');";

    $res = mysqli_query($conn, $comandoInsert);

    if ($res){

        echo "Base de dados atualizada!";

        array_push($arrayCerto, 'Foto de Perfil');

        
    } else {

        mysqli_error($conn);

        array_push($arrayErrado, 'Erro inesperado');
    }


}

$_SESSION['alteracoesCertas'] = $arrayCerto;
$_SESSION['alteracoesErradas'] = $arrayErrado;
$_SESSION['tipoAlteracoes'] = 'Foto';

header("Location: editarPerfilInstituicao.php");
exit;

?>
