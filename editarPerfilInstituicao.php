<?php
    
session_start();

if (!(isset($_SESSION['Logged'])) or $_SESSION['Tipo'] != 'Inst'){
    header('Location: ./');
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
        <link rel="stylesheet" href="./styles/definicoes.css">

        <title>Editar Instituição</title>

        <link rel="shortcut icon" href="assets/icons/logoBranco.png">

    </head>
    
    <body>

        <header id="header">

            <div class="logo">
                <a href="./"><img id="logotipoImg" src="./assets/icons/logoPreto.png"></a>
                
            </div>

            <nav id="naviBar">
                <a href="voluntarios.php"><button class="navBar">Voluntários</button></a>
                <a href="instituicoes.php"><button class="navBar">Instituições</button></a>
                <button class="navBar">Eventos</button>
            </nav>

        </header>      

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

        </header>

        <div class="backgroundPopUpAlterarFoto"></div>
        <div class="popUpAlterarFoto">
            <li><h3>Alterar Foto Perfil</h3></li>
            <li>
                <form action="actions/alterarFotoInstituicao.php" method="post" id="formAlterarFoto" enctype="multipart/form-data">
                    <label for="carregarFoto" id="carregarFotoLabel"><h4>Carregar foto</h4></label>
                    <input type="file" name="carregarFoto" id="carregarFoto" accept=".jpg,.jpeg,.png" >
                </form>
            </li>
            <li><h4>Eliminar foto atual</h4></li>
            <li class="cancelarAlterarFoto"><h4>Cancelar</h4></li>
        </div>

        <main>

            <div class="msgInformativaAlteracoes"><h4 class="alteracoesInfo"></h4></div>  

            <div class="conteudoPrincipal" style="height: 600px !important;">
                <div class="btnPrincipaisEditar">
                    <li id="editarPerfilInstituicao" class="btnUsed">
                        <h3>Editar Perfil</h3>
                    </li>
                    <li id="editarDadosInstituicao">
                        <h3>Dados utilizador</h3>
                    </li>
                    <li id="editarPassInstituicao">
                        <h3>Alterar palavra-passe</h3>
                    </li>
                    <li id="editarEmailInstituicao">
                        <h3>Alterar email</h3>
                    </li>
                </div>

                <!-- ZONA EDIÇÃO PERFIL -->
                <div class="zonaEdicaoPerfil">

                    <form action="actions/alteracoesPerfilInstituicao.php" method="post"> <!-- Perceber -->
                        <li>
                            <div class="zonaEdicaoEsq">
                            <div class="fotoPerfilDef"><img class="editarFotoH" src="assets/images/perfilDefault.png"></div>
                            </div>
                            <div class="zonaEdicaoDir">
                                <h1 class="nomeUserDef">Tomás Ndlate</h1>
                                <h4 class="btnAlterarFoto">Alterar foto de perfil</h4>
                            </div>
                        </li>
                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Nome da Instituição</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="text" class="placeChange" name="alterarNomeInstituicao" placeholder="Alterar Nome">
                                <br>
                                <p>Ajuda as pessoas a encontrar a tua instituição. Este será o nome do teu
                                    perfil.
                                </p>
                            
                                <!-- <p>VoluntárioCOVID19, ajuda o mundo.</p> -->
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2></h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <h6>Sobre a Instituição</h6>
                                <p>Ajuda os utilizadores a perceber e conhecer melhor os valores e objetivos da Instituição,
                                    de modo a proporcionar-lhes uma maior confiança perante esta instituição.
                                </p>
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Descrição</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <textarea class="placeChange" name="alterarDescricao"></textarea>
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Telemóvel</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="tel" class="placeChange" name="alterarTelemovel" placeholder="Telefone">
                            </div>
                        </li>

                        <li><div class="zonaEdicaoEsq"><h2></h2></div><div class="zonaEdicaoDir"><input class="editarSubmit" type="submit"></div></li>
                    </form>
                </div>


                <!-- ZONA EDIÇÃO DADOS -->
                <div class="zonaEdicaoDados">
                    <form action="actions/alteracoesDadosInstituicao.php" method="post">
                        <li>
                            <div class="zonaEdicaoEsq">
                            <div class="fotoPerfilDef"><img class="editarFotoH" src="assets/images/perfilDefault.png"></div>
                            </div>
                            <div class="zonaEdicaoDir">
                                <h1 class="nomeUserDef">Tomás Ndlate</h1>
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Distrito</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="text" class="placeChange" name="alterarDistrito" placeholder="Distrito">
                            </div>
                        </li>
                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Concelho</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="text" class="placeChange" name="alterarConcelho" placeholder="Concelho">
                            </div>
                        </li>
                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Freguesia</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="text" class="placeChange" name="alterarFreguesia" placeholder="Freguesia">
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Morada</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="text" class="placeChange" name="alterarMorada" placeholder="Morada">
                            </div>
                        </li>


                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Nome do Representante</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="text" class="placeChange" name="alterarNomeRepresentante" placeholder="Nome do Representante">
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>E-Mail do Representante</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="email" class="placeChange" name="alterarEmailRepresentante" placeholder="E-Mail do Representante">
                            </div>
                        </li>

                        <li><div class="zonaEdicaoEsq"><h2></h2></div><div class="zonaEdicaoDir"><input class="editarSubmit" type="submit"></div></li>
                    </form>
                </div>

                <!-- ZONA EDIÇÃO PASSWORD -->
                <div class="zonaEdicaoPass">
                    <form action="actions/alteracoesPassInstituicao.php" method="post">
                        <li>
                            <div class="zonaEdicaoEsq">
                            <div class="fotoPerfilDef"><img class="editarFotoH" src="assets/images/perfilDefault.png"></div>
                            </div>
                            <div class="zonaEdicaoDir">
                                <h1 class="nomeUserDef">Tomás Ndlate</h1>
                            </div>
                        </li>
                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Palavra-passe atual</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="password" name="alterarPassAtual" placeholder="Palavra-passe atual">
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Nova palavra-passe</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="password" name="alterarPassNova" placeholder="Nova palavra-passe">
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Confirmar nova palavra-passe</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="password" name="alterarPassNovaConf" placeholder="Confirmar nova palavra-passe">
                            </div>
                        </li>

                        <li><div class="zonaEdicaoEsq"><h2></h2></div><div class="zonaEdicaoDir"><input class="editarSubmit" type="submit"></div></li>
                    </form>
                </div>

                <!-- ZONA EDIÇÃO EMAIL -->
                <div class="zonaEdicaoEmail">
                    <form action="actions/alteracoesEmailInstituicao.php" method="post">
                        <li>
                            <div class="zonaEdicaoEsq">
                            <div class="fotoPerfilDef"><img class="editarFotoH" src="assets/images/perfilDefault.png"></div>
                            </div>
                            <div class="zonaEdicaoDir">
                                <h1 class="nomeUserDef">Tomás Ndlate</h1>
                            </div>
                        </li>
                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Novo email</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="email" name="alterarEmail" placeholder="Novo Email">
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Confirmar Novo email</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="email" name="alterarEmailConf" placeholder="Confirmar Novo Email">
                            </div>
                        </li>

                        <li>
                            <div class="zonaEdicaoEsq">
                                <h2>Password</h2>
                            </div>
                            <div class="zonaEdicaoDir">
                                <input type="password" name="alterarEmailPass" placeholder="Password">
                            </div>
                        </li>

                        <li><div class="zonaEdicaoEsq"><h2></h2></div><div class="zonaEdicaoDir"><input class="editarSubmit" type="submit"></div></li>
                    </form>
                </div>

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
        
        
        <!--<script src="base.js"></script>-->
        <script src="scripts/perfilAtivo.js"></script>
        <script src="scripts/editarInstituicao.js"></script>
        

    </body>
    </html>

<?php

include 'includes/connection.php';

$UserEmail = $_SESSION['User'];

$comando = "SELECT Email, Nome, Telemovel, Distrito, Concelho, Freguesia, Morada, 
            Descricao, NomeRepresentante, EmailRepresentante, ImagePath FROM instituicoesVC19 WHERE (Email = '$UserEmail');";

$query = mysqli_query($conn, $comando);

$resultado = mysqli_fetch_array($query, MYSQLI_ASSOC);

$Nome = $resultado['Nome'];
$Telemovel = $resultado['Telemovel'];
$Distrito = $resultado['Distrito'];
$Concelho = $resultado['Concelho'];
$Freguesia = $resultado['Freguesia'];
$Morada = $resultado['Morada'];
$Descricao = $resultado['Descricao'];
$NomeRepresentante = $resultado['NomeRepresentante'];
$EmailRepresentante = $resultado['EmailRepresentante'];
$Imagem = $resultado['ImagePath'];

echo "<script>"; 

if ($Nome != NULL){
    echo "document.getElementsByClassName('placeChange')[0].value='$Nome';";
}
if ($Descricao != NULL){
    echo "document.getElementsByClassName('placeChange')[1].value='$Descricao';";
}
if ($Telemovel != NULL){
    echo "document.getElementsByClassName('placeChange')[2].value='$Telemovel';";
}
if ($Distrito != NULL){
    echo "document.getElementsByClassName('placeChange')[3].value='$Distrito';";
}
if ($Concelho != NULL){
    echo "document.getElementsByClassName('placeChange')[4].value='$Concelho';";
}
if ($Freguesia != NULL){
    echo "document.getElementsByClassName('placeChange')[5].value='$Freguesia';";
}
if ($Morada != NULL){
    echo "document.getElementsByClassName('placeChange')[6].value='$Morada';";
}
if ($NomeRepresentante != NULL){
    echo "document.getElementsByClassName('placeChange')[7].value='$NomeRepresentante';";
}
if ($EmailRepresentante != NULL){
    echo "document.getElementsByClassName('placeChange')[8].value='$EmailRepresentante';";
}

echo "document.getElementsByClassName('nomeUserDef')[0].innerText = '$Nome';";
echo "document.getElementsByClassName('nomeUserDef')[1].innerText = '$Nome';";
echo "document.getElementsByClassName('nomeUserDef')[2].innerText = '$Nome';";
echo "document.getElementsByClassName('nomeUserDef')[3].innerText = '$Nome';";

echo "document.getElementsByClassName('editarFotoH')[0].src = '$Imagem';";
echo "document.getElementsByClassName('editarFotoH')[1].src = '$Imagem';";
echo "document.getElementsByClassName('editarFotoH')[2].src = '$Imagem';";
echo "document.getElementsByClassName('editarFotoH')[3].src = '$Imagem';";

echo "</script>";

include 'includes/confirmarAlteracoes.php';

if (isset($_SESSION['Logged'])) {

    //Header

    echo "<script>";

    include "perfilAtivo.php";

    echo "</script>";

    echo "<script src='scripts/perfilAtivo.js'></script>";

}

?>