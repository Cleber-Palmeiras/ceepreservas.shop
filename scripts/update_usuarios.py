# -*- coding: utf-8 -*-
"""
Modernização do Módulo de Usuários e Segurança de Contas
"""

# 1. view-paginated.php (Gerenciamento de Usuários - Admin)
view_usuarios_code = """<?php
/**
 * CEEP Reservas - Gerenciamento de Usuários (Administrador)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

$busca = trim($_GET['busca'] ?? '');
$tipoFiltro = trim($_GET['tipo'] ?? '');

$params = [];
$whereClauses = [];

if (!empty($busca)) {
    $whereClauses[] = "(nome LIKE ? OR sobrenome LIKE ? OR email LIKE ? OR matricula LIKE ? OR cpf LIKE ?)";
    $like = "%{$busca}%";
    $params = array_merge($params, [$like, $like, $like, $like, $like]);
}

if (!empty($tipoFiltro)) {
    $whereClauses[] = "tipo = ?";
    $params[] = $tipoFiltro;
}

$whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

try {
    $usuarios = DB::fetchAll(
        "SELECT * FROM usuarios {$whereSql} ORDER BY nome ASC",
        $params
    );
} catch (Throwable $t) {
    error_log('Erro ao listar usuários: ' . $t->getMessage());
    $usuarios = [];
}

render_header('Gerenciar Usuários', 'gerencia_usuario');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Gerenciamento de Usuários</h1>
        <p class="page-subtitle">Controle de professores, coordenadores e administradores do CEEP Reservas.</p>
    </div>
    <div class="page-actions">
        <a href="formulario_cadastro.php" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Novo Usuário</span>
        </a>
    </div>
</div>

<!-- Filtros de Usuários -->
<form method="GET" action="view-paginated.php" class="schedule-filter-bar animate-slide-in">
    <div style="flex: 2; min-width: 240px;">
        <label for="busca" class="form-label">Buscar Usuário</label>
        <input type="text" name="busca" id="busca" class="form-control" placeholder="Nome, matrícula, CPF ou e-mail..." value="<?= e($busca) ?>">
    </div>

    <div style="flex: 1; min-width: 180px;">
        <label for="tipo" class="form-label">Perfil de Acesso</label>
        <select name="tipo" id="tipo" class="form-select">
            <option value="">Todos os Perfis</option>
            <option value="p" <?= $tipoFiltro === 'p' ? 'selected' : '' ?>>Professor</option>
            <option value="a" <?= $tipoFiltro === 'a' ? 'selected' : '' ?>>Administrador</option>
        </select>
    </div>

    <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="height: 42px;">Filtrar</button>
        <?php if (!empty($busca) || !empty($tipoFiltro)): ?>
            <a href="view-paginated.php" class="btn btn-secondary" style="height: 42px; display: inline-flex; align-items: center;">Limpar</a>
        <?php endif; ?>
    </div>
</form>

<div class="card animate-slide-in">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            <span>Usuários Cadastrados (<?= count($usuarios) ?>)</span>
        </h2>
    </div>

    <?php if (empty($usuarios)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            </div>
            <h3 class="empty-state-title">Nenhum usuário encontrado</h3>
            <p class="empty-state-desc">Tente alterar os termos da pesquisa ou cadastre um novo usuário no sistema.</p>
            <a href="formulario_cadastro.php" class="btn btn-primary">Adicionar Usuário</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="ceep-table">
                <thead>
                    <tr>
                        <th>Nome Completo</th>
                        <th>Matrícula / CPF</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Último Acesso</th>
                        <th style="text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): 
                        $isAdminUser = ($u['tipo'] === 'a');
                    ?>
                    <tr>
                        <td>
                            <strong><?= e(trim($u['nome'] . ' ' . ($u['sobrenome'] ?? ''))) ?></strong>
                        </td>
                        <td>
                            <div><strong>Matrícula:</strong> <?= e($u['matricula']) ?></div>
                            <small style="color: var(--slate-500);">CPF: <?= e($u['cpf']) ?></small>
                        </td>
                        <td>
                            <a href="mailto:<?= e($u['email']) ?>" style="color: var(--primary); text-decoration: none;">
                                <?= e($u['email']) ?>
                            </a>
                        </td>
                        <td>
                            <span class="user-role-badge <?= $isAdminUser ? 'badge-admin' : 'badge-prof' ?>">
                                <?= $isAdminUser ? 'Administrador' : 'Professor' ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($u['data_ultimo_login']) && $u['data_ultimo_login'] !== '0000-00-00 00:00:00'): ?>
                                <?= date('d/m/Y H:i', strtotime($u['data_ultimo_login'])) ?>
                            <?php else: ?>
                                <span style="color: var(--slate-400);">Nunca acessou</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.4rem;">
                                <a href="editar_usuario.php?id=<?= urlencode($u['usuario_id']) ?>" class="btn btn-secondary btn-sm">
                                    Editar
                                </a>
                                <?php if ($u['usuario_id'] != $_SESSION['usuario_id']): ?>
                                <a 
                                    href="delete.php?id=<?= urlencode($u['usuario_id']) ?>&csrf_token=<?= e(csrf_token()) ?>" 
                                    class="btn btn-danger btn-sm"
                                    data-confirm="Atenção: excluir este usuário apagará todas as suas reservas cadastradas. Deseja continuar?"
                                >
                                    Excluir
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
render_footer();
"""

with open('view-paginated.php', 'w', encoding='utf-8') as f:
    f.write(view_usuarios_code)
print("Gerado: view-paginated.php")

with open('viewusuarios.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/view-paginated.php'; ?>")
print("Gerado: viewusuarios.php (wrapper)")

# 2. formulario_cadastro.php e cadastrar.php (Cadastro de Usuário)
form_cadastro_code = """<?php
/**
 * CEEP Reservas - Cadastro de Novo Usuário (Administrador)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

render_header('Novo Usuário', 'add_usuario');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Cadastrar Novo Usuário</h1>
        <p class="page-subtitle">Crie uma nova conta de Professor ou Administrador no sistema.</p>
    </div>
    <div class="page-actions">
        <a href="view-paginated.php" class="btn btn-secondary">
            &larr; Voltar para Usuários
        </a>
    </div>
</div>

<div class="card animate-slide-in" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            <span>Dados Cadastrais</span>
        </h2>
    </div>

    <form action="cadastrar.php" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="nome" class="form-label">Nome <span class="required">*</span></label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Primeiro nome" required autofocus>
            </div>
            <div class="form-group">
                <label for="sobrenome" class="form-label">Sobrenome <span class="required">*</span></label>
                <input type="text" id="sobrenome" name="sobrenome" class="form-control" placeholder="Sobrenome completo" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="matricula" class="form-label">Matrícula <span class="required">*</span></label>
                <input type="number" id="matricula" name="matricula" class="form-control" placeholder="Número de matrícula" required>
            </div>
            <div class="form-group">
                <label for="cpf" class="form-label">CPF <span class="required">*</span></label>
                <input type="text" id="cpf" name="cpf" class="form-control" placeholder="Apenas números ou formatado" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">E-mail Institucional ou Pessoal <span class="required">*</span></label>
            <input type="email" id="email" name="email" class="form-control" placeholder="usuario@escola.ce.gov.br" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="tipo" class="form-label">Tipo de Acesso <span class="required">*</span></label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="p">Professor (Realizar e gerenciar próprias reservas)</option>
                    <option value="a">Administrador (Controle total de recursos e usuários)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="senha" class="form-label">Senha Inicial <span class="required">*</span></label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" required minlength="6">
            </div>
        </div>

        <div style="margin-top: 1.75rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <a href="view-paginated.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                Salvar Cadastro
            </button>
        </div>
    </form>
</div>

<?php
render_footer();
"""

with open('formulario_cadastro.php', 'w', encoding='utf-8') as f:
    f.write(form_cadastro_code)
print("Gerado: formulario_cadastro.php")

# cadastrar.php (Processador com Prepared Statements e Bcrypt)
cadastrar_code = """<?php
/**
 * CEEP Reservas - Processador Seguro de Cadastro de Usuário
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: view-paginated.php');
    exit();
}

if (!csrf_verify()) {
    set_flash('error', 'Token de segurança inválido.', 'Erro');
    header('Location: formulario_cadastro.php');
    exit();
}

$nome = trim($_POST['nome'] ?? '');
$sobrenome = trim($_POST['sobrenome'] ?? '');
$matricula = preg_replace('/\\D/', '', $_POST['matricula'] ?? '');
$cpf = preg_replace('/\\D/', '', $_POST['cpf'] ?? '');
$email = trim($_POST['email'] ?? '');
$tipo = trim($_POST['tipo'] ?? 'p');
$senha = $_POST['senha'] ?? '123456';

if (empty($nome) || empty($matricula) || empty($cpf) || empty($email)) {
    set_flash('error', 'Por favor, preencha todos os campos obrigatórios.', 'Campos Incompletos');
    header('Location: formulario_cadastro.php');
    exit();
}

try {
    // 1. Verifica duplicidade de Matrícula
    $dupMat = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE matricula = ?", [$matricula]);
    if ($dupMat) {
        set_flash('warning', "A matrícula '{$matricula}' já está cadastrada para outro usuário.", 'Matrícula Duplicada');
        header('Location: formulario_cadastro.php');
        exit();
    }

    // 2. Verifica duplicidade de CPF
    $dupCpf = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE cpf = ?", [$cpf]);
    if ($dupCpf) {
        set_flash('warning', "O CPF informado já está cadastrado no sistema.", 'CPF Duplicado');
        header('Location: formulario_cadastro.php');
        exit();
    }

    // 3. Verifica duplicidade de E-mail
    $dupEmail = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE LOWER(email) = LOWER(?)", [$email]);
    if ($dupEmail) {
        set_flash('warning', "O e-mail informado já está em uso por outra conta.", 'E-mail Duplicado');
        header('Location: formulario_cadastro.php');
        exit();
    }

    // Gera hash moderno e seguro
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $agora = date('Y-m-d H:i:s');

    DB::execute(
        "INSERT INTO usuarios (nome, sobrenome, cpf, matricula, email, tipo, senha, nivel_usuario, data_cadastro, data_ultimo_login, ativado)
         VALUES (?, ?, ?, ?, ?, ?, ?, '0', ?, '0000-00-00 00:00:00', '1')",
        [$nome, $sobrenome, $cpf, $matricula, $email, $tipo, $senhaHash, $agora]
    );

    set_flash('success', "Usuário '{$nome} {$sobrenome}' cadastrado com sucesso!", 'Cadastro Realizado');
    header('Location: view-paginated.php');
    exit();
} catch (Throwable $t) {
    error_log('Erro ao cadastrar usuário: ' . $t->getMessage());
    set_flash('error', 'Erro ao salvar o usuário no banco de dados: ' . $t->getMessage(), 'Erro no Sistema');
    header('Location: formulario_cadastro.php');
    exit();
}
"""

with open('cadastrar.php', 'w', encoding='utf-8') as f:
    f.write(cadastrar_code)
print("Gerado: cadastrar.php")

# 3. editar_usuario.php (Edição de Perfil próprio ou pelo Admin)
editar_usuario_code = """<?php
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
    $cpf = preg_replace('/\\D/', '', $_POST['cpf'] ?? '');
    $matricula = preg_replace('/\\D/', '', $_POST['matricula'] ?? '');
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
"""

with open('editar_usuario.php', 'w', encoding='utf-8') as f:
    f.write(editar_usuario_code)
print("Gerado: editar_usuario.php")

# 4. modificar_senha.php (Alteração Segura de Senha)
modificar_senha_code = """<?php
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
"""

with open('modificar_senha.php', 'w', encoding='utf-8') as f:
    f.write(modificar_senha_code)
print("Gerado: modificar_senha.php")

# 5. delete.php e deletar.php (Exclusão Segura de Usuário pelo Admin)
delete_user_code = """<?php
/**
 * CEEP Reservas - Exclusão Segura de Usuário
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

$id = (int)($_REQUEST['id'] ?? 0);

if ($id <= 0 || $id === (int)$_SESSION['usuario_id']) {
    set_flash('error', 'Operação inválida. Não é permitido excluir a própria conta logada.', 'Erro');
    header('Location: view-paginated.php');
    exit();
}

try {
    $u = DB::fetchOne("SELECT matricula, nome, sobrenome FROM usuarios WHERE usuario_id = ?", [$id]);
    if ($u) {
        // Remove reservas do usuário
        DB::execute("DELETE FROM reservas WHERE matricula = ?", [$u['matricula']]);
        // Remove usuário
        DB::execute("DELETE FROM usuarios WHERE usuario_id = ?", [$id]);

        set_flash('success', "Usuário '{$u['nome']} {$u['sobrenome']}' e suas reservas foram removidos com sucesso.", 'Usuário Excluído');
    } else {
        set_flash('error', 'Usuário não encontrado.', 'Erro');
    }
} catch (Throwable $t) {
    error_log('Erro ao excluir usuário: ' . $t->getMessage());
    set_flash('error', 'Erro no servidor ao tentar excluir o usuário.', 'Erro');
}

header('Location: view-paginated.php');
exit();
"""

with open('delete.php', 'w', encoding='utf-8') as f:
    f.write(delete_user_code)
print("Gerado: delete.php")

with open('deletar.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/delete.php'; ?>")
print("Gerado: deletar.php (wrapper)")

# 6. recuperar_senha.php (Recuperação de Senha Limpa e Responsiva)
recuperar_senha_code = """<?php
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
    <link rel="stylesheet" href="core/app.css?v=2026.1">
</head>
<body class="login-body">
    <div class="login-card animate-slide-in">
        <div class="login-brand">
            <div class="login-icon-box">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
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
"""

with open('recuperar_senha.php', 'w', encoding='utf-8') as f:
    f.write(recuperar_senha_code)
print("Gerado: recuperar_senha.php")

with open('formulario_senha_perdida.html', 'w', encoding='utf-8') as f:
    f.write("<meta http-equiv='refresh' content='0; url=recuperar_senha.php'>")
print("Gerado: formulario_senha_perdida.html (redirecionador)")
