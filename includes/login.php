<?php


if (!(isset($_SESSION['Logged']))){

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        include __DIR__ . '/connection.php';

        $email = htmlspecialchars($_POST["LoginEmail"]);
        $pass = htmlspecialchars($_POST["LoginPass"]);
        

        $comandoLoginV = "SELECT Email, Pass FROM voluntariosVC19 WHERE (Email = '$email')";

        $comandoLoginI = "SELECT Email, Pass FROM instituicoesVC19 WHERE (Email = '$email')";

        $resultadoV = mysqli_query($conn, $comandoLoginV);

        $resultadoI = mysqli_query($conn, $comandoLoginI);


        if (mysqli_num_rows($resultadoV)!=0) {

            
            $info =  mysqli_fetch_array($resultadoV, MYSQLI_NUM);


            if (password_verify($pass ,$info[1])){

                $_SESSION['Logged'] = TRUE;

                $_SESSION['User'] = $info[0];

                $_SESSION['Tipo'] = "Vol";

            } else {
                
                $msgLogin = '*Palavra-Passe Incorreta*';

            }

        } else if(mysqli_num_rows($resultadoI)!=0) {

            
            $info =  mysqli_fetch_array($resultadoI, MYSQLI_NUM);

            if (password_verify($pass ,$info[1])){

                $_SESSION['Logged'] = TRUE;

                $_SESSION['User'] = $info[0];

                $_SESSION['Tipo'] = "Inst";

            } else {
                
                $msgLogin = '*Palavra-Passe Incorreta*';

            }

        } else {

            mysqli_error($conn);

            $msgLogin = '*Utilizador não encontrado*';
        }

    }
}