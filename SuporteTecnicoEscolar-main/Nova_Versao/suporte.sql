-- Criação do banco e tabelas
CREATE DATABASE IF NOT EXISTS suporte CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE suporte;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('Administrador','Tecnico','Solicitante') NOT NULL
);

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS setores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante_id INT NOT NULL,
    tecnico_id INT DEFAULT NULL,
    categoria_id INT NOT NULL,
    setor_id INT NOT NULL,
    descricao TEXT NOT NULL,
    status ENUM('Aberto','Em atendimento','Concluído') DEFAULT 'Aberto',
    data_abertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_conclusao DATETIME NULL,
    solucao TEXT NULL,
    FOREIGN KEY (solicitante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT,
    FOREIGN KEY (setor_id) REFERENCES setores(id) ON DELETE RESTRICT
);

-- Dados iniciais de categorias e setores
INSERT INTO categorias (nome) VALUES ('Equipamento'),('Rede'),('Software');
INSERT INTO setores (nome) VALUES ('Secretaria'),('Sala 202'),('Laboratório');
