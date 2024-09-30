<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Usuário</title>
    <style>
        body {
            background-color: #e8f5e9;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        h1 {
            color: #4CAF50;
            margin: 20px 0;
        }

        footer img {
            display: block;
            margin: 20px auto;
            width: 200px; /* Aumentar a imagem do rodapé */
        }

        .botao {
            display: block;
            width: 200px;
            padding: 15px;
            margin: 10px auto;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
            cursor: pointer;
        }

        .botao:hover {
            background-color: #45a049;
        }

        .image-container {
            margin: 10px 0;
        }

        .image-container img {
            max-width: 100%; /* Ajusta o tamanho máximo da imagem */
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Força o espaçamento superior na imagem */
        .image-container img:first-child {
            margin-top: 20px; /* Adiciona margem superior para evitar corte no topo */
        }
    </style>
</head>
<body>
    <div class="image-container">
        <img src="imagens/john.png" alt="Imagem do Header">
    </div>
    <h1>Escolha seu Usuário</h1>
    <a href="operador.php" class="botao">Entrar como Rebocador</a>
    <a href="compras.php" class="botao">Entrar como Gerente</a>
    <footer class = "logo">
        <img src="imagens/logo.png" alt="Logo no Rodapé">
    </footer>
</body>
</html>
