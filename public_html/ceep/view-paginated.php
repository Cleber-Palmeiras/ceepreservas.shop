<?php
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
