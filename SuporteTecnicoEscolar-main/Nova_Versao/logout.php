<?php
require_once 'Sessao.php';
Sessao::iniciar();
Sessao::encerrar();
header("Location: login.php");
exit;
?>
