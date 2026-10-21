<?php
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
