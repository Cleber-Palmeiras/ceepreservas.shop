<?php
/**
 * CEEP Reservas - Edição de Dados Pessoais / Usuário
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a', 'p']);

$userLogado = auth_user();
$isAdmin = auth_is_admin();

// Se o admin passar ?id=XX ele edita o usuário solicitado; senão edita seu próprio perfil
$targetId = (int)($_GET['id'] ?? $userLogado['id']);
if (!$isAdmin && $targetId !== (int)$userLogado['id']) {
    $targetId = (int)$userLogado['id'];
}

$u = DB::fetchOne("SELECT * FROM usuarios WHERE usuario_id = ?", [$targetId]);
if (!$u) {
    set_flash('error', 'Usuário não encontrado.', 'Erro');
    header('Location: home.php');
    exit();
}

// Processamento POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        set_flash('error', 'Token de segurança inválido.', 'Erro');
        header('Location: editar_usuario.php?id=' . $targetId);
        exit();
    }

    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $matricula = preg_replace('/\D/', '', $_POST['matricula'] ?? '');
    $tipo = ($isAdmin && isset($_POST['tipo'])) ? trim($_POST['tipo']) : $u['tipo'];

    if (empty($nome) || empty($email) || empty($cpf) || empty($matricula)) {
        set_flash('error', 'Por favor, preencha todos os campos obrigatórios.', 'Campos Incompletos');
        header('Location: editar_usuario.php?id=' . $targetId);
        exit();
    }

    try {
        // Valida duplicidades ignorando o próprio usuário
        $dupEmail = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE LOWER(email) = LOWER(?) AND usuario_id != ?", [$email, $targetId]);
        if ($dupEmail) {
            set_flash('warning', 'Este e-mail já está em uso por outro usuário.', 'E-mail Duplicado');
            header('Location: editar_usuario.php?id=' . $targetId);
            exit();
        }

        $dupCpf = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE cpf = ? AND usuario_id != ?", [$cpf, $targetId]);
        if ($dupCpf) {
            set_flash('warning', 'Este CPF já está cadastrado para outro usuário.', 'CPF Duplicado');
            header('Location: editar_usuario.php?id=' . $targetId);
            exit();
        }

        $dupMat = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE matricula = ? AND usuario_id != ?", [$matricula, $targetId]);
        if ($dupMat) {
            set_flash('warning', 'Esta matrícula já pertence a outro usuário.', 'Matrícula Duplicada');
            header('Location: editar_usuario.php?id=' . $targetId);
            exit();
        }

        // Se a matrícula mudou, atualiza reservas existentes
        if ($matricula != $u['matricula']) {
            DB::execute("UPDATE reservas SET matricula = ? WHERE matricula = ?", [$matricula, $u['matricula']]);
        }

        DB::execute(
            "UPDATE usuarios SET nome = ?, sobrenome = ?, email = ?, cpf = ?, matricula = ?, tipo = ? WHERE usuario_id = ?",
            [$nome, $sobrenome, $email, $cpf, $matricula, $tipo, $targetId]
        );

        // Se atualizou o próprio perfil, reflete na sessão
        if ($targetId === (int)$userLogado['id']) {
            $_SESSION['nome'] = $nome;
            $_SESSION['sobrenome'] = $sobrenome;
            $_SESSION['email'] = $email;
            $_SESSION['cpf'] = $cpf;
            $_SESSION['matricula'] = $matricula;
            $_SESSION['tipo'] = $tipo;
        }

        set_flash('success', 'Dados atualizados com sucesso!', 'Perfil Atualizado');
        header('Location: ' . ($isAdmin && $targetId !== (int)$userLogado['id'] ? 'view-paginated.php' : 'editar_usuario.php'));
        exit();
    } catch (Throwable $t) {
        error_log('Erro ao atualizar usuário: ' . $t->getMessage());
        set_flash('error', 'Erro no servidor ao salvar as alterações.', 'Erro no Sistema');
    }
}

render_header('Dados Pessoais', 'perfil');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">
            <?= $targetId === (int)$userLogado['id'] ? 'Meus Dados Pessoais' : 'Editar Usuário: ' . e($u['nome']) ?>
        </h1>
        <p class="page-subtitle">Mantenha suas informações cadastrais atualizadas para comunicação e reservas.</p>
    </div>
    <div class="page-actions">
        <?php if ($isAdmin): ?>
        <a href="view-paginated.php" class="btn btn-secondary">&larr; Voltar para Lista</a>
        <?php endif; ?>
    </div>
</div>

<div class="card animate-slide-in" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Informações da Conta</span>
        </h2>
    </div>

    <form method="POST" action="editar_usuario.php?id=<?= e($targetId) ?>">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="nome" class="form-label">Nome <span class="required">*</span></label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= e($u['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label for="sobrenome" class="form-label">Sobrenome <span class="required">*</span></label>
                <input type="text" id="sobrenome" name="sobrenome" class="form-control" value="<?= e($u['sobrenome']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="matricula" class="form-label">Matrícula <span class="required">*</span></label>
                <input type="text" id="matricula" name="matricula" class="form-control" value="<?= e($u['matricula']) ?>" required>
            </div>
            <div class="form-group">
                <label for="cpf" class="form-label">CPF <span class="required">*</span></label>
                <input type="text" id="cpf" name="cpf" class="form-control" value="<?= e($u['cpf']) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">E-mail <span class="required">*</span></label>
            <input type="email" id="email" name="email" class="form-control" value="<?= e($u['email']) ?>" required>
        </div>

        <?php if ($isAdmin): ?>
        <div class="form-group">
            <label for="tipo" class="form-label">Perfil de Acesso</label>
            <select name="tipo" id="tipo" class="form-select">
                <option value="p" <?= $u['tipo'] === 'p' ? 'selected' : '' ?>>Professor</option>
                <option value="a" <?= $u['tipo'] === 'a' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <?php endif; ?>

        <div style="margin-top: 1.75rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <a href="modificar_senha.php" class="btn btn-secondary btn-sm">
                Alterar Minha Senha
            </a>
            <button type="submit" class="btn btn-primary">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<?php
render_footer();
