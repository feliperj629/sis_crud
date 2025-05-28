<?php

require_once 'classes/conexao_pdo.php';

 // Função para sanitizar strings
function sanitizarString($dados)
{
    //ENT_QUOTES = Converte aspas simples e duplas para HTML entities
    return htmlspecialchars(trim($dados), ENT_QUOTES, 'UTF-8');
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Atribuição de variáveis simples
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $endereco = $_POST['endereco'] ?? '';


    // Atribuição de variáveis com sanitização
    // Recebe e sanitiza os dados do formulário
    // $nome = sanitizarString($_POST['nome'] ?? '');
    // $email = sanitizarString($_POST['email'] ?? '');
    // $telefone = sanitizarString($_POST['telefone'] ?? '');
    // $endereco = sanitizarString($_POST['endereco'] ?? '');



    // Validação básica
    if (empty($nome) || empty($email) || empty($telefone) || empty($endereco)) {
        echo "<script>
                alert('Todos os campos são obrigatórios!');
                window.location.href = 'cadastro.php';
              </script>";
        exit();
    }

    try {
        // Prepara a query SQL
        $sql = "INSERT INTO usuarios (nome, email, telefone, endereco) VALUES (:nome, :email, :telefone, :endereco)";
        $stmt = $conexao->prepare($sql);

        // Vincula os parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);

        // Executa a query
        if ($stmt->execute()) {
            echo "<script>
                    alert('Cadastro realizado com sucesso!');
                    window.location.href = 'cadastro.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Erro ao realizar cadastro!');
                    window.location.href = 'cadastro.php';
                  </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
                alert('Erro: " . $e->getMessage() . "');
                window.location.href = 'cadastro.php';
              </script>";
    }
} else {
    // Redireciona para a página de cadastro se o formulário não for enviado
    header("Location: cadastro.php");
    exit();
}
