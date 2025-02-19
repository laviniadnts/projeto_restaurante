<?php
// Criando a conexão
$conn = pg_connect("host=localhost dbname=20231214010035 user=postgres password=pabd");

// Verificando se a conexão foi bem-sucedida
if (!$conn) {
    die("Erro ao conectar ao banco de dados: " . pg_last_error());
}
?>
<?php

// Consulta SQL para selecionar os pedidos
$query = "SELECT id, pedido, mesa FROM pedidos";
$result = pg_query($conn, $query);

if (!$result) {
    die("Erro na consulta: " . pg_last_error());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pedidos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Lista de Pedidos</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Pedido</th>
            <th>Mesa</th>
        </tr>
        <?php while ($row = pg_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['pedido']; ?></td>
                <td><?php echo $row['mesa']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
