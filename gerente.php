<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $senha = (isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : "";
} else if (!isset($id)) {
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $senha = NULL;
}
 
try {
    $conexao = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=20231214010035',
	'postgres', 'pabd');
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("SET client_encoding TO 'UTF8'");
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "" && $senha != "") {
    try {
        if ($id != "") {
            // Atualização do registro
            $stmt = $conexao->prepare("UPDATE funcionario SET nome = ?, senha = ? WHERE id = ?");
            $stmt->bindParam(1, $nome);
            $stmt->bindParam(2, $senha);
            $stmt->bindParam(3, $id, PDO::PARAM_INT);
        } else {
            // Inserção de novo registro
            $stmt = $conexao->prepare("INSERT INTO funcionario (nome, senha) VALUES (?, ?)");
            $stmt->bindParam(1, $nome);
            $stmt->bindParam(2, $senha);
        }

        if ($stmt->execute()) {
            echo "Registro salvo com sucesso!";
            $id = null;
            $nome = null;
            $senha = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

 
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM funcionario WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $senha = $rs->senha;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM funcionario WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Restaurante D'ullà</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <form action="?act=save" method="POST" name="form1" >
                <h1>Gerenciamento de funcionários</h1>
                <hr>
                <input type="hidden" name="id" <?php
                 
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />
                Nome:
               <input type="text" name="nome" <?php
 
               if (isset($nome) && $nome != null || $nome != "") {
                   echo "value=\"{$nome}\"";
               }
               ?> />
               Senha:
               <input type="text" name="senha" <?php
 
               if (isset($senha) && $senha != null || $senha != "") {
                   echo "value=\"{$senha}\"";
               }
               ?> />
            
               <input type="submit" value="salvar" />
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>Nome</th>
                    <th>Senha</th>
                    <th>Ações</th>
                </tr>
                <?php
 
                try {
                    $stmt = $conexao->prepare("SELECT * FROM funcionario");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->nome."</td><td>".$rs->senha."</td><td><center>"
                                       ."<center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>
        </body>
    </html>