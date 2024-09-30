<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Compra de Peças</title>
<style>
        body {
            background-color: #e8f5e9;
            font-family: Arial, sans-serif;
        }
 
        h1 {
            color: #4CAF50;
            text-align: center;
        }
 
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
 
        table,
        th,
        td {
            border: 1px solid #4CAF50;
        }
 
        th,
        td {
            padding: 12px;
            text-align: center;
        }
 
        th {
            background-color: #4CAF50;
            color: white;
        }
 
        input[type="number"] {
            width: 100px;
            padding: 5px;
        }
 
        .submit-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
 
        .submit-button:hover {
            background-color: #45a049;
        }
 
        header img {
            display: block;
            margin: 0 auto;
            width: 50%; /* Diminuir a imagem do header */
            max-width: 600px; /* Tamanho máximo */
        }
 
        footer img {
            display: block;
            margin: 20px auto;
            width: 200px; /* Aumentar a imagem do rodapé */
        }
 
        .item-image {
            width: 50px; /* Ajuste o tamanho da imagem conforme necessário */
            height: auto;
        }
 
        .coords-container {
            text-align: center;
            margin: 20px auto;
        }
 
        .coords-container label {
            margin: 10px;
            font-weight: bold;
        }
 
        .coords-container input[type="number"] {
            margin: 10px;
            width: 120px; /* Ajuste a largura conforme necessário */
            padding: 5px;
            border: 1px solid #4CAF50;
            border-radius: 4px;
        }
</style>
</head>
<body>
 
<header>
<img src="imagens/john.png" alt="Imagem do Header">
</header>
 
<h1>Lista de Compras</h1>
 
<form action="" method="POST">
<table>
<tr>
<th>Peça</th>
<th>Imagem</th>
<th>Quantidade</th>
</tr>
<tr>
<td>Parafuso</td>
<td><img src="imagens/parafuso.png" alt="Parafuso" class="item-image"></td>
<td><input type="number" name="qtd_parafuso" min="0" value="0"></td>
</tr>
<tr>
<td>Engrenagem</td>
<td><img src="imagens/engrenagem.png" alt="Engrenagem" class="item-image"></td>
<td><input type="number" name="qtd_engrenagem" min="0" value="0"></td>
</tr>
<tr>
<td>Correia</td>
<td><img src="imagens/correia.png" alt="Correia" class="item-image"></td>
<td><input type="number" name="qtd_correia" min="0" value="0"></td>
</tr>
</table>
 
    <div class="coords-container">
<label for="x_coord">Coordenada X:</label>
<input type="number" name="x_coord" id="x_coord" required>
</div>
<div class="coords-container">
<label for="y_coord">Coordenada Y:</label>
<input type="number" name="y_coord" id="y_coord" required>
</div>
 
    <button type="submit" class="submit-button">Chamar Corrida</button>
</form>
 
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "esp32");
 
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
 
    // Capturar os dados do formulário
    $x_coord = isset($_POST['x_coord']) ? $_POST['x_coord'] : null;
    $y_coord = isset($_POST['y_coord']) ? $_POST['y_coord'] : null;
    $qtd_parafuso = isset($_POST['qtd_parafuso']) ? $_POST['qtd_parafuso'] : 0;
    $qtd_engrenagem = isset($_POST['qtd_engrenagem']) ? $_POST['qtd_engrenagem'] : 0;
    $qtd_correia = isset($_POST['qtd_correia']) ? $_POST['qtd_correia'] : 0;
 
    // Verificar se as coordenadas foram fornecidas
    if (is_null($x_coord) || is_null($y_coord)) {
        die("Erro: Coordenadas não definidas.");
    }
 
    // Depuração: Exibir coordenadas
    echo "X: " . $x_coord . " | Y: " . $y_coord . "<br>";
 
    // Inserir a nova corrida na tabela 'corridas'
    $sql_corrida = "INSERT INTO corridas (x, y, status) VALUES ('$x_coord', '$y_coord', 'pendente')";
 
    if ($conn->query($sql_corrida) === TRUE) {
        $corrida_id = $conn->insert_id; // Pegar o ID da corrida recém criada
 
        // Inserir as peças relacionadas na tabela 'pecas'
        if ($qtd_parafuso > 0) {
            $sql_parafuso = "INSERT INTO pecas (corrida_id, nome_peca, quantidade) VALUES ('$corrida_id', 'Parafuso', '$qtd_parafuso')";
            $conn->query($sql_parafuso);
        }
        if ($qtd_engrenagem > 0) {
            $sql_engrenagem = "INSERT INTO pecas (corrida_id, nome_peca, quantidade) VALUES ('$corrida_id', 'Engrenagem', '$qtd_engrenagem')";
            $conn->query($sql_engrenagem);
        }
        if ($qtd_correia > 0) {
            $sql_correia = "INSERT INTO pecas (corrida_id, nome_peca, quantidade) VALUES ('$corrida_id', 'Correia', '$qtd_correia')";
            $conn->query($sql_correia);
        }
 
        echo "<p style='text-align:center;'>Corrida chamada com sucesso!</p>";
    } else {
        echo "<p style='text-align:center;'>Erro ao chamar corrida: " . $conn->error . "</p>";
    }
 
    $conn->close();
}
?>
 
<footer>
<img src="imagens/logo.png" alt="Logo no Rodapé">
</footer>
 
</body>
</html>

tem menu de contexto