<?php
/**
 * CEEP Reservas - Exclusão Segura de Usuário
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

$id = (int)($_REQUEST['id'] ?? 0);

if ($id <= 0 || $id === (int)$_SESSION['usuario_id']) {
    set_flash('error', 'Operação inválida. Não é permitido excluir a própria conta logada.', 'Erro');
    header('Location: view-paginated.php');
    exit();
}

try {
    $u = DB::fetchOne("SELECT matricula, nome, sobrenome FROM usuarios WHERE usuario_id = ?", [$id]);
    if ($u) {
        // Remove reservas do usuário
        DB::execute("DELETE FROM reservas WHERE matricula = ?", [$u['matricula']]);
        // Remove usuário
        DB::execute("DELETE FROM usuarios WHERE usuario_id = ?", [$id]);

        set_flash('success', "Usuário '{$u['nome']} {$u['sobrenome']}' e suas reservas foram removidos com sucesso.", 'Usuário Excluído');
    } else {
        set_flash('error', 'Usuário não encontrado.', 'Erro');
    }
} catch (Throwable $t) {
    error_log('Erro ao excluir usuário: ' . $t->getMessage());
    set_flash('error', 'Erro no servidor ao tentar excluir o usuário.', 'Erro');
}

header('Location: view-paginated.php');
exit();
