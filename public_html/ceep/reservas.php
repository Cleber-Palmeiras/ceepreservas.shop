<?php
/**
 * CEEP Reservas - Minhas Reservas (2026)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a', 'p']);

$user = auth_user();
$matricula = $user['matricula'];
$isAdmin = auth_is_admin();

// Filtro de período (Futuras ou Todas)
$filtro = $_GET['filtro'] ?? 'futuras';
$agora = date('Y-m-d H:i:s');

try {
    if ($filtro === 'historico') {
        $minhas = DB::fetchAll(
            "SELECT r.*, rec.nome_recurso, rec.categoria 
             FROM reservas r
             LEFT JOIN recursos rec ON r.cod_recurso = rec.cod_recurso
             WHERE r.matricula = ?
             ORDER BY r.datatempo DESC LIMIT 100",
            [$matricula]
        );
    } else {
        $minhas = DB::fetchAll(
            "SELECT r.*, rec.nome_recurso, rec.categoria 
             FROM reservas r
             LEFT JOIN recursos rec ON r.cod_recurso = rec.cod_recurso
             WHERE r.matricula = ? AND r.datatempo >= ?
             ORDER BY r.datatempo ASC",
            [$matricula, $agora]
        );
    }
} catch (Throwable $t) {
    error_log('Erro em minhas reservas: ' . $t->getMessage());
    $minhas = [];
}

render_header('Minhas Reservas', 'minhas_reservas');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Minhas Reservas</h1>
        <p class="page-subtitle">Acompanhe seus horários agendados e gerencie seus recursos pedagógicos.</p>
    </div>
    <div class="page-actions">
        <a href="user1.php" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Nova Reserva</span>
        </a>
    </div>
</div>

<div class="card animate-slide-in">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <span>Listagem de Agendamentos</span>
        </h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="reservas.php?filtro=futuras" class="btn <?= $filtro === 'futuras' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Próximas Reservas</a>
            <a href="reservas.php?filtro=historico" class="btn <?= $filtro === 'historico' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Histórico Completo</a>
        </div>
    </div>

    <?php if (empty($minhas)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
            </div>
            <h3 class="empty-state-title">Nenhuma reserva encontrada</h3>
            <p class="empty-state-desc">Você ainda não possui horários agendados para este período.</p>
            <a href="user1.php" class="btn btn-primary">Fazer um Agendamento</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="ceep-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Recurso / Sala</th>
                        <th>Turno</th>
                        <th>Status</th>
                        <th style="text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $turnoNomes = ['m' => 'Manhã', 't' => 'Tarde', 'n' => 'Noite'];
                    foreach ($minhas as $res): 
                        $passada = strtotime($res['datatempo']) < time();
                    ?>
                    <tr>
                        <td>
                            <strong><?= date('d/m/Y', strtotime($res['datatempo'])) ?></strong>
                        </td>
                        <td>
                            <span class="badge badge-secondary" style="font-size: 0.85rem;">
                                <?= date('H:i', strtotime($res['datatempo'])) ?>
                            </span>
                        </td>
                        <td>
                            <strong><?= e($res['nome_recurso'] ?? 'Recurso #' . $res['cod_recurso']) ?></strong>
                        </td>
                        <td>
                            <span class="badge badge-info">
                                <?= e($turnoNomes[$res['turno'] ?? ''] ?? 'Padrão') ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($passada): ?>
                                <span class="badge badge-secondary">Concluída</span>
                            <?php else: ?>
                                <span class="badge badge-success">Confirmada</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <?php if (!$passada || $isAdmin): ?>
                                <a 
                                    href="cancela.php?id=<?= urlencode($res['ID']) ?>&csrf_token=<?= e(csrf_token()) ?>" 
                                    class="btn btn-danger btn-sm"
                                    data-confirm="Tem certeza que deseja cancelar esta reserva para <?= date('d/m/Y \à\s H:i', strtotime($res['datatempo'])) ?>?"
                                >
                                    Cancelar
                                </a>
                            <?php else: ?>
                                <span style="font-size: 0.8rem; color: var(--slate-400);">Finalizada</span>
                            <?php endif; ?>
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
