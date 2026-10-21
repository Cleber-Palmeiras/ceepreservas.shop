# -*- coding: utf-8 -*-
"""
Modernização do Módulo de Recursos (CRUD completo)
"""

# 1. gerencia_recurso.php
gerencia_recurso_code = """<?php
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
"""

with open('gerencia_recurso.php', 'w', encoding='utf-8') as f:
    f.write(gerencia_recurso_code)
print("Gerado: gerencia_recurso.php")

# 2. recurso.php (Cadastro de Novo Recurso)
recurso_code = """<?php
/**
 * CEEP Reservas - Cadastro de Recurso (Administrador)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

render_header('Novo Recurso', 'add_recurso');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Cadastrar Novo Recurso</h1>
        <p class="page-subtitle">Adicione salas, laboratórios, datashows e outros equipamentos ao catálogo.</p>
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
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Informações do Recurso</span>
        </h2>
    </div>

    <form action="cadastrar_recurso.php" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="nome" class="form-label">Nome do Recurso <span class="required">*</span></label>
            <input 
                type="text" 
                id="nome" 
                name="nome" 
                class="form-control" 
                placeholder="Ex: Datashow 05, Laboratório de Informática 2, Anfiteatro..." 
                required 
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="tipo" class="form-label">Categoria do Recurso <span class="required">*</span></label>
            <select name="tipo" id="tipo" class="form-select" required>
                <option value="1">Datashow</option>
                <option value="2">Laboratório</option>
                <option value="3">Sala</option>
                <option value="4">Outros</option>
            </select>
        </div>

        <div style="margin-top: 1.75rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <a href="gerencia_recurso.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                Salvar Recurso
            </button>
        </div>
    </form>
</div>

<?php
render_footer();
"""

with open('recurso.php', 'w', encoding='utf-8') as f:
    f.write(recurso_code)
print("Gerado: recurso.php")

# 3. cadastrar_recurso.php (Processador de cadastro de recurso)
cadastrar_recurso_code = """<?php
/**
 * CEEP Reservas - Processador de Cadastro de Recurso
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gerencia_recurso.php');
    exit();
}

if (!csrf_verify()) {
    set_flash('error', 'Token de formulário expirado. Tente novamente.', 'Erro de Validação');
    header('Location: recurso.php');
    exit();
}

$nome = trim($_POST['nome'] ?? '');
$categoria = trim($_POST['tipo'] ?? '1');

if (empty($nome)) {
    set_flash('error', 'O nome do recurso é obrigatório.', 'Campo Obrigatório');
    header('Location: recurso.php');
    exit();
}

try {
    // Verifica duplicidade de nome
    $existe = DB::fetchOne("SELECT cod_recurso FROM recursos WHERE LOWER(nome_recurso) = LOWER(?)", [$nome]);
    if ($existe) {
        set_flash('warning', "Já existe um recurso cadastrado com o nome '{$nome}'.", 'Recurso Existente');
        header('Location: recurso.php');
        exit();
    }

    DB::execute(
        "INSERT INTO recursos (nome_recurso, status, categoria) VALUES (?, 'Disponivel', ?)",
        [$nome, $categoria]
    );

    set_flash('success', "Recurso '{$nome}' cadastrado com sucesso!", 'Sucesso');
    header('Location: gerencia_recurso.php');
    exit();
} catch (Throwable $t) {
    error_log('Erro ao cadastrar recurso: ' . $t->getMessage());
    set_flash('error', 'Ocorreu um erro no banco de dados ao salvar o recurso.', 'Erro no Sistema');
    header('Location: recurso.php');
    exit();
}
"""

with open('cadastrar_recurso.php', 'w', encoding='utf-8') as f:
    f.write(cadastrar_recurso_code)
print("Gerado: cadastrar_recurso.php")

# 4. edit_recurso.php (Edição de Recurso)
edit_recurso_code = """<?php
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
"""

with open('edit_recurso.php', 'w', encoding='utf-8') as f:
    f.write(edit_recurso_code)
print("Gerado: edit_recurso.php")

# 5. delete_recurso.php / del_recurso.php (Exclusão Segura)
delete_recurso_code = """<?php
/**
 * CEEP Reservas - Exclusão Segura de Recurso (Administrador)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']);

$id = (int)($_REQUEST['id'] ?? 0);

if ($id <= 0) {
    set_flash('error', 'Identificador de recurso inválido.', 'Erro');
    header('Location: gerencia_recurso.php');
    exit();
}

try {
    $rec = DB::fetchOne("SELECT nome_recurso FROM recursos WHERE cod_recurso = ?", [$id]);
    if ($rec) {
        // Exclui reservas vinculadas ao recurso
        DB::execute("DELETE FROM reservas WHERE cod_recurso = ?", [$id]);
        // Exclui o recurso
        DB::execute("DELETE FROM recursos WHERE cod_recurso = ?", [$id]);

        set_flash('success', "Recurso '{$rec['nome_recurso']}' e suas reservas foram removidos com sucesso.", 'Recurso Excluído');
    } else {
        set_flash('error', 'Recurso não encontrado.', 'Erro');
    }
} catch (Throwable $t) {
    error_log('Erro ao excluir recurso: ' . $t->getMessage());
    set_flash('error', 'Ocorreu um erro no servidor ao excluir o recurso.', 'Erro no Sistema');
}

header('Location: gerencia_recurso.php');
exit();
"""

with open('delete_recurso.php', 'w', encoding='utf-8') as f:
    f.write(delete_recurso_code)
print("Gerado: delete_recurso.php")

with open('del_recurso.php', 'w', encoding='utf-8') as f:
    f.write("<?php require_once __DIR__ . '/delete_recurso.php'; ?>")
print("Gerado: del_recurso.php (wrapper)")
