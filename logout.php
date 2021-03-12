<?php

session_start();

if (isset($_SESSION['Logged'])){

    unset($_SESSION['Logged']);
    unset($_SESSION['User']);
    header('Location: ./');
    exit;
    
}

?>