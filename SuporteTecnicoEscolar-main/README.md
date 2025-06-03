
Sistema web para gerenciamento de chamados de suporte técnico em ambiente escolar.  
Desenvolvido em PHP com MySQL, utilizando arquitetura MVC simples e autenticação de usuários com diferentes perfis: Administrador, Técnico e Solicitante.

---

## 🚀 Funcionalidades

✅ Autenticação e controle de sessões  
✅ Cadastro de usuários: Administrador, Técnico e Solicitante  
✅ Abertura de chamados por solicitantes  
✅ Atribuição de chamados a técnicos pelos administradores  
✅ Atualização de status dos chamados pelos técnicos  
✅ Visualização de relatórios filtrados por data, status e categoria  
✅ Interface amigável com Bootstrap

---

## 🛠️ Tecnologias Utilizadas

- PHP 7.x ou superior
- MySQL 5.7 ou superior
- PDO para acesso seguro ao banco de dados
- HTML5 e CSS3
- Bootstrap 5.3
- JavaScript (para interatividade básica)

---

## 🗂️ Estrutura de Diretórios

suporteTI/
├── assets/
│ ├── css/
│ └── js/
├── classes/
│ ├── BancoDeDados.php
│ ├── Chamado.php
│ ├── Usuario.php
│ ├── Tecnico.php
│ ├── Solicitante.php
│ └── Administrador.php
├── controllers/
│ ├── ControladorChamado.php
│ └── ControladorUsuario.php
├── views/
│ ├── dashboard.php
│ ├── chamados.php
│ ├── relatorios.php
│ ├── login.php
│ ├── cadastro.php
│ └── ...
├── BancoDeDados.php
├── Sessao.php
├── index.php
└── logout.php

sql
Copiar
Editar

---

## 💾 Configuração e Instalação

1. **Clone o repositório ou copie os arquivos** para a pasta do seu servidor local (ex.: `C:\xampp\htdocs\suporteTI`).

2. **Crie o banco de dados** MySQL:

sql
CREATE DATABASE suporte CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE suporte;

Criação das tabelas
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('Administrador','Tecnico','Solicitante') NOT NULL
);

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE setores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante_id INT NOT NULL,
    categoria_id INT NOT NULL,
    setor_id INT NOT NULL,
    descricao TEXT NOT NULL,
    status ENUM('Aberto','Em atendimento','Concluído') DEFAULT 'Aberto',
    data_abertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_conclusao DATETIME NULL,
    solucao TEXT NULL,
    tecnico_id INT NULL,
    FOREIGN KEY (solicitante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (setor_id) REFERENCES setores(id),
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
Configure a conexão com o banco de dados no arquivo:

php
Copiar
Editar
// BancoDeDados.php
$host = 'localhost';
$dbname = 'suporte';
$user = 'root';
$pass = '';
Insira dados iniciais (opcional):

sql
Copiar
Editar
INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Admin', 'admin@escola.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Administrador'),
('João Técnico', 'joao@escola.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Tecnico'),
('Maria Solicitante', 'maria@escola.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Solicitante');

INSERT INTO categorias (nome) VALUES ('Informática'), ('Elétrica'), ('Manutenção Predial');
INSERT INTO setores (nome) VALUES ('Biblioteca'), ('Laboratório de Informática'), ('Administração');
A senha criptografada acima corresponde a "123456".
