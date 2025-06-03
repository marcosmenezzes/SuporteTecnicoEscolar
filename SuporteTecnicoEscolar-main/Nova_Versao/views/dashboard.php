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
            border-radius: 22px !important; /* Arredonda todos os 4 cantos */
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border: 2px solid #ffc107; /* Amarelo Bootstrap */
            background: #fff;
            max-width: 400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 340px;
            overflow: hidden; /* Garante que o border-radius funcione */
        }
        .card-body {
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .card-body > div {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .display-4 {
            font-size: 3rem;
            color: #ffc107 !important; /* Ícone amarelo */
        }
        .btn {
            border-radius: 12px;
        }
        .btn-black-custom {
            background: #111 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 12px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-black-custom:hover, .btn-black-custom:focus {
            background: #222 !important;
            color: #fff !important;
        }
        .navbar .user-menu {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: nowrap;
            min-width: 0;
        }
        .navbar .user-menu span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 0;
                max-width: 100vw;
            }
            .py-5 {
                padding-top: 1.5rem !important;
                padding-bottom: 1.5rem !important;
            }
            .card {
                border-radius: 22px !important; /* Mantém os 4 cantos arredondados no mobile */
                margin-bottom: 1.2rem;
                min-width: 95vw;
                max-width: 95vw;
                box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            }
            .card-body {
                padding: 2rem 1rem;
            }
            .row.gy-4.justify-content-center {
                --bs-gutter-y: 1.2rem;
                margin-left: 0;
                margin-right: 0;
            }
            .col-md-6.col-lg-4 {
                flex: 0 0 100%;
                max-width: 100%;
                padding-left: 0;
                padding-right: 0;
            }
            .display-4 {
                font-size: 2.5rem;
            }
            h2.text-center {
                font-size: 2rem;
            }
            .navbar .btn-black-custom {
                margin-right: 0;
            }
            .navbar .user-menu span {
                max-width: 80px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #ffc107;">
    <div class="container-fluid px-3">
        <div class="row w-100 align-items-center">
            <div class="col-6 d-flex align-items-center">
                <a class="navbar-brand text-dark fw-bold m-0" href="#">Suporte TI</a>
            </div>
            <div class="col-6 user-menu">
                <span class="text-dark fw-bold me-3 text-end"><?= htmlspecialchars($usuario['nome']) ?> (<?= $usuario['tipo'] ?>)</span>
                <a class="btn btn-black-custom ms-2" href="../logout.php">Sair</a>
            </div>
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
