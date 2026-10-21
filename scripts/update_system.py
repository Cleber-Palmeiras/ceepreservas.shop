# -*- coding: utf-8 -*-
"""
Script de Modernizacao CEEP Reservas 2026
Gera os arquivos atualizados com arquitetura segura, responsiva e moderna.
"""
import os

files = {}

# 1. index.php (Tela de Login)
files['index.php'] = """<?php
/**
 * CEEP Reservas - Tela de Login Modernizada (2026)
 */

require_once __DIR__ . '/core/bootstrap.php';

// Se ja estiver logado, redireciona para a Home
if (auth_check()) {
    header('Location: home.php');
    exit();
}

$erro = null;
if (isset($_SESSION['login']) && $_SESSION['login'] === 'false') {
    $erro = 'Credenciais incorretas ou usuario nao encontrado. Por favor, tente novamente.';
    unset($_SESSION['login']);
}

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — CEEP Reservas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="core/app.css?v=2026.1">
</head>
<body class="login-body">
    <div class="login-card animate-slide-in">
        <div class="login-brand">
            <div class="login-icon-box">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <path d="m9 16 2 2 4-4"></path>
                </svg>
            </div>
            <h1 class="login-title">CEEP <span style="color: var(--primary);">Reservas</span></h1>
            <p class="login-subtitle">Sistema de Agendamento de Salas e Recursos</p>
        </div>

        <?php if ($flash): ?>
            <div class="ceep-alert alert-<?= e($flash['type']) ?> animate-slide-in">
                <div class="alert-content">
                    <?php if (!empty($flash['title'])): ?><strong><?= e($flash['title']) ?></strong><br><?php endif; ?>
                    <?= e($flash['message']) ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="ceep-alert alert-error animate-slide-in">
                <div class="alert-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </div>
                <div class="alert-content"><?= e($erro) ?></div>
            </div>
        <?php endif; ?>

        <form action="verifica_usuario.php" method="POST" class="login-form">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="matricula" class="form-label">Matrícula ou CPF</label>
                <input 
                    type="text" 
                    id="matricula" 
                    name="matricula" 
                    class="form-control" 
                    placeholder="Digite sua matrícula ou CPF" 
                    required 
                    autocomplete="username"
                    inputmode="numeric"
                    autofocus
                >
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                    <label for="senha" class="form-label" style="margin-bottom: 0;">Senha</label>
                    <a href="recuperar_senha.php" style="font-size: 0.8rem; color: var(--primary); text-decoration: none; font-weight: 500;">Esqueceu a senha?</a>
                </div>
                <div class="password-input-wrap">
                    <input 
                        type="password" 
                        id="senha" 
                        name="senha" 
                        class="form-control" 
                        placeholder="Sua senha de acesso" 
                        required 
                        autocomplete="current-password"
                    >
                    <button type="button" class="password-toggle-btn" aria-label="Mostrar ou ocultar senha">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <div style="margin-top: 1.75rem;">
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                    <span>Acessar Painel</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </form>

        <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--border-color); text-align: center;">
            <span style="font-size: 0.82rem; color: var(--slate-500);">CEEP — Centro Estadual de Educação Profissional</span>
        </div>
    </div>

    <script src="core/app.js?v=2026.1"></script>
</body>
</html>
"""

# 2. verifica_usuario.php (Login Seguro com Prepared Statement e Rehash)
files['verifica_usuario.php'] = """<?php
/**
 * CEEP Reservas - Processamento Seguro de Login
 */

require_once __DIR__ . '/core/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$rawInput = trim($_POST['matricula'] ?? '');
$senha = $_POST['senha'] ?? '';

// Permite buscar tanto por matrícula quanto por CPF (apenas números)
$identificador = preg_replace('/\\D/', '', $rawInput);

if (empty($identificador) || empty($senha)) {
    $_SESSION['login'] = 'false';
    header('Location: index.php');
    exit();
}

try {
    // Busca usuário por matrícula ou por CPF usando prepared statements
    $user = DB::fetchOne(
        "SELECT * FROM usuarios WHERE (matricula = ? OR cpf = ?) LIMIT 1",
        [$identificador, $identificador]
    );

    if ($user && verify_and_upgrade_password($senha, $user['senha'], (int)$user['usuario_id'])) {
        // Regenera ID de sessão para prevenir fixation attacks
        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $user['usuario_id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['sobrenome'] = $user['sobrenome'] ?? '';
        $_SESSION['cpf'] = $user['cpf'];
        $_SESSION['matricula'] = $user['matricula'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['tipo'] = $user['tipo']; // 'a' ou 'p'
        $_SESSION['nivel_usuario'] = $user['nivel_usuario'] ?? '0';
        $_SESSION['data_ultimo_login'] = $user['data_ultimo_login'] ?? date('Y-m-d H:i:s');
        $_SESSION['login'] = 'true';

        // Atualiza data do último login
        DB::execute(
            "UPDATE usuarios SET data_ultimo_login = ? WHERE usuario_id = ?",
            [date('Y-m-d H:i:s'), $user['usuario_id']]
        );

        set_flash('success', 'Bem-vindo de volta, ' . e($user['nome']) . '!', 'Login efetuado com sucesso');
        header('Location: home.php');
        exit();
    } else {
        $_SESSION['login'] = 'false';
        header('Location: index.php');
        exit();
    }
} catch (Throwable $t) {
    error_log('Login error: ' . $t->getMessage());
    $_SESSION['login'] = 'false';
    header('Location: index.php');
    exit();
}
"""

# 3. verifica_acesso.php e functions.php
files['verifica_acesso.php'] = """<?php
require_once __DIR__ . '/core/bootstrap.php';

class verifica_acesso {
    public function checar_se_foi_logado() {
        auth_require(['a', 'p']);
    }

    public function destruir() {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
"""

files['functions.php'] = """<?php
require_once __DIR__ . '/core/bootstrap.php';

if (!function_exists('session_checker')) {
    function session_checker() {
        auth_require(['a', 'p']);
    }
}
"""

# 4. logout.php
files['logout.php'] = """<?php
require_once __DIR__ . '/core/bootstrap.php';

if (session_status() === PHP_SESSION_ACTIVE) {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

header("Location: index.php");
exit();
"""

# Escreve os arquivos base
for filename, content in files.items():
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Gerado: {filename}")
