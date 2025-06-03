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
    <title>Detalhe do Chamado #<?= $ch['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg <?=
    $usuario['tipo'] === 'Administrador' ? 'navbar-dark bg-danger' :
    ($usuario['tipo'] === 'Tecnico' ? 'navbar-dark bg-info' : 'navbar-dark bg-success');
?>">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Suporte TI</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3">
                    <span class="text-white"><?= htmlspecialchars($usuario['nome']) ?> (<?= $usuario['tipo'] ?>)</span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light" href="../logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <h3>Chamado #<?= $ch['id'] ?></h3>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($ch['descricao']) ?></h5>
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
                <strong>Abertura:</strong> <?= $ch['data_abertura'] ?><br>
                <?php if ($ch['data_conclusao']): ?>
                    <strong>Conclusão:</strong> <?= $ch['data_conclusao'] ?><br>
                <?php endif; ?>
                <strong>Solicitante:</strong> <?= htmlspecialchars($ch['solicitante_nome']) ?><br>
                <strong>Técnico:</strong> <?= $ch['tecnico_nome'] ?? '<em>Não atribuído</em>' ?><br>
                <?php if ($ch['solucao']): ?>
                    <strong>Solução:</strong><br>
                    <p><?= nl2br(htmlspecialchars($ch['solucao'])) ?></p>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <?php if ($usuario['tipo'] === 'Administrador' || ($usuario['tipo'] === 'Tecnico' && $ch['tecnico_id'] == $usuario['id'])): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <?php if ($usuario['tipo'] === 'Administrador'): ?>
                    <h5 class="mb-3">Atribuir Técnico / Alterar Status</h5>
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

                        <button type="submit" class="btn btn-danger">Salvar Alterações</button>
                    </form>
                <?php else: // Técnico ?>
                    <h5 class="mb-3">Atualizar Status / Registrar Solução</h5>
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
                        <button type="submit" class="btn btn-info">Salvar Alterações</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <a href="chamados.php" class="btn btn-secondary">Voltar</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
