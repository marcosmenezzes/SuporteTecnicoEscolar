<?php
class Sessao {
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function estaLogado() {
        return isset($_SESSION['usuario']);
    }

    public static function setUsuario($userArray) {
        $_SESSION['usuario'] = $userArray;
    }

    public static function getUsuario() {
        return $_SESSION['usuario'] ?? null;
    }

    public static function encerrar() {
        session_unset();
        session_destroy();
    }
}
?>
