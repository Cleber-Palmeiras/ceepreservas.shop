<?php
/**
 * CEEP Reservas - Editar Recurso (Administrador)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']);

$id = (int)($_REQUEST['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Identificador de recurso inválido.', 'Erro');
    header('Location: gerencia_recurso.php');
    exit();
}

$recurso = DB::fetchOne("SELECT * FROM recursos WHERE cod_recurso = ?", [$id]);
if (!$recurso) {
    set_flash('error', 'Recurso não encontrado.', 'Erro');
    header('Location: gerencia_recurso.php');
    exit();
}

// Processamento POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        set_flash('error', 'Token de segurança inválido.', 'Erro');
        header('Location: edit_recurso.php?id=' . $id);
        exit();
    }

    $nome = trim($_POST['nome'] ?? '');
    $status = trim($_POST['status'] ?? 'Disponivel');
    $categoria = trim($_POST['categoria'] ?? '1');

    if (empty($nome)) {
        set_flash('error', 'O nome do recurso não pode ficar vazio.', 'Erro');
        header('Location: edit_recurso.php?id=' . $id);
        exit();
    }

    try {
        // Verifica se outro recurso já tem o mesmo nome
        $existe = DB::fetchOne(
            "SELECT cod_recurso FROM recursos WHERE LOWER(nome_recurso) = LOWER(?) AND cod_recurso != ?",
            [$nome, $id]
        );

        if ($existe) {
            set_flash('warning', "Outro recurso já utiliza o nome '{$nome}'.", 'Nome Duplicado');
            header('Location: edit_recurso.php?id=' . $id);
            exit();
        }

        DB::execute(
            "UPDATE recursos SET nome_recurso = ?, status = ?, categoria = ? WHERE cod_recurso = ?",
            [$nome, $status, $categoria, $id]
        );

        set_flash('success', "Recurso '{$nome}' atualizado com sucesso!", 'Recurso Atualizado');
        header('Location: gerencia_recurso.php');
        exit();
    } catch (Throwable $t) {
        error_log('Erro ao atualizar recurso: ' . $t->getMessage());
        set_flash('error', 'Erro ao salvar alterações no recurso.', 'Erro no Sistema');
    }
}

render_header('Editar Recurso', 'gerencia_recurso');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Editar Recurso</h1>
        <p class="page-subtitle">Modifique o nome, status ou categoria de '<?= e($recurso['nome_recurso']) ?>'.</p>
    </div>
    <div class="page-actions">
        <a href="gerencia_recurso.php" class="btn btn-secondary">
            &larr; Voltar para Recursos
        </a>
    </div>
</div>

<div class="card animate-slide-in" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            <span>Editar Dados</span>
        </h2>
    </div>

    <form method="POST" action="edit_recurso.php?id=<?= e($id) ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="nome" class="form-label">Nome do Recurso <span class="required">*</span></label>
            <input 
                type="text" 
                id="nome" 
                name="nome" 
                class="form-control" 
                value="<?= e($recurso['nome_recurso']) ?>" 
                required
            >
        </div>

        <div class="form-group">
            <label for="categoria" class="form-label">Categoria <span class="required">*</span></label>
            <select name="categoria" id="categoria" class="form-select" required>
                <option value="1" <?= $recurso['categoria'] == '1' ? 'selected' : '' ?>>Datashow</option>
                <option value="2" <?= $recurso['categoria'] == '2' ? 'selected' : '' ?>>Laboratório</option>
                <option value="3" <?= $recurso['categoria'] == '3' ? 'selected' : '' ?>>Sala</option>
                <option value="4" <?= $recurso['categoria'] == '4' ? 'selected' : '' ?>>Outros</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status" class="form-label">Status de Disponibilidade <span class="required">*</span></label>
            <select name="status" id="status" class="form-select" required>
                <option value="Disponivel" <?= strtolower($recurso['status']) === 'disponivel' ? 'selected' : '' ?>>Disponível para Reservas</option>
                <option value="Indisponivel" <?= strtolower($recurso['status']) === 'indisponivel' ? 'selected' : '' ?>>Indisponível (Manutenção/Bloqueado)</option>
            </select>
        </div>

        <div style="margin-top: 1.75rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <a href="gerencia_recurso.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<?php
render_footer();
