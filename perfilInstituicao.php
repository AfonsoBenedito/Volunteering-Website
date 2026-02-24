<?php

    session_start();

    if (isset($_POST['LoginEmail'])){
        include 'includes/login.php';
    }

    if (!(isset($_GET["Nome"]))){
        
        header("Location: ./");
        exit;
    
    }

    include 'includes/connection.php';

    $nomeI = $_GET["Nome"];

    $comando = "SELECT * FROM instituicoesVC19 i WHERE (i.Nome = '$nomeI');";

    $resultado = mysqli_query($conn, $comando);

    $row = mysqli_fetch_array($resultado, MYSQLI_NUM);

    if (mysqli_num_rows($resultado) == 0){
    
        header("Location: ./");
        exit;
    }
    

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100&display=swap" rel="stylesheet">

        <!-- Geral CSS -->
        <link rel="stylesheet" href="./styles/geral.css">
        <link rel="stylesheet" href="./styles/perfil.css">
        <link rel="stylesheet" href="./styles/perfilInstituicao.css">

        <title>Instituição</title>

        <link rel="shortcut icon" type="image/jpg" href="./assets/icons/logoBranco.png">


    </head>
    
    <body>
    
        <!------------- LOGIN --------------->
        <div class="popUpLoginBackground"></div>
        <div class="popUpLogin">
            <!-- Zona do Login -->
            <div class="zonaLogin">
                <div class="loginCenter">
                    <?php
                        $varAction = htmlspecialchars($_SERVER["PHP_SELF"]);

                        $varAction .= "?Nome=$nomeI";

                        echo '<form method="post" action="' . $varAction . '">';
                    ?>
                        <h1>Autenticação</h1>
                        <br>
                        <h5 id="ErroLogin"></h5>
                        <h2>Email</h2>
                        <input type="email" name="LoginEmail" placeholder="Email" required>
                        <br><br>
                        <h2>Palavra-passe</h2>
                        <input type="password" name="LoginPass" placeholder="Palavra-passe" required>
                        <br><br><br>
                        <input class="entrarLogin" type="submit" value="Entrar">
                        <br><br>
                        <a>Esqueceu-se da sua palavra-passe?</a>
                    </form>
                </div>
            </div>
            <!-- Zona do Registo -->
            <div class="zonaRegistar">
                <div class="registarCenter">
                    <h1>Sem registo?</h1>
                    <br>
                    <p>Sou novo na voluntárioCOVID19</p>
                    <p><b>Quero registar-me como:</b></p>
                    <br>
                    <br>
                    <a href="inscreverVoluntario.php"><input type="submit" value="Voluntário"></a>
                    <br><br>
                    <a href="inscreverInstituicao.php"><input type="submit" value="Organização"></a>
                </div>
            </div>
            <!-- Botão fechar popUp -->
            <div class="zonaFechar">
                <svg xmlns="http://www.w3.org/2000/svg" 
                    
                    id="exitLogin"
                    fill="currentColor" 
                    class="bi bi-x-circle" 
                    viewBox="0 0 16 16">

                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 
                    0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </div>
        </div>

        <!-------------------------------------->

        <header id="header">

            <div class="logo">
                <a href="./"><img id="logotipoImg" src="assets/icons/logoPreto.png"></a>
            </div>

            <nav id="naviBar">
                <a href="voluntarios.php"><button class="navBar">Voluntários</button></a>
                <a href="instituicoes.php"><button class="navBar">Instituições</button></a>
                <button class="navBar">Eventos</button>
                <button class="btnEntrar">Entrar</button>
            </nav>

        </header>

        <div class="backMenuPerfil"></div>

        <div class="menuPerfil" style="top: 85px !important;">
            <a href="perfilProprio.php"><li>
                <svg xmlns="http://www.w3.org/2000/svg"
                    id="iconBtnPerfil"
                    class="bi bi-person-circle" 
                    viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                  </svg>
                <h3>Perfil</h3>
            </li></a>
            <a href="editarPerfilProprio.php"><li>
                <svg xmlns="http://www.w3.org/2000/svg"
                    id="iconBtnPerfil"
                    class="bi bi-gear" 
                    viewBox="0 0 16 16">
                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                  </svg>
                <h3>Definições</h3>
            </li></a>
            <a href="logout.php"><li class="lastMenuLi">
                <h3>Terminar sessão</h3>
            </li></a>
        </div>

        <main>

            <div id="adicional"></div>
            
            <div class="zonaPerfil">
                <li class="zonasPerfil">
                    <div class="zonaFotoInstituicao"><img id="fotoPerfilInst" src="assets/images/perfilDefault.png"></div>
                </li>
                <li class="descricaoInst">
                    <h1 id="nome">Nome instituição</h1>
                    <a href="editarPerfilInstituicao.php" class="editarPerfilInst">Editar perfil</a>
                    <h2 id="localidade">Localidade</h2>
                    <h2 id="numeroTelefone">NUMERO DE TELEFONE</h2>
                    <h2 id="descricao">Descrição</h2>
                </li>
            </div>

            <div class="mostraEventosParticipou">
                <!--<li>
                    <div class="fotoEventoPerfil"></div>
                    <div class="defEventoPerfil">
                        <h1>Nome Evento</h1>
                        <h3>Localidade</h3>
                    </div>
                    <div class="ativoDesativoPerfil">
                        <h3>Expirado</h3>
                    </div>
                </li>-->
                <li>
                    <div class="fotoEventoPerfil"></div>
                    <div class="defEventoPerfil">
                        <h1>Evento Muito Bonito</h1>
                        <h3>Portugal</h3>
                    </div>
                    <div class="ativoPerfil">
                        <h3>Ativo</h3>
                    </div>
                </li>
                <li>
                    <div class="fotoEventoPerfil"></div>
                    <div class="defEventoPerfil">
                        <h1>Recolha de Lixo</h1>
                        <h3>Costa da Caparica</h3>
                    </div>
                    <div class="ativoPerfil">
                        <h3>Ativo</h3>
                    </div>
                </li>
                <li>
                    <div class="fotoEventoPerfil"></div>
                    <div class="defEventoPerfil">
                        <h1>Montaditos</h1>
                        <h3>FCUL é grande</h3>
                    </div>
                    <div class="aDecorrerPerfil">
                        <h3>A decorrer</h3>
                    </div>
                </li>
                <li>
                    <div class="fotoEventoPerfil"></div>
                    <div class="defEventoPerfil">
                        <h1>RECOLHA DE ANANÁS</h1>
                        <h3>Médio Oriente</h3>
                    </div>
                    <div class="ativoDesativoPerfil">
                        <h3>Expirado</h3>
                    </div>
                </li>
            </div>

            <!-- <footer>
                <div class="sepFooter"></div>
                    <a href="./">Home</a>
                    <a href="inscreverInstituicao.php">Inscrever Instituição</a>
                    <a href="inscreverVoluntario.php">Inscrever Voluntário</a>
                    <a href="perfilVoluntario.php">Perfil Voluntario</a>
                    <a href="perfilInstituicao.php">Perfil Instituição</a>
                    <a href="editarPerfil.php">Editar Perfil Voluntario</a>
                    <a href="editarPerfilInstituicao.php">Editar Perfil Instituição</a>
            </footer> -->
        </main>
        
        
        <script src="scripts/base.js"></script>
        

    </body>
</html>

<?php

if (isset($_SESSION['Logged'])) {

    //Header

    echo "<script>";

    echo "document.getElementsByClassName('btnEntrar')[0].remove();";

    include "perfilAtivo.php";

    echo "</script>";

    echo "<script src='scripts/perfilAtivo.js'></script>";

} else if (isset($msgLogin)){
    echo "<script> abrirLogin();";
    echo "document.getElementById('ErroLogin').style.setProperty('color','red', 'important');";
    echo "document.getElementById('ErroLogin').innerText = '$msgLogin';";
    echo "</script>";
}



// echo "<script>";
// echo "console.log('$row')";
// echo "</script>";

if ($row == NULL){
    header('Location: ./');
    exit;
}

echo "<script> document.getElementById('nome').innerText = '$row[2]';";
echo "document.getElementById('localidade').innerText = '$row[7]';";
echo "document.getElementById('numeroTelefone').innerText = '$row[3]';";
echo "document.getElementById('descricao').innerText = '$row[8]';";
echo "document.getElementById('fotoPerfilInst').src = '$row[11]';";
echo "</script>";



if($row[0] == $_SESSION["User"] and isset($_SESSION['User'])){
    echo "<script>";
    echo "document.getElementsByClassName('editarPerfilInst')[0].style.display = 'inline-block';";
    echo "document.getElementsByClassName('editarPerfilInst')[0].style.visibility = 'visible';";
    echo "</script>";

    $arrayIncompleto = array();

        

    if ($row[3] == NULL){
        array_push($arrayIncompleto, 'Telemóvel');
    }
    if ($row[4] == NULL){
        array_push($arrayIncompleto, 'Distrito');
    }
    if ($row[5] == NULL){
        array_push($arrayIncompleto, 'Concelho');
    }
    if ($row[6] == NULL){
        array_push($arrayIncompleto, 'Freguesia');
    }
    if ($row[7] == NULL){
        array_push($arrayIncompleto, 'Morada');
    }
    if ($row[8] == NULL){
        array_push($arrayIncompleto, 'Descrição');
    }
            
             

    if (count($arrayIncompleto) > 0){

        echo "<script>";

        $textoH = "Informações de utilizador por preencher Editar perfil: <br>";


        for ($i = 0; $i < count($arrayIncompleto); $i++){
            $textoH .=  $arrayIncompleto[$i] . "; ";

        }


        echo "novaDiv = document.createElement('div');";

        echo "novaDiv.setAttribute('class','perfilIncompleto');";

        echo "novoH = document.createElement('h3');";

        

        echo "novoH.innerHTML = '$textoH';";

        echo "novaDiv.appendChild(novoH);";

        echo "document.getElementById('adicional').appendChild(novaDiv);";

        echo "document.getElementById('adicional').style.display = 'block';";

        echo "</script>";
    }
}

?>