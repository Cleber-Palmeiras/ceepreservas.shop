<?php
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
