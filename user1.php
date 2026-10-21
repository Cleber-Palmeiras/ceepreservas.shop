<?php
/**
 * CEEP Reservas - Agendamento de Recursos (2026)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a', 'p']);

$user = auth_user();
$isAdmin = auth_is_admin();
$matricula = $user['matricula'];

// Tratamento de ação POST (Salvar Reserva)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'reservar') {
    if (!csrf_verify()) {
        set_flash('error', 'Token de segurança inválido. Por favor, tente novamente.', 'Erro de Validação');
        header('Location: user1.php');
        exit();
    }

    $cod_recurso = (int)($_POST['cod_recurso'] ?? 0);
    $data_hora = trim($_POST['data_hora'] ?? ''); // Formato: Y-m-d H:i:s
    $turno = trim($_POST['turno'] ?? 'm');

    if ($cod_recurso <= 0 || empty($data_hora)) {
        set_flash('error', 'Por favor, selecione um recurso e um horário válido.', 'Dados Incompletos');
        header('Location: user1.php');
        exit();
    }

    // Valida se o recurso existe e está disponível
    $recurso = DB::fetchOne("SELECT * FROM recursos WHERE cod_recurso = ?", [$cod_recurso]);
    if (!$recurso || strtolower($recurso['status']) !== 'disponivel') {
        set_flash('error', 'Este recurso não está disponível para reservas.', 'Recurso Indisponível');
        header('Location: user1.php?cod_recurso=' . $cod_recurso);
        exit();
    }

    // Verifica se já existe agendamento neste horário para o mesmo recurso
    $existe = DB::fetchOne(
        "SELECT * FROM reservas WHERE cod_recurso = ? AND datatempo = ?",
        [$cod_recurso, $data_hora]
    );

    if ($existe) {
        set_flash('error', 'Ops! Este horário acabou de ser reservado por outro usuário.', 'Conflito de Horário');
    } else {
        try {
            DB::execute(
                "INSERT INTO reservas (cod_recurso, matricula, datatempo, turno, disponivel) VALUES (?, ?, ?, ?, 0)",
                [$cod_recurso, $matricula, $data_hora, $turno]
            );
            $dataFormatada = date('d/m/Y \à\s H:i', strtotime($data_hora));
            set_flash('success', "Reserva de '{$recurso['nome_recurso']}' para {$dataFormatada} confirmada com sucesso!", 'Reserva Efetuada');
        } catch (Throwable $t) {
            error_log('Erro ao reservar: ' . $t->getMessage());
            set_flash('error', 'Ocorreu um erro no servidor ao salvar sua reserva. Tente novamente.', 'Erro no Sistema');
        }
    }

    header('Location: user1.php?cod_recurso=' . $cod_recurso . '&semana=' . urlencode($_POST['semana_offset'] ?? 0) . '&turno=' . urlencode($turno));
    exit();
}

// Parâmetros de Filtro
$categoria = (int)($_GET['categoria'] ?? 0);
$selectedRecursoId = (int)($_GET['cod_recurso'] ?? 0);
$turnoSelecionado = $_GET['turno'] ?? 'm'; // 'm' = Manhã, 't' = Tarde, 'n' = Noite
$semanaOffset = (int)($_GET['semana'] ?? 0);

// Busca lista de categorias e recursos
try {
    $whereCat = $categoria > 0 ? "WHERE categoria = '{$categoria}'" : "";
    $todosRecursos = DB::fetchAll("SELECT * FROM recursos {$whereCat} ORDER BY categoria ASC, nome_recurso ASC");
} catch (Throwable $t) {
    $todosRecursos = [];
}

// Se nenhum recurso estiver explicitamente selecionado, seleciona o primeiro disponível
if ($selectedRecursoId <= 0 && !empty($todosRecursos)) {
    $selectedRecursoId = (int)$todosRecursos[0]['cod_recurso'];
}

$recursoAtual = null;
foreach ($todosRecursos as $r) {
    if ((int)$r['cod_recurso'] === $selectedRecursoId) {
        $recursoAtual = $r;
        break;
    }
}

// Cálculo da semana (Segunda a Sexta/Sábado)
$inicioSemanaTs = strtotime("monday this week +{$semanaOffset} weeks");
$diasSemana = [];
$nomesDias = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

for ($i = 0; $i < 6; $i++) {
    $diaTs = strtotime("+{$i} days", $inicioSemanaTs);
    $diasSemana[] = [
        'nome' => $nomesDias[$i],
        'data' => date('Y-m-d', $diaTs),
        'data_formatada' => date('d/m', $diaTs)
    ];
}

// Horários por turno
$gradeHorarios = [
    'm' => [
        ['label' => '1º Horário', 'hora' => '07:30:00', 'exibicao' => '07:30 - 08:20'],
        ['label' => '2º Horário', 'hora' => '08:20:00', 'exibicao' => '08:20 - 09:10'],
        ['label' => '3º Horário', 'hora' => '09:20:00', 'exibicao' => '09:20 - 10:10'],
        ['label' => '4º Horário', 'hora' => '10:10:00', 'exibicao' => '10:10 - 11:00'],
        ['label' => '5º Horário', 'hora' => '11:00:00', 'exibicao' => '11:00 - 11:50'],
    ],
    't' => [
        ['label' => '1º Horário', 'hora' => '13:10:00', 'exibicao' => '13:10 - 14:00'],
        ['label' => '2º Horário', 'hora' => '14:00:00', 'exibicao' => '14:00 - 14:50'],
        ['label' => '3º Horário', 'hora' => '15:00:00', 'exibicao' => '15:00 - 15:50'],
        ['label' => '4º Horário', 'hora' => '15:50:00', 'exibicao' => '15:50 - 16:40'],
        ['label' => '5º Horário', 'hora' => '16:40:00', 'exibicao' => '16:40 - 17:30'],
    ],
    'n' => [
        ['label' => '1º Horário', 'hora' => '18:30:00', 'exibicao' => '18:30 - 19:20'],
        ['label' => '2º Horário', 'hora' => '19:20:00', 'exibicao' => '19:20 - 20:10'],
        ['label' => '3º Horário', 'hora' => '20:20:00', 'exibicao' => '20:20 - 21:10'],
        ['label' => '4º Horário', 'hora' => '21:10:00', 'exibicao' => '21:10 - 22:00'],
    ]
];

$horariosAtuais = $gradeHorarios[$turnoSelecionado] ?? $gradeHorarios['m'];

// Busca todas as reservas da semana para o recurso selecionado
$reservasSemana = [];
if ($selectedRecursoId > 0) {
    $dataInicioStr = $diasSemana[0]['data'] . ' 00:00:00';
    $dataFimStr = $diasSemana[5]['data'] . ' 23:59:59';

    try {
        $resQuery = DB::fetchAll(
            "SELECT r.*, u.nome, u.sobrenome 
             FROM reservas r
             LEFT JOIN usuarios u ON r.matricula = u.matricula
             WHERE r.cod_recurso = ? AND r.datatempo BETWEEN ? AND ?",
            [$selectedRecursoId, $dataInicioStr, $dataFimStr]
        );

        foreach ($resQuery as $rq) {
            $reservasSemana[$rq['datatempo']] = $rq;
        }
    } catch (Throwable $t) {
        $reservasSemana = [];
    }
}

render_header('Efetuar Reserva', 'reservar');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Agendamento de Salas &amp; Recursos</h1>
        <p class="page-subtitle">Escolha o recurso, turno e selecione os horários disponíveis na grade semanal.</p>
    </div>
    <div class="page-actions">
        <a href="reservas.php" class="btn btn-secondary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <span>Ver Minhas Reservas</span>
        </a>
    </div>
</div>

<!-- Barra de Filtros e Seleção -->
<form method="GET" action="user1.php" class="schedule-filter-bar animate-slide-in">
    <div style="flex: 1; min-width: 220px;">
        <label for="categoria" class="form-label">Categoria</label>
        <select name="categoria" id="categoria" class="form-select" onchange="this.form.submit()">
            <option value="0">Todas as Categorias</option>
            <option value="1" <?= $categoria === 1 ? 'selected' : '' ?>>Datashow</option>
            <option value="2" <?= $categoria === 2 ? 'selected' : '' ?>>Laboratório</option>
            <option value="3" <?= $categoria === 3 ? 'selected' : '' ?>>Sala</option>
            <option value="4" <?= $categoria === 4 ? 'selected' : '' ?>>Outros</option>
        </select>
    </div>

    <div style="flex: 2; min-width: 260px;">
        <label for="cod_recurso" class="form-label">Recurso / Sala</label>
        <select name="cod_recurso" id="cod_recurso" class="form-select" onchange="this.form.submit()">
            <?php foreach ($todosRecursos as $rec): ?>
                <option value="<?= e($rec['cod_recurso']) ?>" <?= (int)$rec['cod_recurso'] === $selectedRecursoId ? 'selected' : '' ?>>
                    <?= e($rec['nome_recurso']) ?> (<?= e($rec['status']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="flex: 1; min-width: 180px;">
        <label for="turno" class="form-label">Turno</label>
        <select name="turno" id="turno" class="form-select" onchange="this.form.submit()">
            <option value="m" <?= $turnoSelecionado === 'm' ? 'selected' : '' ?>>Manhã (07:30 - 11:50)</option>
            <option value="t" <?= $turnoSelecionado === 't' ? 'selected' : '' ?>>Tarde (13:10 - 17:30)</option>
            <option value="n" <?= $turnoSelecionado === 'n' ? 'selected' : '' ?>>Noite (18:30 - 22:00)</option>
        </select>
    </div>

    <input type="hidden" name="semana" value="<?= e($semanaOffset) ?>">

    <div>
        <button type="submit" class="btn btn-primary" style="height: 42px;">Filtrar</button>
    </div>
</form>

<!-- Navegação de Semanas -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;" class="animate-slide-in">
    <div style="display: flex; align-items: center; gap: 0.5rem;">
        <a href="user1.php?cod_recurso=<?= $selectedRecursoId ?>&turno=<?= $turnoSelecionado ?>&semana=<?= $semanaOffset - 1 ?>" class="btn btn-secondary btn-sm">
            &larr; Semana Anterior
        </a>
        <?php if ($semanaOffset !== 0): ?>
            <a href="user1.php?cod_recurso=<?= $selectedRecursoId ?>&turno=<?= $turnoSelecionado ?>&semana=0" class="btn btn-secondary btn-sm">
                Semana Atual
            </a>
        <?php endif; ?>
        <a href="user1.php?cod_recurso=<?= $selectedRecursoId ?>&turno=<?= $turnoSelecionado ?>&semana=<?= $semanaOffset + 1 ?>" class="btn btn-secondary btn-sm">
            Próxima Semana &rarr;
        </a>
    </div>

    <div>
        <span style="font-weight: 700; color: var(--slate-700); font-size: 0.95rem;">
            <?= $diasSemana[0]['data_formatada'] ?> até <?= $diasSemana[5]['data_formatada'] ?>/<?= date('Y', $inicioSemanaTs) ?>
        </span>
        <?php if ($recursoAtual): ?>
            <span class="badge badge-info" style="margin-left: 0.5rem; font-size: 0.85rem;">
                <?= e($recursoAtual['nome_recurso']) ?>
            </span>
        <?php endif; ?>
    </div>
</div>

<!-- Grade Semanal de Horários -->
<div class="schedule-grid-container animate-slide-in">
    <div class="table-responsive">
        <table class="schedule-table">
            <thead>
                <tr>
                    <th class="time-col">Horário</th>
                    <?php foreach ($diasSemana as $dia): ?>
                        <th>
                            <div><?= e($dia['nome']) ?></div>
                            <small style="color: var(--slate-500); font-weight: 600;"><?= e($dia['data_formatada']) ?></small>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horariosAtuais as $slot): ?>
                    <tr>
                        <td class="time-col">
                            <strong><?= e($slot['label']) ?></strong><br>
                            <span style="font-size: 0.72rem; color: var(--slate-500); font-weight: normal;"><?= e($slot['exibicao']) ?></span>
                        </td>

                        <?php foreach ($diasSemana as $dia): 
                            $dtSlot = $dia['data'] . ' ' . $slot['hora'];
                            $isReservado = isset($reservasSemana[$dtSlot]);
                            $reservaInfo = $isReservado ? $reservasSemana[$dtSlot] : null;
                            $minha = $isReservado && ($reservaInfo['matricula'] == $matricula);
                            $passado = strtotime($dtSlot) < time();
                        ?>
                            <td>
                                <?php if ($minha): ?>
                                    <div class="slot-btn my-reservation" title="Reservado por você">
                                        <span style="font-size: 0.75rem; font-weight: 700;">Sua Reserva</span>
                                        <a 
                                            href="cancela.php?id=<?= urlencode($reservaInfo['ID']) ?>&csrf_token=<?= e(csrf_token()) ?>" 
                                            class="btn btn-danger btn-sm" 
                                            style="padding: 2px 6px; font-size: 0.68rem; margin-top: 3px;"
                                            data-confirm="Cancelar este horário reservado?"
                                        >
                                            Cancelar
                                        </a>
                                    </div>
                                <?php elseif ($isReservado): ?>
                                    <div class="slot-btn occupied" title="Horário já reservado">
                                        <span style="font-size: 0.72rem; font-weight: 700;">Ocupado</span>
                                        <span class="slot-occupant">
                                            <?= $isAdmin ? e(trim(($reservaInfo['nome'] ?? '') . ' ' . ($reservaInfo['sobrenome'] ?? ''))) : 'Reservado' ?>
                                        </span>
                                        <?php if ($isAdmin): ?>
                                            <a 
                                                href="cancela.php?id=<?= urlencode($reservaInfo['ID']) ?>&csrf_token=<?= e(csrf_token()) ?>" 
                                                class="btn btn-danger btn-sm" 
                                                style="padding: 2px 5px; font-size: 0.65rem; margin-top: 2px;"
                                                data-confirm="Cancelar reserva deste usuário como Administrador?"
                                            >
                                                Liberar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif ($passado): ?>
                                    <div class="slot-btn" style="background-color: var(--slate-50); opacity: 0.6; cursor: not-allowed;">
                                        <span style="font-size: 0.72rem; color: var(--slate-400);">Encerrado</span>
                                    </div>
                                <?php else: ?>
                                    <form method="POST" action="user1.php" style="margin: 0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="acao" value="reservar">
                                        <input type="hidden" name="cod_recurso" value="<?= e($selectedRecursoId) ?>">
                                        <input type="hidden" name="data_hora" value="<?= e($dtSlot) ?>">
                                        <input type="hidden" name="turno" value="<?= e($turnoSelecionado) ?>">
                                        <input type="hidden" name="semana_offset" value="<?= e($semanaOffset) ?>">

                                        <button 
                                            type="submit" 
                                            class="slot-btn" 
                                            title="Clique para reservar <?= e($slot['exibicao']) ?> em <?= e($dia['data_formatada']) ?>"
                                            data-confirm="Deseja confirmar a reserva de '<?= e($recursoAtual['nome_recurso'] ?? '') ?>' no dia <?= e($dia['data_formatada']) ?> às <?= e($slot['hora']) ?>?"
                                        >
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                            <span>Disponível</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
render_footer();
