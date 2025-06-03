<?php
require_once __DIR__ . '/../Sessao.php';
require_once __DIR__ . '/../classes/Chamado.php';
require_once __DIR__ . '/../classes/Usuario.php';

Sessao::iniciar();

// Se não estiver logado, redireciona para login
if (!Sessao::estaLogado()) {
    header("Location: ../login.php");
    exit;
}

$usuario = Sessao::getUsuario();

// Criar chamado (somente solicitante)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'criarChamado') {
    $dados = [
        'solicitante_id' => $usuario['id'],
        'categoria_id'   => $_POST['categoria_id'],
        'setor_id'       => $_POST['setor_id'],
        'descricao'      => trim($_POST['descricao'])
    ];
    Chamado::criar($dados);
    header("Location: ../views/chamados.php");
    exit;
}

// Atualizar chamado (admin ou técnico)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizarChamado') {
    $dados = [
        'status' => $_POST['status']
    ];
    // Se admin, pode atribuir técnico
    if ($usuario['tipo'] === 'Administrador' && isset($_POST['tecnico_id'])) {
        $dados['tecnico_id'] = $_POST['tecnico_id'];
    }
    // Se técnico, pode registrar solução
    if ($usuario['tipo'] === 'Tecnico' && isset($_POST['solucao'])) {
        $dados['solucao'] = trim($_POST['solucao']);
    }
    Chamado::atualizar($_POST['id'], $dados);
    header("Location: ../views/detalhe_chamado.php?id=" . $_POST['id']);
    exit;
}
?>
