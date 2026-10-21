# -*- coding: utf-8 -*-
"""
Modernização de ajuda.php e sincronização com public_html/ceep/
"""
import shutil
import os

ajuda_code = """<?php
/**
 * CEEP Reservas - Central de Ajuda & Orientações de Uso
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a', 'p']);

render_header('Central de Ajuda', 'ajuda');
?>

<div class="page-header animate-slide-in">
    <div>
        <h1 class="page-title">Central de Ajuda &amp; Orientações</h1>
        <p class="page-subtitle">Instruções para uso responsável e eficiente do sistema de reservas de recursos.</p>
    </div>
    <div class="page-actions">
        <a href="user1.php" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Fazer Reserva</span>
        </a>
    </div>
</div>

<div class="card animate-slide-in" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <span>Dúvidas Frequentes &amp; Regras de Uso</span>
        </h2>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem; line-height: 1.6;">
        <div>
            <h3 style="font-size: 1.05rem; font-family: var(--font-heading); color: var(--slate-900); margin-bottom: 0.35rem;">
                1. Como efetuar um agendamento de sala ou equipamento?
            </h3>
            <p style="color: var(--slate-600); font-size: 0.92rem;">
                No menu superior, acesse <strong>Reservas &rarr; Nova Reserva</strong>. Selecione a categoria do recurso (Datashow, Laboratório, Sala, etc.), escolha o equipamento desejado e o turno (Manhã, Tarde ou Noite). Em seguida, navegue pela grade semanal e clique em qualquer horário disponível (verde) para confirmar seu agendamento instantaneamente.
            </p>
        </div>

        <div style="border-top: 1px solid var(--slate-100); padding-top: 1.25rem;">
            <h3 style="font-size: 1.05rem; font-family: var(--font-heading); color: var(--slate-900); margin-bottom: 0.35rem;">
                2. Qual a antecedência permitida para reservas?
            </h3>
            <p style="color: var(--slate-600); font-size: 0.92rem;">
                Os agendamentos são abertos para a semana corrente e semanas subsequentes do calendário letivo, permitindo aos professores planejar suas aulas práticas com antecedência organizada.
            </p>
        </div>

        <div style="border-top: 1px solid var(--slate-100); padding-top: 1.25rem;">
            <h3 style="font-size: 1.05rem; font-family: var(--font-heading); color: var(--slate-900); margin-bottom: 0.35rem;">
                3. Como cancelar uma reserva que não vou utilizar?
            </h3>
            <p style="color: var(--slate-600); font-size: 0.92rem;">
                Acesse <strong>Reservas &rarr; Minhas Reservas</strong> ou o próprio painel inicial. Localize o horário agendado e clique no botão vermelho <strong>Cancelar</strong>. Liberar o recurso quando não for utilizá-lo é fundamental para que outros colegas docentes possam agendá-lo.
            </p>
        </div>

        <div style="border-top: 1px solid var(--slate-100); padding-top: 1.25rem;">
            <h3 style="font-size: 1.05rem; font-family: var(--font-heading); color: var(--slate-900); margin-bottom: 0.35rem;">
                4. Como alterar minha senha ou dados cadastrais?
            </h3>
            <p style="color: var(--slate-600); font-size: 0.92rem;">
                Clique no seu nome/avatar no canto superior direito do cabeçalho e selecione <strong>Meus Dados</strong> para atualizar seu e-mail ou <strong>Alterar Senha</strong> para atualizar sua credencial de segurança.
            </p>
        </div>
    </div>
</div>

<?php
render_footer();
"""

with open('ajuda.php', 'w', encoding='utf-8') as f:
    f.write(ajuda_code)
print("Gerado: ajuda.php")

# Redirecionadores e compatibilidade para scripts legados
wrappers = {
    'hoje.php': "<?php require_once __DIR__ . '/home.php'; ?>",
    'listahoje.php': "<?php require_once __DIR__ . '/home.php'; ?>",
    'listareserva.php': "<?php require_once __DIR__ . '/reservas.php'; ?>",
    'estaorecursos.php': "<?php require_once __DIR__ . '/gerencia_recurso.php'; ?>",
    'pesquisa.php': "<?php require_once __DIR__ . '/view-paginated.php'; ?>",
    'pesquisa_recurso.php': "<?php require_once __DIR__ . '/gerencia_recurso.php'; ?>",
    'user_novo.php': "<?php require_once __DIR__ . '/user1.php'; ?>",
}

for wfile, wcode in wrappers.items():
    with open(wfile, 'w', encoding='utf-8') as f:
        f.write(wcode)
    print(f"Gerado: {wfile} (wrapper limpo)")

# Sincroniza os arquivos e o core/ para dentro de public_html/ceep se existir
target_ceep = 'public_html/ceep'
if os.path.isdir(target_ceep):
    # Copia core
    target_core = os.path.join(target_ceep, 'core')
    if os.path.exists(target_core):
        shutil.rmtree(target_core)
    shutil.copytree('core', target_core)
    
    # Copia arquivos PHP principais
    for fname in [
        'index.php', 'conecta.php', 'conf.inc.php', 'home.php', 'user1.php', 'user1_c.php',
        'reservas.php', 'minhas_reservas.php', 'cancela.php', 'cancelar.php', 'cancelar_reservas.php',
        'apagar.php', 'apagar2.php', 'excluir_reservas.php', 'todas_reservas.php',
        'gerencia_recurso.php', 'recurso.php', 'cadastrar_recurso.php', 'edit_recurso.php',
        'delete_recurso.php', 'del_recurso.php', 'view-paginated.php', 'viewusuarios.php',
        'formulario_cadastro.php', 'cadastrar.php', 'editar_usuario.php', 'modificar_senha.php',
        'recuperar_senha.php', 'delete.php', 'deletar.php', 'logout.php', 'ajuda.php',
        'hoje.php', 'listahoje.php', 'listareserva.php', 'estaorecursos.php', 'pesquisa.php',
        'pesquisa_recurso.php', 'user_novo.php', 'functions.php', 'verifica_acesso.php', 'verifica_usuario.php'
    ]:
        if os.path.exists(fname):
            shutil.copy2(fname, os.path.join(target_ceep, fname))
    print("Sincronizado com public_html/ceep/")

