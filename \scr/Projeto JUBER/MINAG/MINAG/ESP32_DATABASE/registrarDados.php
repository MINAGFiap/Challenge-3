<?php

    $hostname = "localhost";
    $database = "esp32";
    $username = "root";
    $password = "";

    // Cria uma conexão com o banco de dados
    $conn = mysqli_connect($hostname, $username, $password, $database);

    // Checa se a conexão foi feita corretamente
    if (!$conn) {
        die("Falha na conexão: " . mysqli_connect_error());
    }

    echo "Conexão bem-sucedida.";

    // Mostrar quando dados estão sendo recebidos, por exemplo, array(3) {  ["celula"]=>  string(6) "(1, 2)" ["bssid"]=>string(14) "2A:3B:4C:5D:6E"  ["rssi"]=> string(3) "-70"}
    // var_dump($_POST);

    if (isset($_POST["celula"]) && isset($_POST["bssid"]) && isset($_POST["rssi"])) {
        $c = $_POST["celula"];
        $b = $_POST["bssid"];
        $r = $_POST["rssi"];
    
        $sql = "INSERT INTO redes (celula, bssid, rssi) VALUES ('$c', '$b', $r);";
        // Para visualizar o quary gerada
        echo "SQL: " . $sql . "<br>";
    
        if (mysqli_query($conn, $sql)) {
            echo "<br>" . "Novo registro criado com sucesso!";
        } else {
            echo "Erro: " . mysqli_error($conn); // Exibe erro detalhado
        }
    }
    mysqli_close($conn);
?>