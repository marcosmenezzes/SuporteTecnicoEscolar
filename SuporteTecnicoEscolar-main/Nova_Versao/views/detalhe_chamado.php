<?php
require_once __DIR__ . '/../Sessao.php';
Sessao::iniciar();
if (!Sessao::estaLogado()) {
    header("Location: ../login.php");
    exit;
}
$usuario = Sessao::getUsuario();

require_once __DIR__ . '/../classes/Chamado.php';
require_once __DIR__ . '/../classes/Usuario.php';

if (!isset($_GET['id'])) {
    header("Location: chamados.php");
    exit;
}

$idCham = (int)$_GET['id'];
$ch = Chamado::buscarPorId($idCham);
if (!$ch) {
    header("Location: chamados.php");
    exit;
}

// Permissão de acesso: 
// - Admin pode ver tudo
// - Técnico só se for atribuído
// - Solicitante só se for seu próprio
$acessoLiberado = false;
if ($usuario['tipo'] === 'Administrador') {
    $acessoLiberado = true;
} elseif ($usuario['tipo'] === 'Tecnico' && $ch['tecnico_id'] == $usuario['id']) {
    $acessoLiberado = true;
} elseif ($usuario['tipo'] === 'Solicitante' && $ch['solicitante_id'] == $usuario['id']) {
    $acessoLiberado = true;
}

if (!$acessoLiberado) {
    header("Location: chamados.php");
    exit;
}

// Lista de técnicos (para admin atribuir)
$tecnicos = [];
if ($usuario['tipo'] === 'Administrador') {
    $tecnicos = Usuario::listarTodos();
    // Filtra apenas técnicos
    $tecnicos = array_filter($tecnicos, fn($u) => $u['tipo'] === 'Tecnico');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhe do Chamado #<?= $ch['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg <?=
    $usuario['tipo'] === 'Administrador' ? 'navbar-dark bg-danger' :
    ($usuario['tipo'] === 'Tecnico' ? 'navbar-dark bg-info' : 'navbar-dark bg-success');
?>">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Suporte TI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item mx-auto">
                    <span class="text-white"><?= htmlspecialchars($usuario['nome']) ?> (<?= $usuario['tipo'] ?>)</span>
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
        <h3 class="fw-bold">Chamado #<?= $ch['id'] ?></h3>
        <a href="chamados.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Voltar</a>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Detalhes do Chamado</h5>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($ch['descricao']) ?></h5>
            <div class="row mt-3">
                <div class="col-md-6">
                    <p class="card-text">
                        <strong>Status:</strong>
                        <span class="badge 
                            <?php 
                                if ($ch['status'] === 'Aberto') echo 'bg-warning';
                                elseif ($ch['status'] === 'Em atendimento') echo 'bg-info';
                                else echo 'bg-success';
                            ?>">
                            <?= $ch['status'] ?>
                        </span><br>
                        <strong>Categoria:</strong> <?= htmlspecialchars($ch['categoria']) ?><br>
                        <strong>Setor:</strong> <?= htmlspecialchars($ch['setor']) ?><br>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="card-text">
                        <strong>Abertura:</strong> <?= $ch['data_abertura'] ?><br>
                        <?php if ($ch['data_conclusao']): ?>
                            <strong>Conclusão:</strong> <?= $ch['data_conclusao'] ?><br>
                        <?php endif; ?>
                        <strong>Solicitante:</strong> <?= htmlspecialchars($ch['solicitante_nome']) ?><br>
                        <strong>Técnico:</strong> <?= $ch['tecnico_nome'] ?? '<em>Não atribuído</em>' ?><br>
                    </p>
                </div>
            </div>
            <?php if ($ch['solucao']): ?>
                <div class="mt-3">
                    <strong>Solução:</strong>
                    <div class="p-3 bg-light rounded">
                        <?= nl2br(htmlspecialchars($ch['solucao'])) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($usuario['tipo'] === 'Administrador' || ($usuario['tipo'] === 'Tecnico' && $ch['tecnico_id'] == $usuario['id'])): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <?= $usuario['tipo'] === 'Administrador' ? 'Atribuir Técnico / Alterar Status' : 'Atualizar Status / Registrar Solução' ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if ($usuario['tipo'] === 'Administrador'): ?>
                    <form method="POST" action="../controllers/ControladorChamado.php">
                        <input type="hidden" name="acao" value="atualizarChamado">
                        <input type="hidden" name="id" value="<?= $ch['id'] ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Atribuir Técnico:</label>
                                <select name="tecnico_id" class="form-select">
                                    <option value="">-- Sem Alteração --</option>
                                    <?php foreach ($tecnicos as $t): ?>
                                        <option value="<?= $t['id'] ?>" 
                                            <?= ($ch['tecnico_id'] == $t['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($t['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status:</label>
                                <select name="status" class="form-select" required>
                                    <option value="Aberto" <?= $ch['status']==='Aberto'?'selected':'' ?>>Aberto</option>
                                    <option value="Em atendimento" <?= $ch['status']==='Em atendimento'?'selected':'' ?>>Em atendimento</option>
                                    <option value="Concluído" <?= $ch['status']==='Concluído'?'selected':'' ?>>Concluído</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger"><i class="bi bi-save me-2"></i>Salvar Alterações</button>
                    </form>
                <?php else: // Técnico ?>
                    <form method="POST" action="../controllers/ControladorChamado.php">
                        <input type="hidden" name="acao" value="atualizarChamado">
                        <input type="hidden" name="id" value="<?= $ch['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Status:</label>
                            <select name="status" class="form-select" required>
                                <option value="Aberto" <?= $ch['status']==='Aberto'?'selected':'' ?>>Aberto</option>
                                <option value="Em atendimento" <?= $ch['status']==='Em atendimento'?'selected':'' ?>>Em atendimento</option>
                                <option value="Concluído" <?= $ch['status']==='Concluído'?'selected':'' ?>>Concluído</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Solução:</label>
                            <textarea name="solucao" class="form-control" rows="3"><?= htmlspecialchars($ch['solucao']) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-info"><i class="bi bi-save me-2"></i>Salvar Alterações</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
