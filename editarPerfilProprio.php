<?php

session_start();

include 'includes/connection.php';

if ($_SESSION['Tipo'] == 'Vol'){

    header('Location: editarPerfil.php');
    exit;


}else if ($_SESSION['Tipo'] == 'Inst'){

    header('Location: editarPerfilInstituicao.php');
    exit;

}

?>