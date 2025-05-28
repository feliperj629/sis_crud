<?php
require_once 'classes/usuario.class.php';

// Cria instância da classe Usuario
$usuario = new Usuario();

// Busca todos os usuários
$usuarios = $usuario->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cadastros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .page-title {
            color: #333;
            margin-bottom: 30px;
        }
        .btn-action {
            margin: 0 2px;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .modal-body dt {
            font-weight: bold;
            margin-top: 10px;
        }
        .modal-body dd {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title">Lista de Cadastros</h2>
                <a href="cadusuario.php?op=I" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Novo Cadastro
                </a>
            </div>

            <?php if (!empty($usuarios)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $row) : ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['telefone']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" 
                                                onclick="visualizarUsuario(<?php echo $row['id']; ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="cadusuario.php?op=A&id=<?php echo $row['id']; ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="exec.usuario.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="op" value="E">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    Nenhum usuário cadastrado.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de Visualização -->
    <div class="modal fade" id="modalVisualizar" tabindex="-1" aria-labelledby="modalVisualizarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVisualizarLabel">Detalhes do Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-3">Nome:</dt>
                        <dd class="col-sm-9" id="modal-nome"></dd>

                        <dt class="col-sm-3">E-mail:</dt>
                        <dd class="col-sm-9" id="modal-email"></dd>

                        <dt class="col-sm-3">Telefone:</dt>
                        <dd class="col-sm-9" id="modal-telefone"></dd>

                        <dt class="col-sm-3">Endereço:</dt>
                        <dd class="col-sm-9" id="modal-endereco"></dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function visualizarUsuario(id) {
            // Debug temporário
            console.log('Buscando usuário ID:', id);
            
            // Faz a requisição AJAX
            $.ajax({
                url: 'buscar_usuario.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    console.log('Resposta recebida:', response);
                    
                    if (response.success) {
                        // Preenche o modal com os dados
                        $('#modal-nome').text(response.data.nome);
                        $('#modal-email').text(response.data.email);
                        $('#modal-telefone').text(response.data.telefone);
                        $('#modal-endereco').text(response.data.endereco);
                        
                        // Abre o modal
                        var modal = new bootstrap.Modal(document.getElementById('modalVisualizar'));
                        modal.show();
                    } else {
                        console.error('Erro na resposta:', response.message);
                        alert(response.message || 'Erro ao carregar dados do usuário');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    alert('Erro ao comunicar com o servidor: ' + error);
                }
            });
        }
    </script>
</body>
</html>