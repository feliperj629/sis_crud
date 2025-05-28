<?php

require_once 'classes/conexao.class.php';

class Usuario
{
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $endereco;
    private $db;

    // Construtor
    public function __construct()
    {
        $this->db = Conexao_simples::getInstance();
    }

    // Getters e Setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    // Método para buscar todos os usuários
    public function listarTodos()
    {
        $sql = "SELECT * FROM usuarios ORDER BY nome";
        $resultado = $this->db->executarQuery($sql);

        $usuarios = [];
        while ($usuario = mysqli_fetch_assoc($resultado)) {
            $usuarios[] = $usuario;
        }
        return $usuarios;
    }

    // Método para buscar um usuário específico
    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $tipos = "i";
        $parametros = [$id];

        $resultado = $this->db->executarQuery($sql, $tipos, $parametros);

        if ($usuario = mysqli_fetch_assoc($resultado)) {
            $this->id = $usuario['id'];
            $this->nome = $usuario['nome'];
            $this->email = $usuario['email'];
            $this->telefone = $usuario['telefone'];
            $this->endereco = $usuario['endereco'];
            return true;
        }
        return false;
    }

    // Método para cadastrar um novo usuário
    public function salvar()
    {
        $sql = "INSERT INTO usuarios (nome, email, telefone, endereco) VALUES (?, ?, ?, ?)";
        $tipos = "ssss";
        $parametros = [
            $this->nome,
            $this->email,
            $this->telefone,
            $this->endereco
        ];

        $resultado = $this->db->executarQuery($sql, $tipos, $parametros);
        if ($resultado) {
            $this->id = $this->db->getUltimoId();
            return true;
        }
        return false;
    }

    // Método para atualizar um usuário
    public function atualizar()
    {
        $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, endereco = ? WHERE id = ?";
        $tipos = "ssssi";
        $parametros = [
            $this->nome,
            $this->email,
            $this->telefone,
            $this->endereco,
            $this->id
        ];

        // Debug temporário
        error_log("SQL de atualização: " . $sql);
        error_log("Parâmetros de atualização: " . print_r($parametros, true));

        try {
            $resultado = $this->db->executarQuery($sql, $tipos, $parametros);

            // Verifica se alguma linha foi afetada
            $linhasAfetadas = mysqli_affected_rows($this->db->getConexao());
            error_log("Linhas afetadas na atualização: " . $linhasAfetadas);

            // Se a query foi executada com sucesso, consideramos a atualização bem sucedida
            if ($resultado === true) {
                error_log("Atualização bem sucedida");
                return true;
            }

            error_log("Falha na atualização");
            return false;
        } catch (Exception $e) {
            error_log("Erro na atualização: " . $e->getMessage());
            return false;
        }
    }

    // Método para excluir um usuário
    public function excluir($id)
    {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $tipos = "i";
        $parametros = [$id];

        return $this->db->executarQuery($sql, $tipos, $parametros);
    }

    // Método para buscar usuário para o modal (retorna JSON)
    public function buscarParaModal($id)
    {
        if ($this->buscarPorId($id)) {
            return [
                'success' => true,
                'data' => [
                    'nome' => htmlspecialchars($this->nome),
                    'email' => htmlspecialchars($this->email),
                    'telefone' => htmlspecialchars($this->telefone),
                    'endereco' => htmlspecialchars($this->endereco)
                ]
            ];
        }
        return ['success' => false, 'message' => 'Usuário não encontrado'];
    }

    // Método para validar dados
    public function validarDados()
    {
        $erros = [];

        if (empty($this->nome)) {
            $erros[] = "Nome é obrigatório";
        }

        if (empty($this->email)) {
            $erros[] = "E-mail é obrigatório";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = "E-mail inválido";
        }

        if (empty($this->telefone)) {
            $erros[] = "Telefone é obrigatório";
        }

        if (empty($this->endereco)) {
            $erros[] = "Endereço é obrigatório";
        }

        return $erros;
    }
}
