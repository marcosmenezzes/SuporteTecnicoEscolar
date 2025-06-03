<?php
class BancoDeDados {
    private static $conn;

    public static function conectar() {
        if (!self::$conn) {
            $host = 'localhost';
            $dbname = 'suporte';
            $user = 'root';
            $pass = '';
            try {
                self::$conn = new PDO(
                    "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                    $user,
                    $pass
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
