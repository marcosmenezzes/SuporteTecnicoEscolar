<?php
// classes/Solicitante.php

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Chamado.php';

class Solicitante extends Usuario {

    /**
     * Retorna todos os chamados abertos por este solicitante.
     * @return array de arrays associativos com os dados dos chamados
     */
    public function listarChamados() {
        // Chamado::listarPorUsuario espera um array ['id','tipo']
        $usuario = [
            'id'   => $this->getId(),
            'tipo' => 'Solicitante'
        ];
        return Chamado::listarPorUsuario($usuario);
    }

    /**
     * Abre um novo chamado para este solicitante.
     * @param int    $categoriaId ID da categoria escolhida
     * @param int    $setorId     ID do setor correspondente
     * @param string $descricao   Descrição do problema
     * @return bool true em caso de sucesso, false caso falhe
     */
    public function abrirChamado($categoriaId, $setorId, $descricao) {
        $dados = [
            'solicitante_id' => $this->getId(),
            'categoria_id'   => (int)$categoriaId,
            'setor_id'       => (int)$setorId,
            'descricao'      => trim($descricao)
        ];
        return Chamado::criar($dados);
    }
}
?>
