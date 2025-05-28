<?php

require_once 'classes/usuario.class.php';

// Verifica se o ID foi enviado
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    error_log("ID inválido na busca do modal: " . print_r($_POST, true));
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {
    // Cria instância da classe Usuario
    $usuario = new Usuario();

    // Busca os dados do usuário
    $resultado = $usuario->buscarParaModal($_POST['id']);

    // Debug temporário
    error_log("Resultado da busca para modal: " . print_r($resultado, true));

    // Retorna o resultado em JSON
    header('Content-Type: application/json');
    echo json_encode($resultado);
} catch (Exception $e) {
    error_log("Erro ao buscar usuário para modal: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar dados do usuário: ' . $e->getMessage()]);
}
