<?php
/**
 * CEEP Reservas - Gestão Completa de Reservas (Administrador)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Apenas Administrador

$recursoFiltro = (int)($_GET['cod_recurso'] ?? 0);
$dataFiltro = trim($_GET['data'] ?? '');

$params = [];
$whereClauses = [];

if ($recursoFiltro > 0) {
    $whereClauses[] = "r.cod_recurso = ?";
    $params[] = $recursoFiltro;
}

if (!empty($dataFiltro)) {
    $whereClauses[] = "DATE(r.datatempo) = ?";
    $params[] = $dataFiltro;
}

$whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

try {
    $reservas = DB::fetchAll(
        "SELECT r.*, rec.nome_recurso, rec.categoria, u.nome, u.sobrenome, u.email 
         FROM reservas r
         LEFT JOIN recursos rec ON r.cod_recurso = rec.cod_recurso
         LEFT JOIN usuarios u ON r.matricula = u.matricula
         {$whereSql}
         ORDER BY r.datatempo DESC LIMIT 150",
        $params
    );

    $listaRecursos = DB::fetchAll("SELECT * FROM recursos ORDER BY nome_recurso ASC");
} catch (Throwable $t) {
    error_log('Erro ao listar todas reservas: ' . $t->getMessage());
    $reservas = [];
    $listaRecursos = [];
}

render_header('Todas as Reservas', 'excluir_reservas');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Moderação de Reservas</h1>
        <p class="page-subtitle">Visualize, filtre e gerencie todos os agendamentos realizados no sistema.</p>
    </div>
    <div class="page-actions">
        <a href="user1.php" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Nova Reserva</span>
        </a>
    </div>
</div>

<!-- Filtros Administrativos -->
<form method="GET" action="excluir_reservas.php" class="schedule-filter-bar animate-slide-in">
    <div style="flex: 2; min-width: 240px;">
        <label for="cod_recurso" class="form-label">Filtrar por Recurso</label>
        <select name="cod_recurso" id="cod_recurso" class="form-select">
            <option value="0">Todos os Recursos</option>
            <?php foreach ($listaRecursos as $rec): ?>
                <option value="<?= e($rec['cod_recurso']) ?>" <?= $recursoFiltro === (int)$rec['cod_recurso'] ? 'selected' : '' ?>>
                    <?= e($rec['nome_recurso']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="flex: 1; min-width: 180px;">
        <label for="data" class="form-label">Filtrar por Data</label>
        <input type="date" name="data" id="data" class="form-control" value="<?= e($dataFiltro) ?>">
    </div>

    <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="height: 42px;">Filtrar</button>
        <?php if ($recursoFiltro > 0 || !empty($dataFiltro)): ?>
            <a href="excluir_reservas.php" class="btn btn-secondary" style="height: 42px; display: inline-flex; align-items: center;">Limpar</a>
        <?php endif; ?>
    </div>
</form>

<div class="card animate-slide-in">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <span>Reservas Encontradas (<?= count($reservas) ?>)</span>
        </h2>
    </div>

    <?php if (empty($reservas)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
            </div>
            <h3 class="empty-state-title">Nenhuma reserva encontrada para este filtro</h3>
            <p class="empty-state-desc">Altere ou remova os filtros de busca para visualizar outros agendamentos.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="ceep-table">
                <thead>
                    <tr>
                        <th>Data &amp; Hora</th>
                        <th>Recurso</th>
                        <th>Professor / Solicitante</th>
                        <th>Matrícula</th>
                        <th>Status</th>
                        <th style="text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $res): 
                        $passada = strtotime($res['datatempo']) < time();
                    ?>
                    <tr>
                        <td>
                            <strong><?= date('d/m/Y', strtotime($res['datatempo'])) ?></strong> às <?= date('H:i', strtotime($res['datatempo'])) ?>
                        </td>
                        <td>
                            <strong><?= e($res['nome_recurso'] ?? 'Recurso #' . $res['cod_recurso']) ?></strong>
                        </td>
                        <td>
                            <?= e(trim(($res['nome'] ?? '') . ' ' . ($res['sobrenome'] ?? ''))) ?: '<span style="color: var(--slate-400);">Desconhecido</span>' ?>
                        </td>
                        <td>
                            <span class="badge badge-secondary"><?= e($res['matricula']) ?></span>
                        </td>
                        <td>
                            <?php if ($passada): ?>
                                <span class="badge badge-secondary">Concluída</span>
                            <?php else: ?>
                                <span class="badge badge-success">Ativa</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <a 
                                href="cancela.php?id=<?= urlencode($res['ID']) ?>&csrf_token=<?= e(csrf_token()) ?>" 
                                class="btn btn-danger btn-sm"
                                data-confirm="Tem certeza que deseja cancelar esta reserva como Administrador?"
                            >
                                Excluir Reserva
                            </a>
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
