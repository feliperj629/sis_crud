<?php

require_once 'classes/usuario.class.php';

// Cria instância da classe Usuario
$usuario = new Usuario();

// Inicializa as variáveis
$nome = '';
$email = '';
$telefone = '';
$endereco = '';
$operacao = $_REQUEST['op'] ?? '';
$resp = $_REQUEST['resp'] ?? '';

// Processa o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define os dados do usuário
    $usuario->setNome($_POST['nome']);
    $usuario->setEmail($_POST['email']);
    $usuario->setTelefone($_POST['telefone']);
    $usuario->setEndereco($_POST['endereco']);

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
                  </script>";
        }
    } else {
        // Mostra os erros de validação
        $mensagem = "Erros encontrados:\n" . implode("\n", $erros);
        echo "<script>
                alert('" . addslashes($mensagem) . "');
              </script>";
        // Mantém os dados do formulário em caso de erro
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
    }
}

//Busca de dados - Alteração
if ($operacao == 'A') {
    // Verifica se o ID foi enviado
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "<script>
                alert('ID inválido!');
                window.location.href = 'index.php';
            </script>";
        exit;
    }
    // Converte o ID para inteiro
    $id = (int)$_GET['id'];

    // Debug temporário
    error_log("ID recebido para edição: " . $id);

    // Busca os dados do usuário
    if (!$usuario->buscarPorId($id)) {
        echo "<script>
                alert('Usuário não encontrado!');
                window.location.href = 'index.php';
            </script>";
        exit;
    }
    // Preenche os campos com os dados do usuário
    $nome = $usuario->getNome();
    $email = $usuario->getEmail();
    $telefone = $usuario->getTelefone();
    $endereco = $usuario->getEndereco();

    // Debug temporário
    error_log("Dados do usuário carregados: " . print_r([
        'id' => $id,
        'nome' => $nome,
        'email' => $email,
        'telefone' => $telefone,
        'endereco' => $endereco
    ], true));
}

//Alteração de dados
if ($operacao == 'A') {
} elseif ($operacao == 'I') {
    $usuario = new Usuario();
} elseif ($operacao == 'E') {
    $usuario = new Usuario();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
    </style>
    <script>
        $(document).ready(function(){
            // Máscara para telefone (aceita 8 ou 9 dígitos)
            $('#telefone').mask('(00) 00000-0000');
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Cadastro de Usuário</h2>
            <form action="exec.usuario.php" method="POST">
                <input type="hidden" name="op" value="<?php echo $operacao; ?>">

                <?php if ($operacao == 'A') : ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="nome" name="nome" 
                           value="<?php echo htmlspecialchars($nome); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="tel" class="form-control" id="telefone" name="telefone" 
                           value="<?php echo htmlspecialchars($telefone); ?>" required>
                    <div class="form-text">Digite o número com DDD. Ex.: (00) 00000-0000</div>
                </div>
                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" 
                           value="<?php echo htmlspecialchars($endereco); ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 