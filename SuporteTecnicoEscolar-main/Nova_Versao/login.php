<?php
require_once 'BancoDeDados.php';
require_once 'Sessao.php';
require_once 'classes/Usuario.php';

Sessao::iniciar();

// Se já estiver logado, manda ao dashboard
if (Sessao::estaLogado()) {
    header("Location: views/dashboard.php");
    exit;
}

$erro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email  = trim($_POST['email']);
    $senha  = trim($_POST['senha']);
    $user = Usuario::autenticar($email, $senha);
    if ($user) {
        Sessao::setUsuario($user);
        header("Location: views/dashboard.php");
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Suporte TI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            min-height: 100vh;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .container {
            max-width: 100vw;
            width: 100vw;
            padding: 0;
        }
        .card {
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border: 2px solid #e3e6f0; /* Azul claro ou cinza claro */
            background: #fff;
        }
        .card-body {
            padding: 2rem 1.5rem;
        }
        .row.justify-content-center.align-items-center {
            min-height: 100vh;
        }
        @media (max-width: 600px) {
            .card {
                margin: 0;
                border-radius: 0;
                min-height: unset; /* Removido o min-height para evitar excesso de espaço */
                display: block;
                align-items: unset;
            }
            .card-body {
                width: 100vw;
                padding: 2rem 1.2rem;
            }
            .row.justify-content-center.align-items-center {
                min-height: 80vh; /* Reduz a altura ocupada no mobile */
            }
        }
        .form-control, .btn {
            font-size: 1.2rem;
            min-height: 3rem;
        }
        .btn {
            padding: 0.75rem 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height:100vh;">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4 px-0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Login</h3>

                    <?php if ($erro): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
