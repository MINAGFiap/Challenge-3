<?php
// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $id = $_POST['id'];
    $dados_json = $_POST['dados'];

    // Verifica se o campo 'dados' está vazio
    if (empty($dados_json)) {
        // Atribui o valor padrão se o campo estiver vazio
        $dados_json = json_encode(array('situacao' => 'livre', 'x' => 1, 'y' => 1));
    }

    // Converte a string JSON em um array para validação
    $dados_array = json_decode($dados_json, true);

    // Verifica se o JSON é válido
    if (json_last_error() === JSON_ERROR_NONE) {
        // Conexão com o banco de dados
        $hostname = "localhost";
        $database = "esp32";
        $username = "root";
        $password = "";    

        // Cria a conexão
        $conn = new mysqli($hostname, $username, $password, $database);

        // Verifica se a conexão foi bem-sucedida
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        // SQL para inserir ou atualizar dados caso o id já exista
        $sql = "INSERT INTO operadores (id, dados) 
                VALUES ('$id', '$dados_json') 
                ON DUPLICATE KEY UPDATE dados='$dados_json'";

        // Executa a query
        if ($conn->query($sql) === TRUE) {
            echo "Dados inseridos ou atualizados com sucesso!";
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }

        // Fecha a conexão
        $conn->close();
    } else {
        echo "Erro: O valor fornecido não é um JSON válido.";
    }
}
?>
