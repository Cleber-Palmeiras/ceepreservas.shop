<?php
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
