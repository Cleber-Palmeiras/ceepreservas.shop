<?php
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
    <link rel="stylesheet" href="core/app.css?v=2026.3">
</head>
<body class="login-body">
    <div class="login-card animate-slide-in">
        <div class="login-brand">
            <div style="display: flex; justify-content: center; margin-bottom: 0.85rem;">
                <img src="CRTI.png" alt="Logo CRTI" class="login-logo-img" style="height: 80px !important; max-height: 80px !important; width: auto !important; max-width: 95px !important; object-fit: contain !important; margin: 0 auto; display: block;" height="80">
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
