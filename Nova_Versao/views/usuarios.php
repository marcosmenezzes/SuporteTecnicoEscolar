<?php
require_once __DIR__ . '/../Sessao.php';
Sessao::iniciar();
if (!Sessao::estaLogado()) {
    header("Location: ../login.php");
    exit;
}
$usuario = Sessao::getUsuario();
if ($usuario['tipo'] !== 'Administrador') {
    header("Location: dashboard.php");
    exit;
}

require_once __DIR__ . '/../classes/Usuario.php';
$listaUsuarios = Usuario::listarTodos();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Suporte TI</a>
        <div class="collapse navbar-collapse justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item mx-auto">
                    <span class="text-white">Olá, <?= htmlspecialchars($usuario['nome']) ?> (Administrador)</span>
                </li>
                <li class="nav-item ms-3">
                    <a class="btn btn-outline-light" href="../logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="dashboard.php" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            <h3 class="fw-bold mb-0">Gerenciamento de Usuários</h3>
        </div>
        <a href="editar_usuario.php" class="btn btn-success"><i class="bi bi-plus-circle me-2"></i>Novo Usuário</a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Tipo</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($listaUsuarios as $u): ?>
                    <tr>
                        <td class="fw-medium"><?= htmlspecialchars($u['nome']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge <?php 
                                if ($u['tipo'] === 'Administrador') echo 'bg-danger';
                                elseif ($u['tipo'] === 'Tecnico') echo 'bg-info';
                                else echo 'bg-success';
                            ?>">
                                <?= $u['tipo'] ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex">
                                <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary" title="Editar usuário">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="editar_usuario.php?excluir=<?= $u['id'] ?>" class="btn btn-sm btn-danger" title="Excluir usuário" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
