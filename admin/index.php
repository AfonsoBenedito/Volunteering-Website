<?php

include '../includes/connection.php';

$levouPost = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST['adminSearch'])){

        $obj = 'Vol';
    
        $levouPost = TRUE;
    
        $adminTexto = $_POST["adminSearch"];
        //Username, Nome, Apelido, CC, Email, Telemovel, Distrito, Concelho, Freguesia
    
        $adminCheckMasc = $_POST["adminCheckMasc"];
        $adminCheckFem = $_POST["adminCheckFem"];
        $adminCheckOutro = $_POST["adminCheckOutro"];
        //Genero
    
        $adminCheckConducao = $_POST["adminCartaConducao"];
        //Carta de Condução
    
        $adminIdade = $_POST['adminIdade'];
        //Nascimento
    
        $arrayPesquisados = array();
    
        $arrayGenero = array();
    
        $comandoFiltro = "SELECT CC, Username, Email, Nome, Apelido, Telemovel, Genero, Nascimento, Conducao, Distrito, Concelho, Freguesia, ImagePath FROM voluntariosVC19 WHERE   ";
    
    
        if ($adminTexto != ""){
            $comandoFiltro .= " ((Username LIKE '%$adminTexto%') OR (Nome LIKE '%$adminTexto%') OR
                (Apelido LIKE '%$adminTexto%') OR (CC LIKE '%$adminTexto%') OR 
                (Email LIKE '%$adminTexto%') OR (Telemovel LIKE '%$adminTexto%') OR 
                (Distrito LIKE '%$adminTexto%') OR (Concelho LIKE '%$adminTexto%') OR 
                (Freguesia LIKE '%$adminTexto%')) AND";
    
            array_push($arrayPesquisados, '<h4>Pesquisa:</h4><h1> ' . $adminTexto . ';</h1> ');
        }

        if ($adminCheckMasc == "M" or $adminCheckFem == "F" or $adminCheckOutro == "O"){
            array_push($arrayPesquisados, '<h4>Género:</h4> ');
        }
        if ($adminCheckMasc == "M"){
            array_push($arrayGenero, " (Genero = 'M')");
    
            array_push($arrayPesquisados, '<h1>Masculino;</h1> ');
        }
        if ($adminCheckFem == "F"){
            array_push($arrayGenero, " (Genero = 'F')");
    
            array_push($arrayPesquisados, '<h1>Feminino;</h1> ');
        }
        if ($adminCheckOutro == "O"){
            array_push($arrayGenero, " ((Genero = 'O') OR (Genero IS NULL))");
    
            array_push($arrayPesquisados, '<h1>Outro;</h1> ');
        }
    
        for ($i = 0; $i < count($arrayGenero); $i++){
            if ($i == 0){
                $comandoFiltro .= "(";
            }
            if ($i < count($arrayGenero) - 1){
                $comandoFiltro .= $arrayGenero[$i] . " OR";
            } else {
                $comandoFiltro .= $arrayGenero[$i] . ") AND";
            }
        }
    
        if ($adminCheckConducao == 'T'){
            $comandoFiltro .= " ((Conducao = '1') OR (Conducao IS NULL)) AND";
    
            array_push($arrayPesquisados, '<h4>Carta de Condução:</h4> <h1>Sim;</h1> ');
    
        } else if ($adminCheckConducao == 'F'){
            $comandoFiltro .= " ((Conducao = '0') OR (Conducao IS NULL)) AND";
    
            array_push($arrayPesquisados, '<h4>Carta de Condução:</h4> <h1>Não;</h1> ');
        }
    
    
    
        if ($adminIdade == '1-18'){
    
            $primeiroVal = 1*365;
            $segundoVal = (19*365) - 1;
    
            $comandoFiltro .= " ((SELECT DATEDIFF(CURDATE(), NASCIMENTO)) BETWEEN $primeiroVal AND $segundoVal) AND";
    
            array_push($arrayPesquisados, '<h4>Idade:</h4> <h1>1-18;</h1> ');
    
        } else if ($adminIdade == '19-29'){
    
            $primeiroVal = 19*365;
            $segundoVal = (30*365) - 1;
    
            $comandoFiltro .= " ((SELECT DATEDIFF(CURDATE(), NASCIMENTO)) BETWEEN $primeiroVal AND $segundoVal) AND";
    
            array_push($arrayPesquisados, '<h4>Idade:</h4> <h1>19-29;</h1> ');
    
        }else if ($adminIdade == '30-45'){
    
            $primeiroVal = 30*365;
            $segundoVal = (46*365) - 1;
    
            $comandoFiltro .= " ((SELECT DATEDIFF(CURDATE(), NASCIMENTO)) BETWEEN $primeiroVal AND $segundoVal) AND";
    
            array_push($arrayPesquisados, '<h4>Idade:</h4> <h1>30-45;</h1> ');
            
        }else if ($adminIdade == '46-60'){
    
            $primeiroVal = 46*365;
            $segundoVal = (61*365) - 1;
    
            $comandoFiltro .= " ((SELECT DATEDIFF(CURDATE(), NASCIMENTO)) BETWEEN $primeiroVal AND $segundoVal) AND";
    
            array_push($arrayPesquisados, '<h4>Idade:</h4> <h1>46-60;</h1> ');
            
        }else if ($adminIdade == '61+'){
    
            $primeiroVal = 61*365;
    
            $comandoFiltro .= " ((SELECT DATEDIFF(CURDATE(), NASCIMENTO)) > $primeiroVal) AND";
    
            array_push($arrayPesquisados, '<h4>Idade:</h4> <h1>61+;</h1> ');
            
        }
    
        
        $comandoFiltro = substr($comandoFiltro, 0, -3);
    
        // print($comandoFiltro);
    
    
    
    } else if (isset($_POST['adminSearchInst'])) {


        $obj = 'Inst';

        $levouPost = TRUE;

        $adminSearchIns = htmlspecialchars($_POST['adminSearchInst']);

        $arrayPesquisadosIns = array();

        $comandoFiltro = "SELECT Nome, Email, Telemovel, NomeRepresentante, EmailRepresentante, Morada, Distrito, Concelho, Freguesia, ImagePath FROM instituicoesVC19 WHERE   ";


        if ($adminSearchIns != ""){
            $comandoFiltro .= " ((Email LIKE '%$adminSearchIns%') OR (Nome LIKE '%$adminSearchIns%') OR
                (Telemovel LIKE '%$adminSearchIns%') OR (Morada LIKE '%$adminSearchIns%') OR 
                (EmailRepresentante LIKE '%$adminSearchIns%') OR (NomeRepresentante LIKE '%$adminSearchIns%') OR 
                (Distrito LIKE '%$adminSearchIns%') OR (Concelho LIKE '%$adminSearchIns%') OR 
                (Freguesia LIKE '%$adminSearchIns%')) AND";

            array_push($arrayPesquisadosIns, '<h4>Pesquisa:</h4><h1> ' . $adminSearchIns . ';</h1> ');
        
        }
        $comandoFiltro = substr($comandoFiltro, 0, -3);

    }

} else if (isset($_GET['obj'])){

    $obj = $_GET['obj'];
    
} else {
    $obj = 'Vol';
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin VC19</title>

    <link rel="stylesheet" href="../styles/admin.css">

    <!-- FONTS LINKS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="../assets/icons/logoBranco.png">

</head>
<body>

    <nav>
        <li>
            <img src="../assets/icons/logoAdmin.png">
        </li>

        <li>
            <a href="./?obj=Vol">
                <img src="../assets/icons/voluntarios.png">
                <h4>Voluntários</h4>
            </a>
        </li>

        <li>
            <a href="./?obj=Inst">
                <img src="../assets/icons/instituicoes.png">
                <h4>Instituições</h4>
            </a>
        </li>

        <li>
            <a href="./?obj=Acoes">
                <img src="../assets/icons/acoes.png">
                <h4>Iniciativas</h4>
            </a>
        </li>

        <li>
            <a href="../" target="_blank">
                <img src="../assets/icons/logoPreto.png">
                <h4>Visitar Site</h4>
            </a>
        </li>

        <li class="terminarAdmin"></li>
        

    </nav>

    <main>

        <?php
            // print($obj);

            if ($obj == 'Vol'){



                $varAction = htmlspecialchars($_SERVER["PHP_SELF"]);

                $varAction .= "?obj=Vol";

                $resPesq = implode(" ",$arrayPesquisados);

                echo '<div class="searchVol">
                    <div class="zonaPesquisa">
                        <form method="post" action="' . $varAction . '">

                            <div class="searchPart">
                                <input type="text" class="searchBar" name="adminSearch" placeholder="Pesquise">
                                
                                    <button type="submit" class="searchBarSubmit">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="lupaSearch"
                                            class="bi bi-search" 
                                            viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                        </svg>
                                    </button>
                                
                            </div>

                            <div class="pesqAvancada"><h5 id="abrePesquisa" > <span class="setaPesqAvancada">&#9654;</span> Pesquisa avançada</h5></div>

                            <div class="pesqAvancadaPopUp">

                                <div class="pesqGenero">

                                    <h3>Género</h3>

                                    <input type="checkbox" name="adminCheckMasc" value="M">
                                    <label for="adminCheckMasc"> Masculino </label>

                                    <br>

                                    <input type="checkbox" name="adminCheckFem" value="F">
                                    <label for="adminCheckFem"> Feminino </label>

                                    <br>

                                    <input type="checkbox" name="adminCheckOutro" value="O">
                                    <label for="adminCheckOutro"> Outro </label>

                                </div>

                                <div class="pesqCartaC">

                                    <h3>Carta Condução</h3>

                                    <input type="checkbox" name="adminCartaConducao" id="adminCartaConducao" value="T">
                                    <label for="adminCartaConducao" id="labelCartaC"> Indiferente. </label>
                                
                                </div>
                                
                                <div class="pesqIdade">


                                    <h3>Idade</h3>

                                    <select name="adminIdade">
                                        <option value="ind"> Indiferente </option>
                                        <option value="1-18"> 1 - 18 </option>
                                        <option value="19-29"> 19 - 29 </option>
                                        <option value="30-45"> 30 - 45 </option>
                                        <option value="46-60"> 46 - 60 </option>
                                        <option value="61+"> 61+ </option>
                                    </select>

                                </div>

                                <div class="pesqSubmit">
                                    <input type="submit">
                                </div>

                            </div>

                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
                            <script src="../scripts/adminSearch.js"></script>
                            
                        </form>
                    </div>
                    <div class="zonaMostrarPesquisa">' . $resPesq . '</div>
                    </div>';

            } else if ($obj == 'Inst'){

                $resPesqIns = implode(" ",$arrayPesquisadosIns);

                $varAction = htmlspecialchars($_SERVER["PHP_SELF"]);

                $varAction .= "?obj=Inst";


                echo '<div class="searchVol">
                    <div class="zonaPesquisa">
                        <form method="post" action="' . $varAction . '">

                            <div class="searchPart">
                                <input type="text" class="searchBar" name="adminSearchInst" placeholder="Pesquise">
                                
                                    <button type="submit" class="searchBarSubmit">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="lupaSearch"
                                            class="bi bi-search" 
                                            viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                        </svg>
                                    </button>
                                
                            </div>

                        </form>
                    <div class="zonaMostrarPesquisaInst">' . $resPesqIns . '</div>
                    </div>';



            }
        ?>

        <div class="mostrarSQL">

            <table>
                <thead>
                    <?php 
                        if ($obj == 'Vol'){

                            echo "<tr>
                                    <th>Foto</th>
                                    <th>Username</th>
                                    <th>Nome do Utilizador</th>
                                    <th>Cartão Cidadão</th>
                                    <th>Email</th>
                                    <th>Telemóvel</th>
                                    <th>Género</th>
                                    <th>Data Nascimento</th>
                                    <th>Carta Condução</th>
                                    <th>Localidade</th>
                                </tr>";

                        } else if ($obj == 'Inst'){

                            echo "<tr>
                                    <th>Foto</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telemóvel</th>
                                    <th>Nome Representante</th>
                                    <th>Email Representante</th>
                                    <th>Morada</th>
                                    <th>Localidade</th>
                                </tr>";
                        }
                    ?>
                </thead>
                <tbody>

                    <?php

                        if ($obj == 'Vol'){

                            if ($levouPost == FALSE){

                                $comandoSql = "SELECT CC, Username, Email, Nome, Apelido, Telemovel, Genero, Nascimento, 
                                                Conducao, Distrito, Concelho, Freguesia, ImagePath FROM voluntariosVC19";

                            } else {

                                $comandoSql = $comandoFiltro;
                            }
                            
                            // print($comandoSql);

                            $res = mysqli_query($conn, $comandoSql);

                    
                            if ($res){

                                
                    
                                while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                                    // $html[] = "<tr><td>" .
                                    // implode("</td><td>", $row) .
                                    // "</td></tr>";

                                    if ($row['Genero'] == 'M'){

                                        $genero = " class='maleTd'>&#9794;";

                                    } else if ($row['Genero'] == 'F') {

                                        $genero = " class='femTd'>&#9792;";

                                    } else {

                                        $genero = " class='otherTd'>&#9906;";

                                    }


                                    if ($row['Conducao'] == '1'){

                                        $conducao = " class='simTd'>&#10003;";

                                    } else if ($row['Conducao'] == '0') {

                                        $conducao = " class='naoTd'>&#10005;";

                                    } else {
                                        $conducao = " class='interTd'>&#63;";
                                    }

                                    $userPage = "../perfilVoluntario?Username=" . $row['Username'];

                    
                                    $html[] = "<tr>" .
                                    "<td><a target='_blank' href= " . $userPage . "><div class='posicaoFotoPerfil'><img src= ../".$row['ImagePath']."></div></a></td>".
                                    "<td><a target='_blank' href= " . $userPage . ">".$row['Username']."</a></td>".
                                    "<td>".$row['Nome']." ".$row['Apelido']."</td>".
                                    "<td>".$row['CC']."</td>".
                                    "<td>".$row['Email']."</td>".
                                    "<td>".$row['Telemovel']."</td>".
                                    "<td ".$genero."</td>".
                                    "<td>".$row['Nascimento']."</td>".
                                    "<td ".$conducao."</td>".
                                    "<td>".$row['Distrito'].", ".$row['Concelho'].", ".$row['Freguesia']."</td>".
                                    "</tr>";
                                }
                    
                                    $html = implode("\n", $html);
                    
                                    // echo "<script>";
                                    // echo "document.getElementsByClassName('mostrarSQL')[0].appendChild('$html');";
                                    // echo "</script>";
                                    echo $html;
                    
                    
                    
                            }else {
                                mysqli_error($conn);
                                echo "Sem resultados!";
                            }

                        } else if ($obj == 'Inst' or isset($_POST['adminNomeRepr'])){

                            if ($levouPost == FALSE){

                                $comandoSql = "SELECT Nome, Email, Telemovel, NomeRepresentante, EmailRepresentante, Morada, 
                                               Distrito, Concelho, Freguesia, ImagePath FROM instituicoesVC19";
                            
                            } else {

                                $comandoSql = $comandoFiltro;

                            }

                            $res = mysqli_query($conn, $comandoSql);

                            if ($res){

                                while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {

                                    $instPage = "../perfilInstituicao?Nome=" . $row['Nome'];
                    
                                    $html[] = "<tr>" .
                                    "<td><a target='_blank' href= " . $instPage . "><div class='posicaoFotoPerfil'><img src= ../".$row['ImagePath']."></div></a></td>".
                                    "<td><a target='_blank' href= " . $instPage . ">".$row['Nome']."</a></td>".
                                    "<td>".$row['Email']."</td>".
                                    "<td>".$row['Telemovel']."</td>".
                                    "<td>".$row['NomeRepresentante']."</td>".
                                    "<td>".$row['EmailRepresentante']."</td>".
                                    "<td>".$row['Morada']."</td>".
                                    "<td>".$row['Distrito'].", ".$row['Concelho'].", ".$row['Freguesia']."</td>".
                                    "</tr>";
                                }

                                $html = implode("\n", $html);

                                echo $html;

                            } else {
                                mysqli_error($conn);
                                echo "Sem resultados!";
                            }
                            
                        }
                    ?>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>