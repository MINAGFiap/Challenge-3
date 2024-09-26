<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Dados no Banco</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
 
<div class="container">
    <h2>Inserir dados na tabela</h2>
    <form action="inserir_dados_operadores.php" method="POST">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" required>
 
        <label for="dados">Dados (JSON):</label>
        <textarea id="dados" name="dados" rows="4" cols="50"></textarea>
 
        <input type="submit" value="Inserir Dados">
    </form>
</div>
 
</body>
</html>