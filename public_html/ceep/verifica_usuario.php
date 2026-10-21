<?php
/**
 * CEEP Reservas - Processamento Seguro de Login
 */

require_once __DIR__ . '/core/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$rawInput = trim($_POST['matricula'] ?? '');
$senha = $_POST['senha'] ?? '';

// Permite buscar tanto por matrícula quanto por CPF (apenas números)
$identificador = preg_replace('/\D/', '', $rawInput);

if (empty($identificador) || empty($senha)) {
    $_SESSION['login'] = 'false';
    header('Location: index.php');
    exit();
}

try {
    // Busca usuário por matrícula ou por CPF usando prepared statements
    $user = DB::fetchOne(
        "SELECT * FROM usuarios WHERE (matricula = ? OR cpf = ?) LIMIT 1",
        [$identificador, $identificador]
    );

    if ($user && verify_and_upgrade_password($senha, $user['senha'], (int)$user['usuario_id'])) {
        // Regenera ID de sessão para prevenir fixation attacks
        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $user['usuario_id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['sobrenome'] = $user['sobrenome'] ?? '';
        $_SESSION['cpf'] = $user['cpf'];
        $_SESSION['matricula'] = $user['matricula'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['tipo'] = $user['tipo']; // 'a' ou 'p'
        $_SESSION['nivel_usuario'] = $user['nivel_usuario'] ?? '0';
        $_SESSION['data_ultimo_login'] = $user['data_ultimo_login'] ?? date('Y-m-d H:i:s');
        $_SESSION['login'] = 'true';

        // Atualiza data do último login
        DB::execute(
            "UPDATE usuarios SET data_ultimo_login = ? WHERE usuario_id = ?",
            [date('Y-m-d H:i:s'), $user['usuario_id']]
        );

        set_flash('success', 'Bem-vindo de volta, ' . e($user['nome']) . '!', 'Login efetuado com sucesso');
        header('Location: home.php');
        exit();
    } else {
        $_SESSION['login'] = 'false';
        header('Location: index.php');
        exit();
    }
} catch (Throwable $t) {
    error_log('Login error: ' . $t->getMessage());
    $_SESSION['login'] = 'false';
    header('Location: index.php');
    exit();
}
