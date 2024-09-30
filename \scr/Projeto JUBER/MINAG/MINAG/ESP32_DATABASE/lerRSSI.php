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
    //var_dump($_POST);

    // Ler o corpo da requisição JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true); // Decodifica o JSON para array associativo

    if ($data && isset($data['esp_mac']) && isset($data['dados'])) {
        $esp_mac = $data['esp_mac']; // Endereço MAC do ESP32
        $dados = json_encode($data['dados']); // Os dados da varredura WiFi no formato JSON

        // Preparar a query para inserir ou atualizar os dados
        $sql = "INSERT INTO leitura (esp_mac, dados) VALUES ('$esp_mac', '$dados')
                ON DUPLICATE KEY UPDATE dados='$dados'";

        if (mysqli_query($conn, $sql)) {
            echo "<br>Registro criado ou atualizado com sucesso!";
        } else {
            echo "<br>Erro ao inserir ou atualizar dados: " . mysqli_error($conn);
        }
    } else {
        echo "Nenhum dado válido recebido.";
    }

    mysqli_close($conn);
?>