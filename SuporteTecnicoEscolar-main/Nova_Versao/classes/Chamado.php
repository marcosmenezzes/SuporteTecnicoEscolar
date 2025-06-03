<?php
require_once __DIR__ . '/../BancoDeDados.php';

class Chamado {

    /**
     * Lista todos os chamados de acordo com o tipo de usuário:
     * - Administrador: todos
     * - Técnico: somente chamados atribuídos a ele
     * - Solicitante: somente chamados abertos por ele
     * @param array $usuario ['id', 'tipo', ...]
     * @return array de arrays associativos com campos de chamados JOIN com categorias, setores, nomes de solicitante/tecnico
     */
    public static function listarPorUsuario($usuario) {
        $db = BancoDeDados::conectar();
        $sqlBase = "
            SELECT 
                c.id,
                c.descricao,
                c.status,
                c.data_abertura,
                c.data_conclusao,
                c.solucao,
                cat.nome AS categoria,
                s.nome AS setor,
                u_solic.nome AS solicitante_nome,
                u_tec.nome AS tecnico_nome
            FROM chamados c
            JOIN categorias cat ON c.categoria_id = cat.id
            JOIN setores s ON c.setor_id = s.id
            JOIN usuarios u_solic ON c.solicitante_id = u_solic.id
            LEFT JOIN usuarios u_tec ON c.tecnico_id = u_tec.id
        ";

        switch ($usuario['tipo']) {
            case 'Administrador':
                $stmt = $db->prepare($sqlBase . " ORDER BY c.data_abertura DESC");
                $stmt->execute();
                break;

            case 'Tecnico':
                $stmt = $db->prepare($sqlBase . " WHERE c.tecnico_id = ? ORDER BY c.data_abertura DESC");
                $stmt->execute([$usuario['id']]);
                break;

            case 'Solicitante':
                $stmt = $db->prepare($sqlBase . " WHERE c.solicitante_id = ? ORDER BY c.data_abertura DESC");
                $stmt->execute([$usuario['id']]);
                break;

            default:
                return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cria um novo chamado (sempre pelo solicitante). O campo 'tecnico_id' fica NULL.
     * @param array $dados ['solicitante_id','categoria_id','setor_id','descricao']
     * @return bool
     */
    public static function criar($dados) {
        $db = BancoDeDados::conectar();
        $stmt = $db->prepare("
            INSERT INTO chamados (solicitante_id, categoria_id, setor_id, descricao)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $dados['solicitante_id'],
            $dados['categoria_id'],
            $dados['setor_id'],
            $dados['descricao']
        ]);
    }

    /**
     * Busca detalhes de um chamado por ID (incluindo JOINs).
     * @param int $id
     * @return array|null
     */
    public static function buscarPorId($id) {
        $db = BancoDeDados::conectar();
        $stmt = $db->prepare("
            SELECT 
                c.*,
                cat.nome AS categoria,
                s.nome AS setor,
                u_solic.nome AS solicitante_nome,
                u_tec.nome AS tecnico_nome
            FROM chamados c
            JOIN categorias cat ON c.categoria_id = cat.id
            JOIN setores s ON c.setor_id = s.id
            JOIN usuarios u_solic ON c.solicitante_id = u_solic.id
            LEFT JOIN usuarios u_tec ON c.tecnico_id = u_tec.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Atualiza status, técnico e/ou solução de um chamado.
     * Se for admin, pode atribuir técnico. Se for técnico, pode atualizar status e solucao.
     * @param int $id
     * @param array $dados ['tecnico_id' (opcional), 'status', 'solucao' (opcional)]
     * @return bool
     */
    public static function atualizar($id, $dados) {
        $db = BancoDeDados::conectar();
        // Monta dinamicamente a query:
        $campos = [];
        $valores = [];

        if (isset($dados['tecnico_id'])) {
            $campos[] = "tecnico_id = ?";
            $valores[] = $dados['tecnico_id'];
        }
        if (isset($dados['status'])) {
            $campos[] = "status = ?";
            $valores[] = $dados['status'];
            if ($dados['status'] === 'Concluído') {
                $campos[] = "data_conclusao = NOW()";
            }
        }
        if (isset($dados['solucao'])) {
            $campos[] = "solucao = ?";
            $valores[] = $dados['solucao'];
        }

        if (count($campos) === 0) {
            return false;
        }

        $sql = "UPDATE chamados SET " . implode(', ', $campos) . " WHERE id = ?";
        $valores[] = $id;
        $stmt = $db->prepare($sql);
        return $stmt->execute($valores);
    }
}
?>
