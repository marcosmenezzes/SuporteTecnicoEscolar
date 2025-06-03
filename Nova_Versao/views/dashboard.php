<?php
require_once __DIR__ . '/../Sessao.php';
Sessao::iniciar();
if (!Sessao::estaLogado()) {
    header("Location: ../login.php");
    exit;
}
$usuario = Sessao::getUsuario();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Suporte TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Suporte TI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3">
                    <span class="text-white">Olá, <?= htmlspecialchars($usuario['nome']) ?> (<?= $usuario['tipo'] ?>)</span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light" href="../logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="text-center mb-4 fw-bold text-dark">Painel de Controle</h2>
    <div class="row gy-4 justify-content-center">
        <?php if ($usuario['tipo'] === 'Administrador'): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-people-fill display-4 text-dark"></i>
                            <h5 class="mt-3 fw-bold">Gerenciar Usuários</h5>
                            <p class="text-muted">Adicione, edite ou remova usuários do sistema</p>
                        </div>
                        <a href="usuarios.php" class="btn btn-primary mt-3 w-100">Acessar</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($usuario['tipo'] === 'Administrador' || $usuario['tipo'] === 'Tecnico' || $usuario['tipo'] === 'Solicitante'): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-cpu-fill display-4 text-medium"></i>
                            <h5 class="mt-3 fw-bold">Chamados</h5>
                            <p class="text-muted">Gerencie seus chamados de suporte técnico</p>
                        </div>
                        <a href="chamados.php" class="btn btn-primary mt-3 w-100">Acessar</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($usuario['tipo'] === 'Administrador'): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-file-earmark-bar-graph-fill display-4 text-dark"></i>
                            <h5 class="mt-3 fw-bold">Relatórios</h5>
                            <p class="text-muted">Visualize relatórios e estatísticas do sistema</p>
                        </div>
                        <a href="relatorios.php" class="btn btn-primary mt-3 w-100">Gerar</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
