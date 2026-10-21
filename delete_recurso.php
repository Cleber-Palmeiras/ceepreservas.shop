<?php
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
