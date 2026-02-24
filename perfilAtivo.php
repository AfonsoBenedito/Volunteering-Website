<?php 
    include 'includes/connection.php';

    $UserEmail = $_SESSION['User'];


    if ($_SESSION['Tipo'] == 'Vol'){

        $comandoSql = "SELECT ImagePath FROM voluntariosVC19 WHERE (Email = '$UserEmail');";

        $imgPath = mysqli_fetch_array(mysqli_query($conn, $comandoSql), MYSQLI_NUM)[0];

    } else if ($_SESSION['Tipo'] == 'Inst') {

        $comandoSql = "SELECT ImagePath FROM instituicoesVC19 WHERE (Email = '$UserEmail');";

        $imgPath = mysqli_fetch_array(mysqli_query($conn, $comandoSql), MYSQLI_NUM)[0];
    }

    echo "novoButton = document.createElement('button');";
    echo "novoButton.setAttribute('class','perfilButton');";
    echo "novoButton.innerText='foto';";

    echo "novoDiv = document.createElement('div');";
    echo "novoDiv.setAttribute('class','navImagemPerfil');";

    echo "novoImg = document.createElement('img');";
    echo "novoImg.setAttribute('src','$imgPath');";
    echo "novoImg.setAttribute('id','imgHeaderPerfil');";

    echo "novoDiv.appendChild(novoImg);";

    echo "novoButton.appendChild(novoDiv);";
    
    echo "novoDiv2 = document.createElement('div');";
    echo "novoDiv2.setAttribute('class','apontadorPerfil');";

    echo "novoButton.appendChild(novoDiv2);";

    echo "document.getElementById('naviBar').appendChild(novoButton);";

?>