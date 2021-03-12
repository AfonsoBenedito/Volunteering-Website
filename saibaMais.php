<?php

    session_start();
    include 'connection.php';

    if (isset($_POST['LoginEmail'])){
        include 'Login.php';
    }
    

?>

<!DOCTYPE html>
<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&amp;display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100&amp;display=swap" rel="stylesheet">

    <!-- Geral CSS -->
    <link rel="stylesheet" href="styles/geral.css">
    <link rel="stylesheet" href="styles/perfil.css">
    <link rel="stylesheet" href="styles/saibaMais.css">

    <link rel="shortcut icon" href="./assets/Icons/logoBranco.png">

    <title>Saiba Mais</title>


    
</head>
<body>
    <header id="header">
        <div class="logo">
            <a href="./"><img id="logotipoImg" src="assets/Icons/logoPreto.png"></a>
        </div>

        <nav id="naviBar">
            <a href="voluntarios.php"><button class="navBar">Voluntários</button></a>
            <a href="instituicoes.php"><button class="navBar">Instituições</button></a>
            <button class="navBar">Eventos</button>
            <button class="btnEntrar">Entrar</button>
        </nav>
    </header>

    <div class="popUpLoginBackground"></div>

    <div class="popUpLogin">
        <!-- Zona do Login -->
        <div class="zonaLogin">
            <div class="loginCenter">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                <a href="inscreverInstituicao.php"><input type="submit" value="Instituição"></a>
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
    
    <div class="backMenuPerfil"></div>

    <div class="menuPerfil">
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


    <div class="text">
        <h1>O que é a VoluntarioCOVID19?</h1>

        <h3>O objetivo da nossa aplicação VoluntarioCOVID19 é permitir aos utilizadores criarem um perfil, como voluntários ou instituições,
            para participarem e criarem, respetivamente, eventos de voluntariado para ajudar as pessoas que mais precisam neste 
            periodo de pandemia.</h3><br><br>

        <div class="grid-container">
            <div class="separadorV">
                <h1>Sobre os Voluntários</h1>

                <h3>Um utilizador que se inscrever como voluntário poderá participar em eventos de voluntariado. Durante a inscrição 
                    não são obrigatórios preencher todos os campos de requisitos para estar registado, mas se quiser participar nesses 
                    eventos precisará de os completar obrigatóriamente. O utilizador só participa nos eventos se os requisitos do seu perfil 
                    estiverem de acordo com os dos eventos, por exemplo, se a população alvo do voluntário for a mesma do evento
                    então o voluntário será aceite.<br><br></h3>
            </div>

            <div class="image1">
                <img src="./assets/Imagens/imgVoluntario.jpg">
            </div>

            <div class="image2">
                <img src="./assets/Imagens/imgInstituicao.jpg">
            </div>

            <div class="separadorI">
                <h1>Sobre as Instituições</h1>    

                <h3>Um utilizador também se pode registar como uma instituição, na nossa aplicação, para poder criar os eventos de voluntariado 
                    mas para o fazer também precisa de preencher todos os campos do seu perfil. A instituição recruta voluntários para as suas 
                    ações se estes cumprirem com os requisitos dos eventos criados.<br><br></h3>
            </div>
            
        </div>


        <h1>Comunicação</h1>

        <h3>Os utilizadores podem comunicar entre si, no sentido de avaliarem os voluntários e as instituições, atribuindo-lhes pontos
            como também deixarem comentarios em ambos.</h3><br><br>

        <h1>Sobre Nós</h1>
        <h3>Esta aplicação é um projeto que está a ser desenvolvido pelo grupo 24 da disciplina de Aplicações e Serviços na Web, 
            da Faculdade de Ciencias da Universidade de Lisboa. Afonso Benedito, Afonso Telles, Gonçalo Araújo e Tomás Ndlate.</h3><br><br>
    </div>
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
?>