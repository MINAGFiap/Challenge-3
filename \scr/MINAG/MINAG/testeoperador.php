<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esp32";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_GET['finalizar'])) {
    $id = $_GET['finalizar'];
    $sql = "DELETE FROM corridas WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Corrida finalizada com sucesso!";
    } else {
        echo "Erro ao finalizar a corrida: " . $conn->error;
    }
}

// Consulta SQL para obter as corridas pendentes e suas localizações
$sql = "SELECT id, x, y, status FROM corridas WHERE status = 'pendente'";
$corridasResult = $conn->query($sql);

// Variável para armazenar o caminho do percurso
$caminhoPercorrido = [];

// Verifica se há uma corrida selecionada e busca o percurso correspondente
if (isset($_GET['id_corrida'])) {
    $id_corrida = $_GET['id_corrida'];

    // Consulta SQL para buscar o percurso correspondente ao id da corrida
    $sql_percurso = "SELECT caminho FROM percurso WHERE id = $id_corrida";
    $percursoResult = $conn->query($sql_percurso);

    if ($percursoResult->num_rows > 0) {
        $row = $percursoResult->fetch_assoc();
        
        // Decodifica o JSON para um array PHP
        $caminhoPercorrido = json_decode($row['caminho'], true);
    }
}

// Consulta SQL para obter as posições dos carrinhos (dispositivos) a partir da tabela 'posicao'
$sql_posicao = "SELECT macAddress, x, y FROM posicao";
$posicoesResult = $conn->query($sql_posicao);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Operador</title>
    <style>
                body {
            background-color: #e8f5e9;
            font-family: Arial, sans-serif;
        }

        header img {
            display: block;
            margin: 0 auto;
            max-width: 200px;
        }

        footer img {
            display: block;
            margin: 20px auto;
            width: 200px; /* Aumentar a imagem do rodapé */
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #4CAF50;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .finalizar {
            color: #fff;
            background-color: #e57373;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
        }

        .finalizar:hover {
            background-color: #ef5350;
        }

        .mapa-container {
            position: relative;
            display: inline-block;
            margin-top: 20px;
        }

        .mapa-container img {
            max-width: 100%;
            height: auto;
        }

        .marker {
            position: absolute;
            width: 25px;
            height: 25px;
            background-color: red;
            border-radius: 20%;
            transform: translate(-50%, -50%);
        }

        .marker-carrinho {
            background-color: blue;
        }

        .marker-label {
            position: absolute;
            font-size: 12px;
            color: black;
            transform: translate(-50%, 15px);
            text-align: center;
            white-space: nowrap;
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none; /* Permite clicar no conteúdo abaixo do canvas */
        }
    </style>
</head>

<body>

<header>
    <img src="imagens/john.png" alt="Imagem do Header">
</header>

<div class="container">
    <h1>Corridas Pendentes</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Coordenada X</th>
            <th>Coordenada Y</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php
        if ($corridasResult->num_rows > 0) {
            while ($row = $corridasResult->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['x']}</td>
                    <td>{$row['y']}</td>
                    <td>{$row['status']}</td>
                    <td><a class='finalizar' href='operador.php?finalizar={$row['id']}'>Finalizar</a></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhuma corrida pendente.</td></tr>";
        }
        ?>
    </table>
    
    <div class="mapa-container">
        <img src="imagens/mapa.png" alt="Mapa" id="mapa">
        
        <!-- Canvas para desenhar o percurso -->
        <canvas id="percursoCanvas"></canvas>
        
        <?php
        // Adiciona marcadores e rótulos para corridas
        $corridasResult->data_seek(0);  
        while ($row = $corridasResult->fetch_assoc()) {
            if ($row['x'] > 1){
                $x_pixel = $row['x'] * 71;
            }else{
                $x_pixel = 1 * 85;
            }

            if ($row['y'] > 1){
                $y_pixel = $row['y'] * 115;
            }else{
                $y_pixel = 1 * 150;
            }

            echo "<div class='marker' style='top: {$y_pixel}px; left: {$x_pixel}px;'></div>";
            echo "<div class='marker-label' style='top: {$y_pixel}px; left: {$x_pixel}px;'>{$row['id']}</div>";
        }

        // Adiciona marcadores e rótulos para carrinhos
        while ($row = $posicoesResult->fetch_assoc()) {
            if ($row['x'] > 1){
                $x_pixel = $row['x'] * 71;
            }else{
                $x_pixel = 1 * 85;
            }

            if ($row['y'] > 1){
                $y_pixel = $row['y'] * 115;
            }else{
                $y_pixel = 1 * 150;
            }

            echo "<div class='marker marker-carrinho' style='top: {$y_pixel}px; left: {$x_pixel}px;'></div>";
            echo "<div class='marker-label' style='top: {$y_pixel}px; left: {$x_pixel}px;'>{$row['macAddress']}</div>";
        }
        ?>
    </div>

</div>

<footer class="logo">
    <img src="imagens/logo.png" alt="Logo no Rodapé">
</footer>

<script>
    // Caminho percorrido (obtido do banco de dados via PHP)
    const caminhoPercorrido = <?php echo json_encode($caminhoPercorrido); ?>;

    // Multiplicador de escala
    const escala = 70; // Mesma escala que você usa para posicionar os marcadores

    // Função para desenhar o percurso no canvas
    function desenharPercurso() {
        const canvas = document.getElementById('percursoCanvas');
        const ctx = canvas.getContext('2d');
        
        // Ajustar o canvas para o tamanho do mapa
        const mapaImg = document.getElementById('mapa');
        canvas.width = mapaImg.clientWidth;
        canvas.height = mapaImg.clientHeight;

        // Configurar o estilo da linha
        ctx.strokeStyle = 'gold'; // Cor da linha do percurso
        ctx.lineWidth = 8;

        // Iniciar o desenho da linha
        if (caminhoPercorrido.length > 0) {
            const [xInicial, yInicial] = caminhoPercorrido[0];
            ctx.beginPath();
            ctx.moveTo(xInicial * escala * 2, yInicial * escala);

            // Percorrer o caminho e desenhar
            caminhoPercorrido.forEach(([x, y]) => {
                ctx.lineTo(x * escala, y * escala);
            });

            // Finalizar o desenho
            ctx.stroke();
        }
    }

    // Desenhar o percurso quando a página carrega
    window.onload = desenharPercurso;
</script>

</body>
</html>
