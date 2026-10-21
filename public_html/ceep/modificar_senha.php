<?php
/**
 * CEEP Reservas - Modificação Segura de Senha
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a', 'p']);

$user = auth_user();
$userId = (int)$user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        set_flash('error', 'Token de segurança inválido.', 'Erro');
        header('Location: modificar_senha.php');
        exit();
    }

    $senhaAtual = $_POST['senha_atual'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmaSenha = $_POST['confirma_senha'] ?? '';

    if (empty($senhaAtual) || empty($novaSenha)) {
        set_flash('error', 'Por favor, preencha todos os campos de senha.', 'Campos Vazios');
        header('Location: modificar_senha.php');
        exit();
    }

    if ($novaSenha !== $confirmaSenha) {
        set_flash('error', 'A nova senha e a confirmação não conferem.', 'Senhas Divergentes');
        header('Location: modificar_senha.php');
        exit();
    }

    if (strlen($novaSenha) < 6) {
        set_flash('error', 'A nova senha deve possuir pelo menos 6 caracteres.', 'Senha Fraca');
        header('Location: modificar_senha.php');
        exit();
    }

    try {
        $u = DB::fetchOne("SELECT senha FROM usuarios WHERE usuario_id = ?", [$userId]);
        if (!$u || !verify_and_upgrade_password($senhaAtual, $u['senha'], $userId)) {
            set_flash('error', 'Sua senha atual está incorreta.', 'Autenticação Falhou');
            header('Location: modificar_senha.php');
            exit();
        }

        // Gera novo hash seguro
        $novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        DB::execute("UPDATE usuarios SET senha = ? WHERE usuario_id = ?", [$novoHash, $userId]);

        set_flash('success', 'Sua senha foi alterada com sucesso!', 'Senha Atualizada');
        header('Location: home.php');
        exit();
    } catch (Throwable $t) {
        error_log('Erro ao alterar senha: ' . $t->getMessage());
        set_flash('error', 'Ocorreu um erro no servidor ao salvar a nova senha.', 'Erro no Sistema');
    }
}

render_header('Alterar Senha', 'perfil');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Alteração de Senha</h1>
        <p class="page-subtitle">Proteja sua conta utilizando uma senha forte com letras e números.</p>
    </div>
    <div class="page-actions">
        <a href="editar_usuario.php" class="btn btn-secondary">&larr; Voltar para Meus Dados</a>
    </div>
</div>

<div class="card animate-slide-in" style="max-width: 540px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <span>Definir Nova Senha</span>
        </h2>
    </div>

    <form method="POST" action="modificar_senha.php">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="senha_atual" class="form-label">Senha Atual <span class="required">*</span></label>
            <div class="password-input-wrap">
                <input type="password" id="senha_atual" name="senha_atual" class="form-control" required autofocus>
                <button type="button" class="password-toggle-btn" aria-label="Mostrar ou ocultar senha">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="nova_senha" class="form-label">Nova Senha <span class="required">*</span></label>
            <div class="password-input-wrap">
                <input type="password" id="nova_senha" name="nova_senha" class="form-control" required minlength="6" placeholder="Mínimo de 6 caracteres">
                <button type="button" class="password-toggle-btn" aria-label="Mostrar ou ocultar senha">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="confirma_senha" class="form-label">Confirmar Nova Senha <span class="required">*</span></label>
            <div class="password-input-wrap">
                <input type="password" id="confirma_senha" name="confirma_senha" class="form-control" required minlength="6">
                <button type="button" class="password-toggle-btn" aria-label="Mostrar ou ocultar senha">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </div>
        </div>

        <div style="margin-top: 1.75rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <a href="home.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                Salvar Nova Senha
            </button>
        </div>
    </form>
</div>

<?php
render_footer();
