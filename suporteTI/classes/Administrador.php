<?php
// classes/Administrador.php

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Chamado.php';

class Administrador extends Usuario {

    /**
     * Retorna todos os usuários do sistema.
     * @return array de arrays associativos com campos: id, nome, email, tipo
     */
    public function listarUsuarios() {
        return Usuario::listarTodos();
    }

    /**
     * Cria um novo usuário (Administrador, Técnico ou Solicitante).
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @param string $tipo ('Administrador', 'Tecnico' ou 'Solicitante')
     * @return bool
     */
    public function criarUsuario($nome, $email, $senha, $tipo) {
        $dados = [
            'nome'  => $nome,
            'email' => $email,
            'senha' => $senha,
            'tipo'  => $tipo
        ];
        return Usuario::criar($dados);
    }

    /**
     * Atualiza um usuário existente.
     * @param int    $id
     * @param string $nome
     * @param string $email
     * @param string $tipo  ('Administrador', 'Tecnico' ou 'Solicitante')
     * @param string $senha (se vazio, mantém a senha atual)
     * @return bool
     */
    public function atualizarUsuario($id, $nome, $email, $tipo, $senha = '') {
        $dados = [
            'nome'  => $nome,
            'email' => $email,
            'tipo'  => $tipo,
            'senha' => $senha
        ];
        return Usuario::atualizar($id, $dados);
    }

    /**
     * Exclui um usuário pelo ID.
     * @param int $id
     * @return bool
     */
    public function excluirUsuario($id) {
        return Usuario::excluir($id);
    }

    /**
     * Retorna todos os chamados do sistema (visão completa de administrador).
     * @return array de arrays associativos com dados completos de cada chamado
     */
    public function listarTodosChamados() {
        // Chamado::listarPorUsuario filtra pelo tipo; para administrador, passamos tipo 'Administrador'
        $usuario = [
            'id'   => $this->getId(),
            'tipo' => 'Administrador'
        ];
        return Chamado::listarPorUsuario($usuario);
    }
}
?>
