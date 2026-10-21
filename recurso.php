<?php
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
