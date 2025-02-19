<?php
// Criando a conexão
$conn = pg_connect("host=localhost dbname=20231214010035 user=postgres password=pabd");

    if (!$conn) {
        die("Conexão falhou: " . pg_last_error());
    }

    // Checando o envio do formulário
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $nome = $_POST['nome']; // Pegando o ID do funcionário ou gerente
        $senha = $_POST['senha'];

        // Consulta SQL para verificar a tabela e os dados
        $query = "SELECT * FROM funcionario WHERE nome = $1 AND senha = $2";
        $result = pg_query_params($conn, $query, array($nome,$senha));
        
    if (pg_num_rows($result) > 0) {
        // Se encontrar um funcionário, redireciona para a página de funcionários
        header("Location: funcionario.php?nome=$nome");
        exit;
        } else {
        // Caso contrário, verifica se é um gerente
        $query = "SELECT * FROM gerente WHERE nome = $1 AND senha = $2";
        $result = pg_query_params($conn, $query, array($nome,$senha));
            
    if (pg_num_rows ($result) > 0 ){
        header("Location: gerente.php?nome=$nome");
        exit;
        } else {
        echo "";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>pagina inicial</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
    <h1>Digite seus dados:</h1>
    <form method="post" action="">
        <label for="nome">nome</label>
        <input type="text" name="nome" required>
        <br></br>
        <label for="senha">senha</label>
        <input type="password" name="senha" required>
        <br></br>
        <input class="inputsubmit" type="submit" value="Buscar">
    </form>
    </div>
</body>
</html>

