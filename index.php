<?php

    session_start();

    if (isset($_POST['LoginEmail'])){
        include 'Login.php';
    }
    

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- FONTS LINKS -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100&display=swap" rel="stylesheet">

        <!-- Geral CSS -->
        <link rel="stylesheet" href="styles/geral.css">

        <!-- Home CSS -->
        <link rel="stylesheet" href="styles/home.css">

        <link rel="shortcut icon" href="assets/Icons/logoBranco.png">

        <title>VoluntárioCOVID19 - Home</title>

    </head>
    
    <body>

        <!------------- LOGIN --------------->
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
        <!-------------------------------------->


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
        

        <!-- HOME ECRAN PRINCIPAL & BACKGROUND -->
        <div class="background">
            <div class="principalText">
                <h1 class="eEs">
                    <li id="eE">Q</li> <li id="eE">u</li> <li id="eE">e</li> <li id="eE">r</li> <li>&nbsp;</li>
                    <li id="eE">F</li> <li id="eE">a</li> <li id="eE">z</li> <li id="eE">e</li> <li id="eE">r</li> <li>&nbsp;</li> <li id="eE">a</li> <li>&nbsp;</li>
                    <li id="eE">D</li> <li id="eE">i</li> <li id="eE">f</li> <li id="eE">e</li> <li id="eE">r</li> <li id="eE">e</li> <li id="eE">n</li> <li id="eE">ç</li> <li id="eE">a</li> <li id="eE">?</li>
                    <br>
                    <li id="eE">V</li> <li id="eE">o</li> <li id="eE">l</li> <li id="eE">u</li> <li id="eE">n</li> <li id="eE">t</li> <li id="eE">á</li> <li id="eE">r</li> <li id="eE">i</li> <li id="eE">o</li>
                    <li id="eE">C</li> <li id="eE">O</li> <li id="eE">V</li> <li id="eE">I</li> <li id="eE">D</li> <li id="eE">1</li> <li id="eE">9</li>
                </h1>
                <h3>Começa já a melhorar vidas.</h3>
                <br>
                <br>
                <a href="inscreverVoluntario.php" class="btnPrincipal">Tornar-me Voluntário</a>
            </div>
        </div>


        <!-- BARRA SUPERIOR -->
        <header id="header">

            <div class="logo">
                <img id="logotipoImg" src="assets/Icons/logoBranco.png">
            </div>

            <nav id='naviBar'>
                <a href="voluntarios.php"><button class="navBar">Voluntários</button></a>
                <a href="instituicoes.php"><button class="navBar">Instituições</button></a>
                <button class="navBar">Eventos</button>
                <button class="btnEntrar">Entrar</button> <!--tambem vai poder ser btn perfil-->
                <!-- <button class="perfilButton">foto
                    <div class="navImagemPerfil">
                        <img src="assets/Imagens/perfilDefault.png">
                    </div>
                    <div class="apontadorPerfil"></div>
                </button> -->
            </nav>

        </header>


        <!-- CONTEUDO UNICO DA PAGINA HOME -->
        <main>
            <div class="resumoApp">
                <h1>O que é a VoluntárioCOVID19?</h1>

                <p>A VoluntárioCOVID19 tem como objetivo fazer a gestão de uma bolsa de voluntários que permita às
                    instituições registadas encontrar e recrutar voluntários. Inscreva-se se pretender participar em ações de voluntariado 
                    e de solidariedade COVID19. 
                </p>
                <br>
                <p><a href="saibaMais.php">Sabe mais aqui!</a></p>
            </div>
            <div class="principalBlocks">
                <li>
                    <div class="imgCartao um"></div>
                    <a href="inscreverVoluntario.php" class="btnCartao">Ser Voluntário</a>
                    <div class="zonaTextoCartao">
                        <div class="centerTextCartao">
                            <h1 id="imgCartaoVolH">Quer tornar-se voluntário?</h1>
                            <p>Comece já a ajudar!</p>
                        </div>
                    </div>
                    
                    
                </li>
                <li>
                    <div class="imgCartao dois"></div>
                    <a href="inscreverInstituicao.php" class="btnCartao">Inscrever Instituição</a>
                    <div class="zonaTextoCartao">
                        <div class="centerTextCartao">
                            <h1 id="imgCartaoInstH">Instituição organizadora de ações de voluntariado?</h1>
                            <p>Não perca tempo!</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="imgCartao tres"></div>
                    <a class="btnCartao">Criar Iniciativa</a>
                    <div class="zonaTextoCartao">
                        <div class="centerTextCartao">
                            <h1>Planear uma iniciativa local de voluntariado?</h1>
                            <p>Do que está à espera?</p>
                        </div>
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
        <script src="scripts/home.js"></script>
   
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


    // BtnPrincipal e Cartões


    echo "<script>";

    echo "document.getElementsByClassName('btnPrincipal')[0].innerText = 'Ações Voluntariado';";

    echo "document.getElementsByClassName('btnPrincipal')[0].href = './';";

    echo "document.getElementsByClassName('btnCartao')[0].innerText = 'Ver Voluntarios';";

    echo "document.getElementsByClassName('btnCartao')[0].href = 'voluntarios.php';";

    echo "document.getElementById('imgCartaoVolH').innerText = 'Quer conhecer outros Voluntários?';";

    echo "document.getElementsByClassName('btnCartao')[1].innerText = 'Ver Instituições';";

    echo "document.getElementsByClassName('btnCartao')[1].href = 'instituicoes.php';";

    echo "document.getElementById('imgCartaoInstH').innerText = 'Quer conhecer as Instituições que participam?';";

    echo "</script>";
    
} else if (isset($msgLogin)){
    echo "<script> abrirLogin();";
    echo "document.getElementById('ErroLogin').style.setProperty('color','red', 'important');";
    echo "document.getElementById('ErroLogin').innerText = '$msgLogin';";
    echo "</script>";
}
?>