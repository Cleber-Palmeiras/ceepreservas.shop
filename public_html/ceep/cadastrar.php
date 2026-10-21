<?php
/**
 * CEEP Reservas - Processador Seguro de Cadastro de Usuário
 */

require_once __DIR__ . '/core/bootstrap.php';
auth_require(['a']); // Exclusivo Administrador

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: view-paginated.php');
    exit();
}

if (!csrf_verify()) {
    set_flash('error', 'Token de segurança inválido.', 'Erro');
    header('Location: formulario_cadastro.php');
    exit();
}

$nome = trim($_POST['nome'] ?? '');
$sobrenome = trim($_POST['sobrenome'] ?? '');
$matricula = preg_replace('/\D/', '', $_POST['matricula'] ?? '');
$cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
$email = trim($_POST['email'] ?? '');
$tipo = trim($_POST['tipo'] ?? 'p');
$senha = $_POST['senha'] ?? '123456';

if (empty($nome) || empty($matricula) || empty($cpf) || empty($email)) {
    set_flash('error', 'Por favor, preencha todos os campos obrigatórios.', 'Campos Incompletos');
    header('Location: formulario_cadastro.php');
    exit();
}

try {
    // 1. Verifica duplicidade de Matrícula
    $dupMat = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE matricula = ?", [$matricula]);
    if ($dupMat) {
        set_flash('warning', "A matrícula '{$matricula}' já está cadastrada para outro usuário.", 'Matrícula Duplicada');
        header('Location: formulario_cadastro.php');
        exit();
    }

    // 2. Verifica duplicidade de CPF
    $dupCpf = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE cpf = ?", [$cpf]);
    if ($dupCpf) {
        set_flash('warning', "O CPF informado já está cadastrado no sistema.", 'CPF Duplicado');
        header('Location: formulario_cadastro.php');
        exit();
    }

    // 3. Verifica duplicidade de E-mail
    $dupEmail = DB::fetchOne("SELECT usuario_id FROM usuarios WHERE LOWER(email) = LOWER(?)", [$email]);
    if ($dupEmail) {
        set_flash('warning', "O e-mail informado já está em uso por outra conta.", 'E-mail Duplicado');
        header('Location: formulario_cadastro.php');
        exit();
    }

    // Gera hash moderno e seguro
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $agora = date('Y-m-d H:i:s');

    DB::execute(
        "INSERT INTO usuarios (nome, sobrenome, cpf, matricula, email, tipo, senha, nivel_usuario, data_cadastro, data_ultimo_login, ativado)
         VALUES (?, ?, ?, ?, ?, ?, ?, '0', ?, '0000-00-00 00:00:00', '1')",
        [$nome, $sobrenome, $cpf, $matricula, $email, $tipo, $senhaHash, $agora]
    );

    set_flash('success', "Usuário '{$nome} {$sobrenome}' cadastrado com sucesso!", 'Cadastro Realizado');
    header('Location: view-paginated.php');
    exit();
} catch (Throwable $t) {
    error_log('Erro ao cadastrar usuário: ' . $t->getMessage());
    set_flash('error', 'Erro ao salvar o usuário no banco de dados: ' . $t->getMessage(), 'Erro no Sistema');
    header('Location: formulario_cadastro.php');
    exit();
}
