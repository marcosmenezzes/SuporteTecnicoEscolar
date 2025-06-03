<?php
// classes/Tecnico.php

require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Chamado.php';

class Tecnico extends Usuario {

    /**
     * Retorna todos os chamados atribuídos a este técnico.
     * @return array de arrays associativos com os dados dos chamados
     */
    public function listarChamados() {
        // Como Chamado::listarPorUsuario exige ['id','tipo'], aproveitamos os dados do próprio objeto
        $usuario = [
            'id'   => $this->getId(),
            'tipo' => 'Tecnico'
        ];
        return Chamado::listarPorUsuario($usuario);
    }

    /**
     * Atualiza o status e/ou solução de um chamado atribuído a este técnico.
     * Só permite alterar se este técnico realmente estiver vinculado ao chamado.
     *
     * @param int    $chamadoId ID do chamado a ser atualizado
     * @param string $status    Novo status ('Aberto', 'Em atendimento' ou 'Concluído')
     * @param string|null $solucao Texto com a solução (opcional; só faz sentido quando status = 'Concluído')
     * @return bool true em caso de sucesso, false caso falhe ou não pertença a este técnico
     */
    public function atualizarChamado($chamadoId, $status, $solucao = null) {
        // 1) Verificar se este chamado realmente pertence a este técnico
        $detalhe = Chamado::buscarPorId($chamadoId);
        if (!$detalhe) {
            return false; // chamado não existe
        }
        if ((int)$detalhe['tecnico_id'] !== (int)$this->getId()) {
            return false; // chamado não atribuído a este técnico
        }

        // 2) Monta array de dados para atualização
        $dados = ['status' => $status];
        if ($solucao !== null) {
            $dados['solucao'] = trim($solucao);
        }

        // 3) Chama o método de atualização
        return Chamado::atualizar($chamadoId, $dados);
    }
}
?>
