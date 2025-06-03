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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            min-height: 100vh;
        }
        body {
            min-height: 100vh;
            background: #f8f9fa;
        }
        .container {
            max-width: 100vw;
            width: 100vw;
            padding: 0 0.5rem;
        }
        .card {
            border-radius: 22px !important;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border: 2px solid #ffc107;
            background: #fff;
            overflow: hidden;
        }
        .btn-custom {
            background: #111 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 12px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-custom:hover, .btn-custom:focus {
            background: #222 !important;
            color: #fff !important;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th,
        .table td {
            min-width: 150px; /* Ajuste conforme necessário */
        }
        @media (max-width: 600px) {
            .container {
                padding: 0;
            }
            .card {
                margin-bottom: 1.2rem;
                /* Removido min-width e max-width */
            }
            .table {
                font-size: 0.9rem;
            }
            .btn-sm {
                padding: 0.4rem 0.8rem;
            }
            .navbar .user-menu span {
                max-width: 80px;
                font-size: 0.95rem;
            }
        }
        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .ps-3 { padding-left: 1rem !important; }
        .pe-3 { padding-right: 1rem !important; }
    </style>
    <style>
    @media (min-width: 375px) and (max-width: 428px) {
        .d-flex.align-items-center {
            flex-direction: row;
        }
    }
    </style>
</head>
<body>
<nav class="navbar navbar-dark py-0" style="background-color: #ffc107; min-height:38px;">
    <div class="px-0 d-flex justify-content-between align-items-center w-100" style="min-height:38px;">
        <a class="navbar-brand text-dark fw-bold m-0 ps-3" style="font-size:1rem;" href="dashboard.php">Suporte TI</a>
        <div class="d-flex align-items-center pe-3">
            <span class="text-dark fw-bold me-3 text-end" style="font-size:0.92rem;"><?= htmlspecialchars($usuario['nome']) ?> (<?= $usuario['tipo'] ?>)</span>
            <a class="btn btn-custom btn-sm" href="../logout.php">Sair</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="d-flex w-100 justify-content-start align-items-center gap-2 mb-4">
        <a href="editar_usuario.php" class="btn btn-custom">
            <i class="bi bi-plus-circle me-2"></i>Novo Usuário
        </a>
        <a href="dashboard.php" class="btn btn-custom">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
    <h3 class="fw-bold mb-4 text-center">Gerenciamento de Usuários</h3>
     

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
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
                                    if ($u['tipo'] === 'Administrador') echo 'bg-warning';
                                    elseif ($u['tipo'] === 'Tecnico') echo 'bg-info';
                                    else echo 'bg-success';
                                ?>">
                                    <?= $u['tipo'] ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-custom">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="editar_usuario.php?excluir=<?= $u['id'] ?>" 
                                       class="btn btn-sm btn-custom"
                                       onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
