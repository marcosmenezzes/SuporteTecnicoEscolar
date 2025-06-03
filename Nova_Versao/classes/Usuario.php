<?php
require_once __DIR__ . '/../BancoDeDados.php';

class Usuario {
    protected $id;
    protected $nome;
    protected $email;
    protected $tipo;

    public function __construct($id, $nome, $email, $tipo) {
        $this->id    = $id;
        $this->nome  = $nome;
        $this->email = $email;
        $this->tipo  = $tipo;
    }

    // Getters básicos
    public function getId()    { return $this->id; }
    public function getNome()  { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getTipo()  { return $this->tipo; }

    /** 
     * Retorna lista de todos os usuários (somente para Administrador). 
     * @return array de arrays associativos com campos: id, nome, email, tipo
     */
    public static function listarTodos() {
        $db = BancoDeDados::conectar();
        $stmt = $db->query("SELECT id, nome, email, tipo FROM usuarios ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um usuário por ID.
     * @param int $id
     * @return array|null (campos id, nome, email, tipo) ou null se não existir.
     */
    public static function buscarPorId($id) {
        $db = BancoDeDados::conectar();
        $stmt = $db->prepare("SELECT id, nome, email, tipo FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Cria um novo usuário no banco.
     * @param array $dados [nome, email, senha (sem hash), tipo]
     * @return bool
     */
    public static function criar($dados) {
        $db = BancoDeDados::conectar();
        $hash = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $hash,
            $dados['tipo']
        ]);
    }

    /**
     * Atualiza dados de um usuário existente.
     * Se $dados['senha'] estiver vazio, não altera a senha.
     * @param int $id
     * @param array $dados [nome, email, tipo, [senha]]
     * @return bool
     */
    public static function atualizar($id, $dados) {
        $db = BancoDeDados::conectar();
        if (!empty($dados['senha'])) {
            $hash = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ?, tipo = ? WHERE id = ?");
            return $stmt->execute([
                $dados['nome'],
                $dados['email'],
                $hash,
                $dados['tipo'],
                $id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id = ?");
            return $stmt->execute([
                $dados['nome'],
                $dados['email'],
                $dados['tipo'],
                $id
            ]);
        }
    }

    /**
     * Exclui um usuário.
     * @param int $id
     * @return bool
     */
    public static function excluir($id) {
        $db = BancoDeDados::conectar();
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Autentica login: retorna array do usuário (id, nome, email, tipo)
     * ou null caso credenciais inválidas.
     */
    public static function autenticar($email, $senha) {
        $db = BancoDeDados::conectar();
        $stmt = $db->prepare("SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $linha = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($linha && password_verify($senha, $linha['senha'])) {
            return [
                'id'    => $linha['id'],
                'nome'  => $linha['nome'],
                'email' => $linha['email'],
                'tipo'  => $linha['tipo']
            ];
        }
        return null;
    }
}
?>
