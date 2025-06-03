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
    <title>Relatórios - Suporte TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Suporte TI</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-3">
                        <span class="text-white">
                            Olá, <?= htmlspecialchars($usuario['nome']) ?> (Administrador)
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light" href="../logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo -->
    <div class="container py-4">
        <h3>Gerar Relatórios</h3>

        <!-- Formulário de Filtros -->
        <form class="row g-3 mb-4" method="GET" action="">
            <div class="col-md-3">
                <label class="form-label">Data Início:</label>
                <input 
                    type="date" 
                    name="data_ini" 
                    class="form-control" 
                    value="<?= htmlspecialchars($dataInicial) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Data Fim:</label>
                <input 
                    type="date" 
                    name="data_fim" 
                    class="form-control" 
                    value="<?= htmlspecialchars($dataFinal) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status:</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="Aberto" <?= $statusFiltro === 'Aberto' ? 'selected' : '' ?>>Aberto</option>
                    <option value="Em atendimento" <?= $statusFiltro === 'Em atendimento' ? 'selected' : '' ?>>Em atendimento</option>
                    <option value="Concluído" <?= $statusFiltro === 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Categoria:</label>
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
                <button type="submit" class="btn btn-danger">Filtrar</button>
            </div>
        </form>

        <!-- Tabela de Resultados -->
        <?php if (count($resultados) === 0): ?>
            <p class="text-muted">Nenhum chamado encontrado para os filtros informados.</p>
        <?php else: ?>
            <div class="table-responsive shadow-sm">
                <table class="table table-bordered">
                    <thead class="table-light">
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
                                <td><?= $r['id'] ?></td>
                                <td><?= htmlspecialchars($r['descricao']) ?></td>
                                <td><?= $r['status'] ?></td>
                                <td><?= $r['data_abertura'] ?></td>
                                <td><?= htmlspecialchars($r['categoria']) ?></td>
                                <td><?= htmlspecialchars($r['solicitante_nome']) ?></td>
                                <td><?= htmlspecialchars($r['tecnico_nome'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Scripts Bootstrap e JavaScript customizado -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
