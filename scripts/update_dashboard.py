# -*- coding: utf-8 -*-
"""
Modernização do Dashboard Principal (home.php) e módulos de visualização rápida
"""

dashboard_code = """<?php
/**
 * CEEP Reservas - Painel Principal (Dashboard 2026)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a', 'p']);

$user = auth_user();
$isAdmin = auth_is_admin();
$matricula = $user['matricula'];
$hoje = date('Y-m-d');
$agora = date('Y-m-d H:i:s');

// Saudação baseada na hora
$hora = (int)date('H');
if ($hora >= 5 && $hora < 12) {
    $saudacao = 'Bom dia';
} elseif ($hora >= 12 && $hora < 18) {
    $saudacao = 'Boa tarde';
} else {
    $saudacao = 'Boa noite';
}

// 1. Estatísticas
try {
    $totalRecursos = (int)DB::fetchOne("SELECT COUNT(*) as c FROM recursos")['c'];
    $recursosDisponiveis = (int)DB::fetchOne("SELECT COUNT(*) as c FROM recursos WHERE status = 'Disponivel'")['c'];
} catch (Throwable $t) {
    $totalRecursos = 0;
    $recursosDisponiveis = 0;
}

try {
    $reservasHojeTotal = (int)DB::fetchOne(
        "SELECT COUNT(*) as c FROM reservas WHERE DATE(datatempo) = ?",
        [$hoje]
    )['c'];

    $minhasReservasCount = (int)DB::fetchOne(
        "SELECT COUNT(*) as c FROM reservas WHERE matricula = ? AND datatempo >= ?",
        [$matricula, $agora]
    )['c'];
} catch (Throwable $t) {
    $reservasHojeTotal = 0;
    $minhasReservasCount = 0;
}

// 2. Consulta de Reservas de Hoje
try {
    if ($isAdmin) {
        $reservasHoje = DB::fetchAll(
            "SELECT r.*, rec.nome_recurso, rec.categoria, u.nome, u.sobrenome 
             FROM reservas r
             LEFT JOIN recursos rec ON r.cod_recurso = rec.cod_recurso
             LEFT JOIN usuarios u ON r.matricula = u.matricula
             WHERE DATE(r.datatempo) = ?
             ORDER BY r.datatempo ASC",
            [$hoje]
        );
    } else {
        $reservasHoje = DB::fetchAll(
            "SELECT r.*, rec.nome_recurso, rec.categoria 
             FROM reservas r
             LEFT JOIN recursos rec ON r.cod_recurso = rec.cod_recurso
             WHERE r.matricula = ? AND DATE(r.datatempo) = ?
             ORDER BY r.datatempo ASC",
            [$matricula, $hoje]
        );
    }
} catch (Throwable $t) {
    $reservasHoje = [];
}

// 3. Consulta de Status dos Recursos
try {
    $recursosList = DB::fetchAll("SELECT * FROM recursos ORDER BY nome_recurso ASC LIMIT 12");
} catch (Throwable $t) {
    $recursosList = [];
}

render_header('Painel Principal', 'home');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title"><?= e($saudacao) ?>, <?= e($user['nome']) ?>!</h1>
        <p class="page-subtitle">
            <?php if (!empty($user['data_ultimo_login']) && $user['data_ultimo_login'] !== '0000-00-00 00:00:00'): ?>
                Último acesso em <?= date('d/m/Y \à\s H:i', strtotime($user['data_ultimo_login'])) ?>
            <?php else: ?>
                Seja bem-vindo ao seu primeiro acesso ao CEEP Reservas!
            <?php endif; ?>
        </p>
    </div>
    <div class="page-actions">
        <a href="user1.php" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Fazer Reserva</span>
        </a>
        <a href="reservas.php" class="btn btn-secondary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <span>Minhas Reservas</span>
        </a>
    </div>
</div>

<!-- Grid de Estatísticas Rápidas -->
<div class="stats-grid animate-slide-in">
    <div class="stat-card">
        <div class="stat-icon bg-blue">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Reservas Hoje</span>
            <span class="stat-value"><?= $reservasHojeTotal ?></span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-emerald">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Recursos Disponíveis</span>
            <span class="stat-value"><?= $recursosDisponiveis ?> <small style="font-size: 0.95rem; font-weight: 500; color: var(--slate-400);">/ <?= $totalRecursos ?></small></span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-indigo">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Meus Próximos Agendamentos</span>
            <span class="stat-value"><?= $minhasReservasCount ?></span>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(460px, 1fr)); gap: 1.5rem;">
    <!-- Card Reservas de Hoje -->
    <div class="card animate-slide-in">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                <span>Reservas para Hoje (<?= date('d/m/Y') ?>)</span>
            </h2>
            <span class="badge badge-info"><?= count($reservasHoje) ?> agendamento(s)</span>
        </div>

        <?php if (empty($reservasHoje)): ?>
            <div class="empty-state" style="padding: 2.5rem 1rem;">
                <div class="empty-state-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <h3 class="empty-state-title">Nenhuma reserva agendada para hoje</h3>
                <p class="empty-state-desc">Os recursos estão livres para agendamento. Clique abaixo para reservar uma sala ou equipamento.</p>
                <a href="user1.php" class="btn btn-primary btn-sm">Agendar Horário</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="ceep-table">
                    <thead>
                        <tr>
                            <th>Horário</th>
                            <th>Recurso</th>
                            <?php if ($isAdmin): ?><th>Professor</th><?php endif; ?>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservasHoje as $res): ?>
                        <tr>
                            <td>
                                <strong><?= date('H:i', strtotime($res['datatempo'])) ?></strong>
                            </td>
                            <td>
                                <strong><?= e($res['nome_recurso'] ?? 'Recurso #' . $res['cod_recurso']) ?></strong>
                            </td>
                            <?php if ($isAdmin): ?>
                            <td>
                                <?= e(trim(($res['nome'] ?? '') . ' ' . ($res['sobrenome'] ?? ''))) ?: 'Matrícula ' . e($res['matricula']) ?>
                            </td>
                            <?php endif; ?>
                            <td>
                                <?php if ($isAdmin || $res['matricula'] == $matricula): ?>
                                <a 
                                    href="cancela.php?id=<?= urlencode($res['ID']) ?>&csrf_token=<?= e(csrf_token()) ?>" 
                                    class="btn btn-danger btn-sm"
                                    data-confirm="Deseja realmente cancelar este horário reservado?"
                                    title="Cancelar Reserva"
                                >
                                    Cancelar
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Card Status dos Recursos -->
    <div class="card animate-slide-in">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                <span>Recursos e Salas</span>
            </h2>
            <?php if ($isAdmin): ?>
            <a href="gerencia_recurso.php" class="btn btn-secondary btn-sm">Gerenciar</a>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="ceep-table">
                <thead>
                    <tr>
                        <th>Nome do Recurso</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Reserva</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $catNomes = [
                        '1' => 'Datashow',
                        '2' => 'Laboratório',
                        '3' => 'Sala',
                        '4' => 'Outros'
                    ];
                    foreach ($recursosList as $rec): 
                        $disp = strtolower($rec['status'] ?? '') === 'disponivel';
                    ?>
                    <tr>
                        <td><strong><?= e($rec['nome_recurso']) ?></strong></td>
                        <td><?= e($catNomes[$rec['categoria'] ?? ''] ?? 'Recurso') ?></td>
                        <td>
                            <span class="badge <?= $disp ? 'badge-success' : 'badge-danger' ?>">
                                <?= $disp ? 'Disponível' : 'Indisponível' ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($disp): ?>
                            <a href="user1.php?cod_recurso=<?= urlencode($rec['cod_recurso']) ?>" class="btn btn-primary btn-sm">
                                Reservar
                            </a>
                            <?php else: ?>
                            <span style="font-size: 0.8rem; color: var(--slate-400);">Indisponível</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
render_footer();
"""

with open('home.php', 'w', encoding='utf-8') as f:
    f.write(dashboard_code)
print("Gerado: home.php")
