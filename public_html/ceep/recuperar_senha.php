<?php
/**
 * CEEP Reservas - Recuperação de Senha
 */

require_once __DIR__ . '/core/bootstrap.php';

$mensagem = null;
$tipoMensagem = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificador = trim($_POST['identificador'] ?? '');

    if (!empty($identificador)) {
        $u = DB::fetchOne(
            "SELECT * FROM usuarios WHERE email = ? OR matricula = ? OR cpf = ?",
            [$identificador, $identificador, $identificador]
        );

        if ($u) {
            // Em ambiente escolar local, orienta o usuário ou envia e-mail conforme configurado
            $mensagem = "Localizamos sua conta ({$u['nome']}). Por favor, entre em contato com a coordenação pedagógica ou administrador para a reinicialização temporária da sua senha.";
            $tipoMensagem = 'info';
        } else {
            $mensagem = "Nenhuma conta foi localizada com os dados informados.";
            $tipoMensagem = 'error';
        }
    } else {
        $mensagem = "Por favor, informe seu e-mail, matrícula ou CPF.";
        $tipoMensagem = 'warning';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha — CEEP Reservas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="core/app.css?v=2026.4">
</head>
<body class="login-body">
    <div class="login-card animate-slide-in">
        <div class="login-brand">
            <div style="display: flex; justify-content: center; margin-bottom: 0.85rem;"><img src="CRTI.png" alt="Logo CRTI" class="login-logo-img" style="height: 80px !important; max-height: 80px !important; width: auto !important; max-width: 95px !important; object-fit: contain !important; margin: 0 auto; display: block;" height="80"></div>
            <h1 class="login-title">Recuperar <span style="color: var(--primary);">Senha</span></h1>
            <p class="login-subtitle">Identifique sua conta para orientações de acesso</p>
        </div>

        <?php if ($mensagem): ?>
            <div class="ceep-alert alert-<?= e($tipoMensagem) ?> animate-slide-in">
                <div class="alert-content"><?= e($mensagem) ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="recuperar_senha.php">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="identificador" class="form-label">E-mail, Matrícula ou CPF</label>
                <input 
                    type="text" 
                    id="identificador" 
                    name="identificador" 
                    class="form-control" 
                    placeholder="Digite seu e-mail ou número de matrícula" 
                    required 
                    autofocus
                >
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                    <span>Localizar Conta</span>
                </button>
            </div>

            <div style="margin-top: 1.25rem; text-align: center;">
                <a href="index.php" style="color: var(--slate-600); text-decoration: none; font-size: 0.88rem; font-weight: 500;">
                    &larr; Voltar para a Tela de Login
                </a>
            </div>
        </form>
    </div>
</body>
</html>
