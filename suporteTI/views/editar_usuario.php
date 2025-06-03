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

// Verifica se vai editar ou excluir
if (isset($_GET['excluir'])) {
    Usuario::excluir((int)$_GET['excluir']);
    header("Location: usuarios.php");
    exit;
}

$modoEdicao = false;
$dadosForm = ['nome'=>'','email'=>'','tipo'=>'Solicitante'];
$titulo = "Novo Usuário";

if (isset($_GET['id'])) {
    $modoEdicao = true;
    $idEd = (int)$_GET['id'];
    $u = Usuario::buscarPorId($idEd);
    if (!$u) {
        header("Location: usuarios.php");
        exit;
    }
    $dadosForm = $u;
    $titulo = "Editar Usuário";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $tipo  = $_POST['tipo'];
    $senha = $_POST['senha'] ?? '';

    if ($modoEdicao) {
        $updateData = ['nome'=>$nome,'email'=>$email,'tipo'=>$tipo,'senha'=>$senha];
        Usuario::atualizar($idEd, $updateData);
    } else {
        $novoData = ['nome'=>$nome,'email'=>$email,'senha'=>$senha,'tipo'=>$tipo];
        Usuario::criar($novoData);
    }
    header("Location: usuarios.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
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
                    <span class="text-white"><?= htmlspecialchars($usuario['nome']) ?> (Administrador)</span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light" href="../logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <h3><?= $titulo ?></h3>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Nome:</label>
            <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($dadosForm['nome']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($dadosForm['email']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo de Usuário:</label>
            <select name="tipo" class="form-select">
                <option value="Administrador" <?= $dadosForm['tipo']==='Administrador'?'selected':'' ?>>Administrador</option>
                <option value="Tecnico" <?= $dadosForm['tipo']==='Tecnico'?'selected':'' ?>>Técnico</option>
                <option value="Solicitante" <?= $dadosForm['tipo']==='Solicitante'?'selected':'' ?>>Solicitante</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label"><?= $modoEdicao ? 'Nova senha (deixe vazio para manter)' : 'Senha' ?>:</label>
            <input type="password" name="senha" class="form-control" <?= $modoEdicao ? '' : 'required' ?>>
        </div>
        <button type="submit" class="btn btn-success"><?= $modoEdicao ? 'Atualizar' : 'Criar' ?></button>
        <a href="usuarios.php" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scripts.js"></script>
</body>
</html>
