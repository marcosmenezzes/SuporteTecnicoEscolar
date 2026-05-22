# 🎫 Technical Support Ticket Management System

Web-based system for managing technical support tickets in a school environment.  
Developed in PHP with MySQL, using a simple MVC architecture and user authentication with different roles: Administrator, Technician, and Requester.

---

## 🚀 Features

✅ Authentication and session control  
✅ User registration: Administrator, Technician, and Requester  
✅ Ticket creation by requesters  
✅ Ticket assignment to technicians by administrators  
✅ Ticket status updates by technicians  
✅ Report visualization filtered by date, status, and category  
✅ User-friendly interface with Bootstrap

---

## 🛠️ Technologies Used

- PHP 7.x or higher
- MySQL 5.7 or higher
- PDO for secure database access
- HTML5 and CSS3
- Bootstrap 5.3
- JavaScript (for basic interactivity)

---

## 🗂️ Directory Structure

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

---

## 💾 Setup and Installation

1. **Clone the repository or copy the files** to your local server folder (example: `C:\xampp\htdocs\suporteTI`).

2. **Create the MySQL database**:

`sql
CREATE DATABASE suporte CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE suporte;

-- Create tables
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
Configure the database connection in the file:
// BancoDeDados.php
$host = 'localhost';
$dbname = 'suporte';
$user = 'root';
$pass = '';
Insert initial data (optional):
INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Admin', 'admin@escola.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Administrador'),
('João Técnico', 'joao@escola.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Tecnico'),
('Maria Solicitante', 'maria@escola.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Solicitante');

INSERT INTO categorias (nome) VALUES 
('IT Support'), 
('Electrical'), 
('Building Maintenance');

INSERT INTO setores (nome) VALUES 
('Library'), 
('Computer Lab'), 
('Administration');

The encrypted password above corresponds to: 123456.

?
# 🎫 Technical Support Ticket Management System

Web-based system for managing technical support tickets in a school environment.  
Developed in PHP with MySQL, using a simple MVC architecture and user authentication with different roles: Administrator, Technician, and Requester.

---

## 🚀 Features

✅ Authentication and session control  
✅ User registration: Administrator, Technician, and Requester  
✅ Ticket creation by requesters  
✅ Ticket assignment to technicians by administrators  
✅ Ticket status updates by technicians  
✅ Report visualization filtered by date, status, and category  
✅ User-friendly interface with Bootstrap

---

## 🛠️ Technologies Used

- PHP 7.x or higher
- MySQL 5.7 or higher
- PDO for secure database access
- HTML5 and CSS3
- Bootstrap 5.3
- JavaScript (for basic interactivity)

---

## 🗂️ Directory Structure

``text
suporteTI/
├── assets/
│   ├── css/
│   └── js/
├── classes/
│   ├── BancoDeDados.php
│   ├── Chamado.php
│   ├── Usuario.php
│   ├── Tecnico.php
│   ├── Solicitante.php
│   └── Administrador.php
├── controllers/
│   ├── ControladorChamado.php
│   └── ControladorUsuario.php
├── views/
│   ├── dashboard.php
│   ├── chamados.php
│   ├── relatorios.php
│   ├── login.php
│   ├── cadastro.php
│   └── ...
├── BancoDeDados.php
├── Sessao.php
├── index.php
└── logout.php
💾 Setup and Installation
Clone the repository or copy the files to your local server folder (example: C:\xampp\htdocs\suporteTI).
Create the MySQL database:
CREATE DATABASE suporte CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE suporte;

-- Create tables
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
    status ENUM('Open','In Progress','Completed') DEFAULT 'Open',
    data_abertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_conclusao DATETIME NULL,
    solucao TEXT NULL,
    tecnico_id INT NULL,
    FOREIGN KEY (solicitante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (setor_id) REFERENCES setores(id),
    FOREIGN KEY (tecnico_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
Configure the database connection in the file:
// BancoDeDados.php
$host = 'localhost';
$dbname = 'suporte';
$user = 'root';
$pass = '';
Insert initial data (optional):
INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Admin', 'admin@school.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Administrador'),
('John Technician', 'john@school.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Tecnico'),
('Maria Requester', 'maria@school.com', '$2y$10$uHTod.DdkyF1jOtokkOHKOW2qGHX9fgXGHA4arNZ.t245hLRmY0Yq', 'Solicitante');

INSERT INTO categorias (nome) VALUES 
('IT Support'),
('Electrical'),
('Building Maintenance');

INSERT INTO setores (nome) VALUES 
('Library'),
('Computer Lab'),
('Administration');

The encrypted password above corresponds to: 123456.
