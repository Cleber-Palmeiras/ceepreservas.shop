<?php
session_start();
require_once "conecta.php";

// Verifica se veio POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["login"] = "false";
    header("Location: index.php");
    exit;
}

// Pegando dados
$matricula = trim($_POST["matricula"] ?? '');
$senha = $_POST["senha"] ?? '';

// Validação
if (empty($matricula) || empty($senha)) {
    $_SESSION["login"] = "false";
    header("Location: index.php");
    exit;
}

// Conexão
$link = DB_Connect();

// Criptografa senha (tem que bater com o banco)
$senha_hash = hash('sha512', $senha);

// Query (SEM get_result → compatível com Hostinger)
$stmt = mysqli_prepare($link, "SELECT * FROM usuarios WHERE matricula = ? AND senha = ?");

if (!$stmt) {
    die("Erro na query: " . mysqli_error($link));
}

mysqli_stmt_bind_param($stmt, "ss", $matricula, $senha_hash);
mysqli_stmt_execute($stmt);

// Resultado manual (compatível com qualquer servidor)
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);

    // Sessão
    $_SESSION['usuario_id'] = $row['usuario_id'];
    $_SESSION['nome'] = $row['nome'];
    $_SESSION['sobrenome'] = $row['sobrenome'];
    $_SESSION['cpf'] = $row['cpf'];
    $_SESSION['matricula'] = $row['matricula'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['tipo'] = $row['tipo'];
    $_SESSION['leitura'] = $row['leitura'];
    $_SESSION['mensagem'] = $row['mensagem'];
    $_SESSION['nivel_usuario'] = $row['nivel_usuario'];
    $_SESSION['data_ultimo_login'] = $row['data_ultimo_login'];
    $_SESSION["login"] = "true";

    // Atualiza último login
    date_default_timezone_set('America/Fortaleza');
    $date = date('Y-m-d H:i:s');

    $stmt2 = mysqli_prepare($link, "UPDATE usuarios SET data_ultimo_login = ? WHERE usuario_id = ?");
    mysqli_stmt_bind_param($stmt2, "si", $date, $row['usuario_id']);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    mysqli_stmt_close($stmt);

    header("Location: home.php");
    exit;

} else {

    $_SESSION["login"] = "false";
    mysqli_stmt_close($stmt);

    header("Location: index.php");
    exit;
}
?>