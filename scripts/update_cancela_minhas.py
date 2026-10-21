# -*- coding: utf-8 -*-
"""
Modernização de Minhas Reservas, Cancelamento e Moderação de Reservas
"""

# 1. reservas.php (Minhas Reservas)
reservas_code = """<?php
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
"""

with open('reservas.php', 'w', encoding='utf-8') as f:
    f.write(reservas_code)
print("Gerado: reservas.php")

with open('minhas_reservas.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/reservas.php'; ?>")
print("Gerado: minhas_reservas.php (wrapper)")

# 2. cancela.php / cancelar.php (Cancelamento Seguro)
cancela_code = """<?php
/**
 * CEEP Reservas - Cancelamento Seguro de Reserva
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a', 'p']);

$user = auth_user();
$matricula = $user['matricula'];
$isAdmin = auth_is_admin();

$id = (int)($_REQUEST['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Identificador de reserva inválido.', 'Erro');
    header('Location: home.php');
    exit();
}

try {
    // Busca a reserva para validar posse e detalhes
    $reserva = DB::fetchOne(
        "SELECT r.*, rec.nome_recurso 
         FROM reservas r
         LEFT JOIN recursos rec ON r.cod_recurso = rec.cod_recurso
         WHERE r.ID = ?",
        [$id]
    );

    if (!$reserva) {
        set_flash('error', 'Reserva não encontrada ou já cancelada.', 'Erro');
        header('Location: home.php');
        exit();
    }

    // Apenas o próprio dono da reserva ou um administrador pode cancelar
    if (!$isAdmin && $reserva['matricula'] != $matricula) {
        set_flash('error', 'Você não tem permissão para cancelar uma reserva de outro usuário.', 'Acesso Negado');
        header('Location: home.php');
        exit();
    }

    // Executa cancelamento com prepared statement seguro
    DB::execute("DELETE FROM reservas WHERE ID = ?", [$id]);

    $dataFormatada = date('d/m/Y \à\s H:i', strtotime($reserva['datatempo']));
    set_flash('success', "A reserva de '{$reserva['nome_recurso']}' para {$dataFormatada} foi cancelada com sucesso.", 'Reserva Cancelada');
} catch (Throwable $t) {
    error_log('Erro ao cancelar reserva: ' . $t->getMessage());
    set_flash('error', 'Ocorreu um erro no servidor ao cancelar a reserva.', 'Erro no Sistema');
}

// Redireciona de volta de forma limpa
$referer = $_SERVER['HTTP_REFERER'] ?? 'reservas.php';
header('Location: ' . $referer);
exit();
"""

with open('cancela.php', 'w', encoding='utf-8') as f:
    f.write(cancela_code)
print("Gerado: cancela.php")

with open('cancelar.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/cancela.php'; ?>")
print("Gerado: cancelar.php (wrapper)")

with open('cancelar_reservas.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/cancela.php'; ?>")
print("Gerado: cancelar_reservas.php (wrapper)")

# 3. apagar.php (Interface de cancelamento amigável)
apagar_code = """<?php
/**
 * CEEP Reservas - Cancelar Reservas (Redirecionamento para visão moderna de reservas)
 */
require_once __DIR__ . '/core/bootstrap.php';
header('Location: reservas.php');
exit();
"""

with open('apagar.php', 'w', encoding='utf-8') as f:
    f.write(apagar_code)
print("Gerado: apagar.php")

with open('apagar2.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/reservas.php'; ?>")
print("Gerado: apagar2.php")

# 4. excluir_reservas.php e todas_reservas.php (Moderação Administrativa)
todas_reservas_code = """<?php
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
"""

with open('excluir_reservas.php', 'w', encoding='utf-8') as f:
    f.write(todas_reservas_code)
print("Gerado: excluir_reservas.php")

with open('todas_reservas.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/excluir_reservas.php'; ?>")
print("Gerado: todas_reservas.php (wrapper)")
