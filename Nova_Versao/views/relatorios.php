<?php
// views/relatorios.php

// 1) Inclui a classe de conexão com o banco de dados
require_once __DIR__ . '/../BancoDeDados.php';

// 2) Inclui a classe de sessão
require_once __DIR__ . '/../Sessao.php';

Sessao::iniciar();

// 3) Verifica se o usuário está logado
if (!Sessao::estaLogado()) {
    header("Location: ../login.php");
    exit;
}

$usuario = Sessao::getUsuario();

// 4) Verifica se o usuário tem permissão (somente Administrador)
if ($usuario['tipo'] !== 'Administrador') {
    header("Location: dashboard.php");
    exit;
}

// 5) Conecta ao banco
$db = BancoDeDados::conectar();

// 6) Captura parâmetros de filtro via GET
$dataInicial     = $_GET['data_ini']    ?? '';
$dataFinal       = $_GET['data_fim']    ?? '';
$statusFiltro    = $_GET['status']      ?? '';
$categoriaFiltro = $_GET['categoria']   ?? '';

// 7) Monta cláusulas dinâmicas e parâmetros
$clauses = [];
$params  = [];

if ($dataInicial) {
    $clauses[] = "c.data_abertura >= ?";
    $params[]  = $dataInicial . " 00:00:00";
}
if ($dataFinal) {
    $clauses[] = "c.data_abertura <= ?";
    $params[]  = $dataFinal . " 23:59:59";
}
if ($statusFiltro) {
    $clauses[] = "c.status = ?";
    $params[]  = $statusFiltro;
}
if ($categoriaFiltro) {
    $clauses[] = "c.categoria_id = ?";
    $params[]  = $categoriaFiltro;
}

$where = '';
if (count($clauses) > 0) {
    $where = 'WHERE ' . implode(' AND ', $clauses);
}

// 8) Monta e executa a query de relatório
$sql = "
    SELECT 
        c.id,
        c.descricao,
        c.status,
        c.data_abertura,
        cat.nome AS categoria,
        u_solic.nome AS solicitante_nome,
        u_tec.nome AS tecnico_nome
    FROM chamados c
    JOIN categorias cat      ON c.categoria_id = cat.id
    JOIN usuarios u_solic    ON c.solicitante_id = u_solic.id
    LEFT JOIN usuarios u_tec ON c.tecnico_id = u_tec.id
    {$where}
    ORDER BY c.data_abertura DESC
";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 9) Busca lista de categorias para o select de filtro
$cats = $db->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Suporte TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Suporte TI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item mx-auto">
                        <span class="text-white">
                            Olá, <?= htmlspecialchars($usuario['nome']) ?> (Administrador)
                        </span>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-outline-light" href="../logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo -->
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="dashboard.php" class="btn btn-outline-secondary me-3">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                <h3 class="fw-bold mb-0">Relatórios de Chamados</h3>
            </div>
        </div>

        <!-- Formulário de Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0"><i class="bi bi-funnel me-2"></i>Filtros</h5>
            </div>
            <div class="card-body">
                <form class="row g-3" method="GET" action="">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-medium">Data Início:</label>
                        <input 
                            type="date" 
                            name="data_ini" 
                            class="form-control" 
                            value="<?= htmlspecialchars($dataInicial) ?>">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-medium">Data Fim:</label>
                        <input 
                            type="date" 
                            name="data_fim" 
                            class="form-control" 
                            value="<?= htmlspecialchars($dataFinal) ?>">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-medium">Status:</label>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="Aberto" <?= $statusFiltro === 'Aberto' ? 'selected' : '' ?>>Aberto</option>
                            <option value="Em atendimento" <?= $statusFiltro === 'Em atendimento' ? 'selected' : '' ?>>Em atendimento</option>
                            <option value="Concluído" <?= $statusFiltro === 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label fw-medium">Categoria:</label>
                        <select name="categoria" class="form-select">
                            <option value="">Todas</option>
                            <?php foreach ($cats as $c): ?>
                                <option 
                                    value="<?= $c['id'] ?>" 
                                    <?= $categoriaFiltro == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-2"></i>Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabela de Resultados -->
        <?php if (count($resultados) === 0): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>Nenhum chamado encontrado para os filtros informados.
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Resultados</h5>
                        <span class="badge bg-medium"><?= count($resultados) ?> chamados encontrados</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Abertura</th>
                                    <th>Categoria</th>
                                    <th>Solicitante</th>
                                    <th>Técnico</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultados as $r): ?>
                                    <tr>
                                        <td class="fw-medium"><?= $r['id'] ?></td>
                                        <td><?= htmlspecialchars($r['descricao']) ?></td>
                                        <td>
                                            <span class="badge <?php 
                                                if ($r['status'] === 'Aberto') echo 'bg-warning';
                                                elseif ($r['status'] === 'Em atendimento') echo 'bg-info';
                                                else echo 'bg-success';
                                            ?>">
                                                <?= $r['status'] ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($r['data_abertura'])) ?></td>
                                        <td><?= htmlspecialchars($r['categoria']) ?></td>
                                        <td><?= htmlspecialchars($r['solicitante_nome']) ?></td>
                                        <td><?= htmlspecialchars($r['tecnico_nome'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts Bootstrap e JavaScript customizado -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
