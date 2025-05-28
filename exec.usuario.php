<?php

// print "<pre>";
// print_r($_REQUEST);
// print "</pre>";
// exit;

// Conexão com o banco de dados
require_once 'classes/usuario.class.php';

// Verifica se é uma requisição POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}

// Cria instância da classe Usuario
$usuario = new Usuario();

// Obtém a operação a ser realizada
$operacao = $_REQUEST['op'] ?? '';

if ($operacao == 'I' || $operacao == 'A') {
    // Define os dados do usuário
    $usuario->setNome($_POST['nome']);
    $usuario->setEmail($_POST['email']);
    $usuario->setTelefone($_POST['telefone']);
    $usuario->setEndereco($_POST['endereco']);
}

// Processa a operação
switch ($operacao) {
    case 'I': // Inserção
        // Valida os dados
        $erros = $usuario->validarDados();
        if (empty($erros)) {
            // Tenta salvar o usuário
            if ($usuario->salvar()) {
                echo "<script>
                        alert('Usuário cadastrado com sucesso!');
                        window.location.href = 'index.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Erro ao cadastrar usuário!');
                        window.location.href = 'cadusuario.php?op=I';
                      </script>";
            }
        } else {
            // Mostra os erros de validação
            $mensagem = "Erros encontrados:\n" . implode("\n", $erros);
            echo "<script>
                    alert('" . addslashes($mensagem) . "');
                    window.location.href = 'cadusuario.php?op=I';
                  </script>";
        }
        break;

    case 'A': // Alteração
        // Verifica se o ID foi enviado
        if (!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
            echo "<script>
                    alert('ID inválido!');
                    window.location.href = 'index.php';
                  </script>";
            exit;
        }

        // Define os dados do usuário
        $usuario->setId($_REQUEST['id']);

        // Valida os dados
        $erros = $usuario->validarDados();
        if (empty($erros)) {
            // Tenta atualizar o usuário
            if ($usuario->atualizar()) {
                error_log("Usuário atualizado com sucesso");
                echo "<script>
                        alert('Usuário atualizado com sucesso!');
                        window.location.href = 'index.php';
                      </script>";
            } else {
                error_log("Erro ao atualizar usuário - Falha na operação");
                echo "<script>
                        alert('Erro ao atualizar usuário!');
                        window.location.href = 'cadusuario.php?op=A&id=" . $usuario->getId() . "';
                      </script>";
            }
        } else {
            // Debug temporário - Log dos erros de validação
            error_log("Erros de validação: " . print_r($erros, true));
            // Mostra os erros de validação
            $mensagem = "Erros encontrados:\n" . implode("\n", $erros);
            echo "<script>
                    alert('" . addslashes($mensagem) . "');
                    window.location.href = 'cadusuario.php?op=A&id=" . $usuario->getId() . "';
                  </script>";
        }
        break;

    case 'E': // Exclusão
        // Verifica se o ID foi enviado
        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            echo "<script>
                    alert('ID inválido!');
                    window.location.href = 'index.php';
                  </script>";
            exit;
        }

        $id = (int)$_POST['id'];

        // Tenta excluir o usuário
        if ($usuario->excluir($id)) {
            echo "<script>
                    alert('Usuário excluído com sucesso!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Erro ao excluir usuário!');
                    window.location.href = 'index.php';
                  </script>";
        }
        break;

    default:
        // Operação inválida
        echo "<script>
                alert('Operação inválida!');
                window.location.href = 'index.php';
              </script>";
        break;
}
