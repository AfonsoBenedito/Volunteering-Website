<?php

session_start();

if (isset($_SESSION['Logged'])){
    header('Location: ./');
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST"){

    include 'includes/connection.php';

    if (isset($_POST['LoginEmail'])){

        include 'includes/login.php';

        if (isset($_SESSION['Logged'])){
            header('Location: ./');
            exit;
        }

    } else {
        
        $RUsername = htmlspecialchars($_POST['RegistoUsername']);
        $RNome = htmlspecialchars($_POST["RegistoNome"]);
        $RApelido = htmlspecialchars($_POST["RegistoApelido"]);
        $RNasc = htmlspecialchars($_POST["RegistoNascimento"]);
        $RCC = htmlspecialchars($_POST["RegistoCC"]);
        $REmail = htmlspecialchars($_POST["RegistoEmail"]);
        $RPass = htmlspecialchars($_POST["RegistoPass"]);
        $RConfPass = htmlspecialchars($_POST["RegistoConfPass"]);


        $SemProblemas = TRUE;
        $arrayErroRegisto = array();

        if ($RPass != $RConfPass){
            array_push($arrayErroRegisto, 'Password');
            $SemProblemas = FALSE;
        
        }

        //Confirmar email igual a algum dos voluntários
        $comandoRegistoVerVol = "SELECT Email, CC, Username FROM voluntariosVC19 WHERE (Email = '$REmail') OR (CC = '$RCC') OR (Username = '$RUsername');";

        $resultadoRegistoVerVol = mysqli_query($conn, $comandoRegistoVerVol);


        if (mysqli_num_rows($resultadoRegistoVerVol)!=0) {

            $SemProblemas = FALSE;

            while ($row = mysqli_fetch_array($resultadoRegistoVerVol, MYSQLI_ASSOC)){
                
                if ($row['Email'] == $REmail){

                    array_push($arrayErroRegisto, 'Email');

                }
                if ($row['CC'] == $RCC){

                    array_push($arrayErroRegisto, 'CC');

                }
                if ($row['Username'] == $RUsername){

                    array_push($arrayErroRegisto, 'Username');

                }
            }
        }


        //Confirmar email igual a algum dos voluntários
        $comandoRegistoVerInst = "SELECT * FROM instituicoesVC19 WHERE (Email = '$REmail')";

        $resultadoRegistoVerInst = mysqli_query($conn, $comandoRegistoVerInst);


        if (mysqli_num_rows($resultadoRegistoVerInst)!=0) {

            array_push($arrayErroRegisto, 'Email');


            $SemProblemas = FALSE;

        }



        

        if ($SemProblemas == TRUE) {

            $hashedPw = password_hash($RPass, PASSWORD_DEFAULT);

            $comandoRegistoInput = "INSERT INTO voluntariosVC19 (CC, Username, Email, Pass, Nome, Apelido, Nascimento, Conducao, Verificado, ImagePath) VALUES ('$RCC','$RUsername','$REmail','$hashedPw','$RNome','$RApelido','$RNasc', '0','FALSE', 'assets/images/perfilDefault.png')"; 

            $resultadoRegistoInput = mysqli_query($conn, $comandoRegistoInput);

            if ($resultadoRegistoInput){
                $msg = "Registado com sucesso";

                $_SESSION['Logged'] = TRUE;
                $_SESSION['User'] = "$REmail";
                $_SESSION['Tipo'] = "Vol";



                // $VCode = substr(md5(mt_rand()),0,15);

                // $VMensagem = "O seu código é " . $VCode . "!";
                // $VPara = $REmail;
                // $VAssunto = "Ativação de conta VoluntárioCovid19!";
                // $VDe = "De: voluntariocovid19@no-reply.com";
                // $VMensagem = "O seu código é " . $VCode . "! Por favor clique <a ref= 'verificacao.php?user=" . $REmail . "&code=" . $VCode . "'> Aqui </a> para verificar a sua Conta!";

                // mail($VPara,$VAssunto,$VMensagem,$VDe);

                header('Location: perfilProprio.php');
                exit;

            } else {

                mysqli_error($conn);

                array_push($arrayErroRegisto, 'Inesperado');
            }
        }
    }
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

        <!-- Bootstraap CSS 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    -->
        <!-- Geral CSS -->
        <link rel="stylesheet" href="styles/geral.css">
        <link rel="stylesheet" href="styles/inscrever.css">

        <link rel="shortcut icon" href="assets/icons/logoBranco.png">

        <title>Inscrever Voluntário</title>

        <style>
            @media all and (max-width: 850px){
                .zonaInscricao{
                    height: 1200px !important;
                }
            }
        </style>

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

        
        <!-- BARRA SUPERIOR -->
        <header id="header">

            <div class="logo">
                <a href="./"><img id="logotipoImg" src="assets/icons/logoPreto.png"></a>
            </div>

            <nav id='naviBar'>
                <a href="voluntarios.php"><button class="navBar">Voluntários</button></a>
                <a href="instituicoes.php"><button class="navBar">Instituições</button></a>
                <button class="navBar">Eventos</button>
                <button class="btnEntrar">Entrar</button>
            </nav>

        </header>


        <!-- CONTEUDO UNICO DA PAGINA INSCREVER VOLUNTÁRIO -->
        <main>
            <div class="zonaInscricao">
                <div class="zonaImgRegistar" style="background-image:url(assets/images/zonaRegisto.jpg);">
                    <h3>Inscrever-me como</h3>
                    <h1>Voluntário</h1>
                </div>
                <div class="formRegist">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <li class="inscLi">
                            <div class="formEsq">
                                <h3>Username</h3>
                            </div>
                            <div class="formDir">
                                <input type="text" name="RegistoUsername" placeholder="Username" Required>
                                <!-- <h6>Deu muito errado</h6> -->
                            </div>
                        </li>

                        <li class="inscLi">
                            <div class="formEsq">
                                <h3>Nome</h3>
                            </div>
                            <div class="formDir">
                                <input type="text" name="RegistoNome" placeholder="Nome" Required>
                            </div>
                        </li>

                        <li class="inscLi">
                            <div class="formEsq">
                                <h3>Apelido</h3>
                            </div>
                            <div class="formDir">
                                <input type="text" name="RegistoApelido" placeholder="Apelido" Required>
                            </div>
                        </li>

                        <li class="inscLi">
                            <div class="formEsq">
                                <h3>Data de Nascimento</h3>
                            </div>
                            <div class="formDir">
                                <?php 
                                    $today = date("Y-m-d");
                                    echo "<input type='date' name='RegistoNascimento' max='$today' Required>";
                                ?>
                            </div>
                        </li>

                        <li class="inscLi">
                            <div class="formEsq">
                                <h3>CC / Cartão Cidadão</h3>
                            </div>
                            <div class="formDir">
                                <input type="text" name="RegistoCC" placeholder="CC / Cartão Cidadão" Required>
                            </div>
                        </li>

                        <li class="inscLi">
                            <div class="formEsq">
                                <h3>Email</h3>
                            </div>
                            <div class="formDir">
                                <input type="email" name="RegistoEmail" placeholder="Email" Required>
                            </div>
                        </li>

                        <li class="inscLi">
                            <div class="formEsq">
                                <h3>Palavra-passe</h3>
                            </div>
                            <div class="formDir">
                                <input type="password" name="RegistoPass" placeholder="Palavra-passe" Required>
                            </div>
                        </li>

                        <li class="inscLi">
                            <div class="formEsq">
                                <h3>Confirmar Palavra-passe</h3>
                            </div>
                            <div class="formDir">
                                <input type="password" name="RegistoConfPass" placeholder="Confirmar Palavra-passe" Required>
                            </div>
                        </li>

                        <li>
                            <input class="btnFinalInscrever" type="submit" id="fname" name="fname" value="Inscrever-me">
                        </li>
                        
                    </form>
                </div>
                <a href="inscreverInstituicao.php"><h4>
                    Inscrever Instituição
                    <svg xmlns="http://www.w3.org/2000/svg" class="setaNextPage" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                    </svg>
                </h4></a>
            </div>

            <footer>
                <div class="sepFooter"></div>
                    <a href="./">Home</a>
                    <a href="inscreverInstituicao.php">Inscrever Instituição</a>
                    <a href="inscreverVoluntario.php">Inscrever Voluntário</a>
                    <a href="perfilVoluntario.php">Perfil Voluntario</a>
                    <a href="perfilInstituicao.php">Perfil Instituição</a>
                    <a href="editarPerfil.php">Editar Perfil Voluntario</a>
                    <a href="editarPerfilInstituicao.php">Editar Perfil Instituição</a>
            </footer>
    </main>
        
        
        <script src="scripts/base.js"></script>
        

    </body>
</html>


<?php

if (isset($arrayErroRegisto)){
    echo "<script>";

    $valorBack = (750 + (count($arrayErroRegisto) * 25)) . "px";

    echo "document.getElementsByClassName('zonaInscricao')[0].style.height = '$valorBack';";

    
    for ($i = 0; $i < count($arrayErroRegisto); $i++){

        echo "novoHErro = document.createElement('h6');";

        if ($arrayErroRegisto[$i] == 'Email'){
            echo "document.getElementsByTagName('input')['RegistoEmail'].style.borderBottom = '1px solid red';";
            echo "document.getElementsByClassName('inscLi')[5].style.marginBottom = '25px';";

            echo "novoHErro.innerText = 'Email já tem uma conta associada!';";
            echo "document.getElementsByClassName('formDir')[5].appendChild(novoHErro);";

        }
        if ($arrayErroRegisto[$i] == 'Password'){
            echo "document.getElementsByTagName('input')['RegistoPass'].style.borderBottom = '1px solid red';";
            echo "document.getElementsByTagName('input')['RegistoConfPass'].style.borderBottom = '1px solid red';";
            echo "document.getElementsByClassName('inscLi')[6].style.marginBottom = '25px';";

            echo "novoHErro.innerText = 'Passwords não correspondentes!';";
            echo "document.getElementsByClassName('formDir')[6].appendChild(novoHErro);";

        }
        if ($arrayErroRegisto[$i] == 'CC'){

            echo "document.getElementsByTagName('input')['RegistoCC'].style.borderBottom = '1px solid red';";
            echo "document.getElementsByClassName('inscLi')[4].style.marginBottom = '25px';";

            echo "novoHErro.innerText = 'CC já existente!';";
            echo "document.getElementsByClassName('formDir')[4].appendChild(novoHErro);";

        }
        if ($arrayErroRegisto[$i] == 'Username'){

            echo "document.getElementsByTagName('input')['RegistoUsername'].style.borderBottom = '1px solid red';";
            echo "document.getElementsByClassName('inscLi')[0].style.marginBottom = '25px';";

            echo "novoHErro.innerText = 'Username já existente!';";
            echo "document.getElementsByClassName('formDir')[0].appendChild(novoHErro);";

        }

        if ($arrayErroRegisto[$i] == 'Inesperado'){
            echo "console.log('inesperado');";
            echo "document.getElementsByClassName('inscLi')[7].style.marginBottom = '25px';";

            echo "novoHErro.innerText = 'Erro Inesperado, tente novamente mais tarde!';";
            echo "document.getElementsByClassName('formDir')[7].appendChild(novoHErro);";
        }
    }

    echo "</script>";
}

if (isset($msgLogin)){
    echo "<script> abrirLogin();";
    echo "document.getElementById('ErroLogin').style.setProperty('color','red', 'important');";
    echo "document.getElementById('ErroLogin').innerText = '$msgLogin';";
    echo "</script>";
}


?>