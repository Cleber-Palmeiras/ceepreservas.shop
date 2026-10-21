<?php
/**
 * CEEP Reservas - Gerenciamento de Recursos (Administrador)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

$busca = trim($_GET['busca'] ?? '');
$categoriaFiltro = (int)($_GET['categoria'] ?? 0);

$params = [];
$whereClauses = [];

if (!empty($busca)) {
    $whereClauses[] = "nome_recurso LIKE ?";
    $params[] = "%{$busca}%";
}

if ($categoriaFiltro > 0) {
    $whereClauses[] = "categoria = ?";
    $params[] = $categoriaFiltro;
}

$whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

try {
    $recursos = DB::fetchAll(
        "SELECT * FROM recursos {$whereSql} ORDER BY categoria ASC, nome_recurso ASC",
        $params
    );
} catch (Throwable $t) {
    error_log('Erro ao listar recursos: ' . $t->getMessage());
    $recursos = [];
}

$catNomes = [
    '1' => 'Datashow',
    '2' => 'Laboratório',
    '3' => 'Sala',
    '4' => 'Outros'
];

render_header('Gerenciar Recursos', 'gerencia_recurso');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Gerenciamento de Recursos &amp; Salas</h1>
        <p class="page-subtitle">Cadastre, edite status e monitore a disponibilidade de equipamentos pedagógicos.</p>
    </div>
    <div class="page-actions">
        <a href="recurso.php" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Novo Recurso</span>
        </a>
    </div>
</div>

<!-- Filtros e Busca -->
<form method="GET" action="gerencia_recurso.php" class="schedule-filter-bar animate-slide-in">
    <div style="flex: 2; min-width: 240px;">
        <label for="busca" class="form-label">Buscar por Nome</label>
        <input type="text" name="busca" id="busca" class="form-control" placeholder="Ex: Datashow, Laboratório, Sala..." value="<?= e($busca) ?>">
    </div>

    <div style="flex: 1; min-width: 200px;">
        <label for="categoria" class="form-label">Filtrar por Categoria</label>
        <select name="categoria" id="categoria" class="form-select">
            <option value="0">Todas as Categorias</option>
            <option value="1" <?= $categoriaFiltro === 1 ? 'selected' : '' ?>>Datashow</option>
            <option value="2" <?= $categoriaFiltro === 2 ? 'selected' : '' ?>>Laboratório</option>
            <option value="3" <?= $categoriaFiltro === 3 ? 'selected' : '' ?>>Sala</option>
            <option value="4" <?= $categoriaFiltro === 4 ? 'selected' : '' ?>>Outros</option>
        </select>
    </div>

    <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="height: 42px;">Filtrar</button>
        <?php if (!empty($busca) || $categoriaFiltro > 0): ?>
            <a href="gerencia_recurso.php" class="btn btn-secondary" style="height: 42px; display: inline-flex; align-items: center;">Limpar</a>
        <?php endif; ?>
    </div>
</form>

<div class="card animate-slide-in">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
            <span>Recursos Cadastrados (<?= count($recursos) ?>)</span>
        </h2>
    </div>

    <?php if (empty($recursos)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
            </div>
            <h3 class="empty-state-title">Nenhum recurso encontrado</h3>
            <p class="empty-state-desc">Cadastre um novo recurso pedagógico para disponibilizá-lo para reservas.</p>
            <a href="recurso.php" class="btn btn-primary">Cadastrar Recurso</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="ceep-table">
                <thead>
                    <tr>
                        <th>Nome do Recurso</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th style="text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recursos as $rec): 
                        $disp = strtolower($rec['status'] ?? '') === 'disponivel';
                    ?>
                    <tr>
                        <td><strong><?= e($rec['nome_recurso']) ?></strong></td>
                        <td><?= e($catNomes[$rec['categoria'] ?? ''] ?? 'Outros') ?></td>
                        <td>
                            <span class="badge <?= $disp ? 'badge-success' : 'badge-danger' ?>">
                                <?= $disp ? 'Disponível' : 'Indisponível' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.4rem;">
                                <a href="edit_recurso.php?id=<?= urlencode($rec['cod_recurso']) ?>" class="btn btn-secondary btn-sm" title="Editar">
                                    Editar
                                </a>
                                <a 
                                    href="delete_recurso.php?id=<?= urlencode($rec['cod_recurso']) ?>&csrf_token=<?= e(csrf_token()) ?>" 
                                    class="btn btn-danger btn-sm"
                                    data-confirm="Atenção: excluir este recurso também removerá todas as reservas associadas a ele. Deseja continuar?"
                                    title="Excluir"
                                >
                                    Excluir
                                </a>
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
