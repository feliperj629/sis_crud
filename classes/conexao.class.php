<?php

class Conexao_simples
{
    private static $instance = null;
    private $conexao;
    private $servidor = "localhost";
    private $usuario = "root";
    private $senha = "";
    private $banco = "aula";

    // Construtor privado para evitar instanciação direta
    private function __construct()
    {
        try {
            $this->conexao = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->banco);
            if (!$this->conexao) {
                throw new Exception("Erro na conexão: " . mysqli_connect_error());
            }

            // Define o charset para UTF-8
            mysqli_set_charset($this->conexao, "utf8");
        } catch (Exception $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    // Método para obter a instância única
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Método para obter a conexão
    public function getConexao()
    {
        return $this->conexao;
    }

    // Método para fechar a conexão
    public function fecharConexao()
    {
        if ($this->conexao) {
            mysqli_close($this->conexao);
        }
    }

    // Previne a clonagem do objeto
    private function __clone()
    {
    }

    // Previne a deserialização do objeto - deve ser público
    public function __wakeup()
    {
        throw new Exception("Não é possível deserializar uma instância de Conexao_simples");
    }

    // Método para executar queries com prepared statements
    public function executarQuery($sql, $tipos = "", $parametros = [])
    {
        try {
            // Debug temporário
            error_log("Executando query: " . $sql);
            error_log("Tipos: " . $tipos);
            error_log("Parâmetros: " . print_r($parametros, true));

            $stmt = mysqli_prepare($this->conexao, $sql);
            if (!$stmt) {
                $erro = mysqli_error($this->conexao);
                error_log("Erro na preparação da query: " . $erro);
                throw new Exception("Erro na preparação da query: " . $erro);
            }

            if (!empty($tipos) && !empty($parametros)) {
                if (!mysqli_stmt_bind_param($stmt, $tipos, ...$parametros)) {
                    $erro = mysqli_stmt_error($stmt);
                    error_log("Erro ao vincular parâmetros: " . $erro);
                    throw new Exception("Erro ao vincular parâmetros: " . $erro);
                }
            }

            if (!mysqli_stmt_execute($stmt)) {
                $erro = mysqli_stmt_error($stmt);
                error_log("Erro na execução da query: " . $erro);
                throw new Exception("Erro na execução da query: " . $erro);
            }

            // Verifica se é uma query de atualização, inserção ou exclusão
            if (stripos($sql, 'UPDATE') === 0 || stripos($sql, 'INSERT') === 0 || stripos($sql, 'DELETE') === 0) {
                $linhasAfetadas = mysqli_stmt_affected_rows($stmt);
                error_log("Query de modificação executada - Linhas afetadas: " . $linhasAfetadas);
                mysqli_stmt_close($stmt);
                // Retorna true se a query foi executada com sucesso, independente do número de linhas afetadas
                return true;
            }

            // Para queries SELECT, retorna o resultado
            $resultado = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);

            error_log("Query executada com sucesso");
            return $resultado;
        } catch (Exception $e) {
            error_log("Exceção na execução da query: " . $e->getMessage());
            throw $e;
        }
    }

    // Método para obter o último ID inserido
    public function getUltimoId()
    {
        return mysqli_insert_id($this->conexao);
    }

    // Método para escapar strings
    public function escaparString($string)
    {
        return mysqli_real_escape_string($this->conexao, $string);
    }
}
