<html><body>

    <?php

        $dbhost = "appserver-01.alunos.di.fc.ul.pt"; //base de dados
        $dbuser = "asw024"; // username
        $dbpass = "agentebec"; //palavra passe de acesso ao MySQL
        $dbname = "asw024";

        // Cria a ligação à BD
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        // Verifica a ligação à BD
        if (mysqli_connect_error()) {
            die("Database connection failed: " . mysqli_connect_error());
        }
    ?>

</body></html>