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
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Suporte TI</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3">
                    <span class="text-white">Olá, <?= htmlspecialchars($usuario['nome']) ?> (Administrador)</span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light" href="../logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Usuários</h3>
        <a href="editar_usuario.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Novo Usuário</a>
    </div>

    <table class="table table-hover shadow-sm">
        <thead class="table-light">
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
                <td><?= htmlspecialchars($u['nome']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['tipo'] ?></td>
                <td class="text-end">
                    <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil-fill"></i></a>
                    <a href="editar_usuario.php?excluir=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão deste usuário?');">
                        <i class="bi bi-trash-fill"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
