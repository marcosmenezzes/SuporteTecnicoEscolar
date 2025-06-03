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
    <title>Chamados - Suporte TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Suporte TI</a>
        <div class="collapse navbar-collapse">
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

<div class="container py-4">
    <?php if ($usuario['tipo'] === 'Solicitante'): ?>
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Abrir Novo Chamado</h5>
            <form method="POST" action="../controllers/ControladorChamado.php">
                <input type="hidden" name="acao" value="criarChamado">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Categoria:</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($cats as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
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
                        <button type="submit" class="btn btn-primary">Enviar Chamado</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <h4>Seus Chamados</h4>
    <input type="text" id="buscaChamado" class="form-control mb-3" placeholder="Buscar chamado...">
    <?php if (count($listaChamados) === 0): ?>
        <p class="text-muted">Nenhum chamado encontrado.</p>
    <?php else: ?>
        <?php foreach ($listaChamados as $ch): ?>
            <div class="card chamado-item mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        #<?= $ch['id'] ?> – <?= htmlspecialchars($ch['descricao']) ?>
                    </h5>
                    <p class="card-text">
                        <span class="badge 
                            <?php 
                                if ($ch['status'] === 'Aberto') echo 'bg-warning';
                                elseif ($ch['status'] === 'Em atendimento') echo 'bg-info';
                                else echo 'bg-success';
                            ?>">
                            <?= $ch['status'] ?>
                        </span>
                        <small class="text-muted">Aberto em <?= $ch['data_abertura'] ?></small><br>
                        <small class="text-muted">Categoria: <?= htmlspecialchars($ch['categoria']) ?> |
                            Setor: <?= htmlspecialchars($ch['setor']) ?></small><br>
                        <small class="text-muted">Solicitante: <?= htmlspecialchars($ch['solicitante_nome']) ?>
                            <?php if ($ch['tecnico_nome']): ?>
                                | Técnico: <?= htmlspecialchars($ch['tecnico_nome']) ?>
                            <?php endif; ?>
                        </small>
                    </p>
                    <a href="detalhe_chamado.php?id=<?= $ch['id'] ?>" class="btn btn-outline-primary btn-sm">Detalhes</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
