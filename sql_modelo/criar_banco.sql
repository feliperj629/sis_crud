-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS aula;

-- Criação da tabela usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserção de dados iniciais
INSERT INTO usuarios (nome, email, telefone, endereco) VALUES
('João Silva', 'joao.silva@email.com', '(11) 98765-4321', 'Rua das Flores, 123 - São Paulo/SP'),
('Maria Oliveira', 'maria.oliveira@email.com', '(21) 91234-5678', 'Av. Principal, 456 - Rio de Janeiro/RJ'),
('Pedro Santos', 'pedro.santos@email.com', '(31) 99876-5432', 'Rua dos Pinheiros, 789 - Belo Horizonte/MG'),
('Ana Costa', 'ana.costa@email.com', '(41) 98765-1234', 'Alameda das Árvores, 321 - Curitiba/PR'),
('Carlos Ferreira', 'carlos.ferreira@email.com', '(51) 91234-8765', 'Rua das Palmeiras, 654 - Porto Alegre/RS');

-- Comentários explicativos:
-- 1. O banco de dados usa UTF-8 para suporte completo a caracteres especiais
-- 2. O campo data_cadastro é preenchido automaticamente com a data/hora atual
-- 3. Os telefones estão formatados para facilitar a leitura 