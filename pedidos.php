<?php
$conn = pg_connect("host=localhost dbname=20231214010035 user=postgres password=pabd");
if (!$conn) {
    die("Conexão falhou: " . pg_last_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pedido = $_POST["pedido"];
    $mesa = $_POST["mesa"];

    $query = "INSERT INTO pedidos (pedido, mesa) VALUES ($1, $2)";
    $result = pg_query_params($conn, $query, array($pedido, $mesa));
    
    if ($result) {
        echo "<p class='correto'>Pedido realizado com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao fazer pedido: " . pg_last_error($conn) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área de pedidos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
    <h1>Área de pedidos:</h1>
    <form method="post" action="">
        <label for="pedido">Pedido</label>
        <input type="text" name="pedido" required>
        <br></br>
        <label for="mesa">Mesa</label>
        <input type="text" name="mesa" required>
        <br></br>
        <input class="inputpedido" type="submit" value="Fazer pedido">
    </form>
    </div>
</body>
</html>