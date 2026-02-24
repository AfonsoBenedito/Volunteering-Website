<?php

if (isset($_SESSION['tipoAlteracoes'])){

    echo "<script>";

    echo "document.getElementsByClassName('conteudoPrincipal')[0].style.top = '45px';";

    echo "document.getElementsByClassName('msgInformativaAlteracoes')[0].style.visibility = 'visible';";




    if (count($_SESSION['alteracoesErradas']) > 0){

        echo "document.getElementsByClassName('msgInformativaAlteracoes')[0].style.backgroundColor = 'rgb(255, 38, 0)';";


        if ($_SESSION['tipoAlteracoes'] == 'Perfil' or $_SESSION['tipoAlteracoes'] == 'Dados'){

            echo "document.getElementsByClassName('alteracoesInfo')[0].innerText = 'Erro! Não foi alterado ';";


            for ($i = 0; $i < count($_SESSION['alteracoesErradas']); $i++){

                $erro = $_SESSION['alteracoesErradas'][$i];

                

                echo "document.getElementsByClassName('alteracoesInfo')[0].innerText += ', ' + '$erro';";

            }

            if (count($_SESSION['alteracoesCertas']) > 0){

                echo "document.getElementsByClassName('alteracoesInfo')[0].innerText += '! Apenas alteradas ';";


                for ($i = 0; $i < count($_SESSION['alteracoesCertas']); $i++){

                    $certo = $_SESSION['alteracoesCertas'][$i];

                    echo "document.getElementsByClassName('alteracoesInfo')[0].innerText += ', ' + '$certo';";

                }
            }

        } else if ($_SESSION['tipoAlteracoes'] == 'Email' or $_SESSION['tipoAlteracoes'] == 'Pass' or $_SESSION['tipoAlteracoes'] == 'Foto'){

            $erro = $_SESSION['alteracoesErradas'][0];

            echo "document.getElementsByClassName('alteracoesInfo')[0].innerText = '$erro';";   

        }


    } else {

        echo "document.getElementsByClassName('alteracoesInfo')[0].innerText = 'Todas as alterações foram efetuadas com sucesso!';";

        echo "document.getElementsByClassName('msgInformativaAlteracoes')[0].style.backgroundColor = 'rgb(0, 171, 131)';";

    }

    unset($_SESSION['tipoAlteracoes']);
    unset($_SESSION['alteracoesCertas']);
    unset($_SESSION['alteracoesErradas']);

    echo "</script>";
    
}

?>