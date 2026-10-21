<?php
/**
 * CEEP Reservas - Cadastro de Novo Usuário (Administrador)
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

render_header('Novo Usuário', 'add_usuario');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Cadastrar Novo Usuário</h1>
        <p class="page-subtitle">Crie uma nova conta de Professor ou Administrador no sistema.</p>
    </div>
    <div class="page-actions">
        <a href="view-paginated.php" class="btn btn-secondary">
            &larr; Voltar para Usuários
        </a>
    </div>
</div>

<div class="card animate-slide-in" style="max-width: 680px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            <span>Dados Cadastrais</span>
        </h2>
    </div>

    <form action="cadastrar.php" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="nome" class="form-label">Nome <span class="required">*</span></label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Primeiro nome" required autofocus>
            </div>
            <div class="form-group">
                <label for="sobrenome" class="form-label">Sobrenome <span class="required">*</span></label>
                <input type="text" id="sobrenome" name="sobrenome" class="form-control" placeholder="Sobrenome completo" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="matricula" class="form-label">Matrícula <span class="required">*</span></label>
                <input type="number" id="matricula" name="matricula" class="form-control" placeholder="Número de matrícula" required>
            </div>
            <div class="form-group">
                <label for="cpf" class="form-label">CPF <span class="required">*</span></label>
                <input type="text" id="cpf" name="cpf" class="form-control" placeholder="Apenas números ou formatado" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">E-mail Institucional ou Pessoal <span class="required">*</span></label>
            <input type="email" id="email" name="email" class="form-control" placeholder="usuario@escola.ce.gov.br" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="tipo" class="form-label">Tipo de Acesso <span class="required">*</span></label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="p">Professor (Realizar e gerenciar próprias reservas)</option>
                    <option value="a">Administrador (Controle total de recursos e usuários)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="senha" class="form-label">Senha Inicial <span class="required">*</span></label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" required minlength="6">
            </div>
        </div>

        <div style="margin-top: 1.75rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <a href="view-paginated.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                Salvar Cadastro
            </button>
        </div>
    </form>
</div>

<?php
render_footer();
