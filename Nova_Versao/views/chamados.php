<?php
require_once __DIR__ . '/../Sessao.php';
Sessao::iniciar();
if (!Sessao::estaLogado()) {
    header("Location: ../login.php");
    exit;
}
$usuario = Sessao::getUsuario();
require_once __DIR__ . '/../classes/Chamado.php';

$listaChamados = Chamado::listarPorUsuario($usuario);

// Para popular selects de categorias e setores:
$db = BancoDeDados::conectar();
$cats = $db->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$set = $db->query("SELECT id, nome FROM setores ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados - Suporte TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Suporte TI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item mx-auto">
                    <span class="text-white">Olá, <?= htmlspecialchars($usuario['nome']) ?> (<?= $usuario['tipo'] ?>)</span>
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
        <h3 class="fw-bold">Gerenciamento de Chamados</h3>
        <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Voltar</a>
    </div>
    
    <?php if ($usuario['tipo'] === 'Solicitante'): ?>
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0"><i class="bi bi-plus-circle me-2"></i>Abrir Novo Chamado</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="../controllers/ControladorChamado.php">
                <input type="hidden" name="acao" value="criarChamado">
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label class="form-label">Categoria:</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($cats as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label class="form-label">Setor:</label>
                        <select name="setor_id" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($set as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Descrição do Problema:</label>
                        <textarea name="descricao" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Enviar Chamado</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="bi bi-list-check me-2"></i>Seus Chamados</h5>
                <div class="d-flex">
                    <input type="text" id="buscaChamado" class="form-control" placeholder="Buscar chamado...">
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (count($listaChamados) === 0): ?>
                <p class="text-muted">Nenhum chamado encontrado.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($listaChamados as $ch): ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card chamado-item h-100">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <span>#<?= $ch['id'] ?></span>
                                    <span class="badge 
                                        <?php 
                                            if ($ch['status'] === 'Aberto') echo 'bg-warning';
                                            elseif ($ch['status'] === 'Em atendimento') echo 'bg-info';
                                            else echo 'bg-success';
                                        ?>">
                                        <?= $ch['status'] ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title"><?= htmlspecialchars($ch['descricao']) ?></h6>
                                    <p class="card-text">
                                        <small class="text-muted">Aberto em <?= $ch['data_abertura'] ?></small><br>
                                        <small class="text-muted">Categoria: <?= htmlspecialchars($ch['categoria']) ?></small><br>
                                        <small class="text-muted">Setor: <?= htmlspecialchars($ch['setor']) ?></small><br>
                                        <small class="text-muted">Solicitante: <?= htmlspecialchars($ch['solicitante_nome']) ?></small>
                                        <?php if ($ch['tecnico_nome']): ?>
                                            <br><small class="text-muted">Técnico: <?= htmlspecialchars($ch['tecnico_nome']) ?></small>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-top-0 text-end">
                                    <a href="detalhe_chamado.php?id=<?= $ch['id'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
