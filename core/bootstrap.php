<?php
/**
 * CEEP Reservas - Core Bootstrap
 * Camada de inicialização moderna, segura e retrocompatível (PHP 7.4 - 8.3+)
 */

// Evitar execução direta de erros não tratados e definir timezone
date_default_timezone_set('America/Fortaleza');

// Configuração segura de Sessão
if (session_status() === PHP_SESSION_NONE) {
    // Configurações de cookie de sessão segura
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    
    // Suporte seguro a parâmetros de cookie
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    } else {
        session_set_cookie_params(0, '/; samesite=Lax', '', $isSecure, true);
    }
    session_start();
}

// Carregar configurações do banco
require_once __DIR__ . '/../conf.inc.php';

/**
 * Classe de Conexão e Acesso Seguro ao Banco de Dados (PDO + Retrocompatibilidade mysqli)
 */
class DB {
    private static ?PDO $pdo = null;
    private static $mysqli = null;

    public static function pdo(): PDO {
        if (self::$pdo === null) {
            $sqlitePath1 = __DIR__ . '/../ceep.db';
            $sqlitePath2 = __DIR__ . '/../database.db';

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            // 1. Prioridade para arquivo SQLite local se existir e nenhum MySQL customizado estiver ativo
            $useSqlite = (defined('DB_DRIVER') && DB_DRIVER === 'sqlite') || 
                         (file_exists($sqlitePath1) && (!defined('DB_HOSTNAME') || DB_HOSTNAME === 'localhost' || DB_HOSTNAME === '127.0.0.1'));

            if ($useSqlite && (file_exists($sqlitePath1) || file_exists($sqlitePath2))) {
                $targetDb = file_exists($sqlitePath1) ? $sqlitePath1 : $sqlitePath2;
                try {
                    self::$pdo = new PDO("sqlite:" . $targetDb, null, null, $options);
                    return self::$pdo;
                } catch (PDOException $e) {
                    // Se sqlite falhar, prossegue para MySQL
                }
            }

            // 2. Conexão MySQL
            $host = getenv('DB_HOSTNAME') ?: (defined('DB_HOSTNAME') ? DB_HOSTNAME : 'localhost');
            $database = getenv('DB_DATABASE') ?: (defined('DB_DATABASE') ? DB_DATABASE : 'tcc');
            $username = getenv('DB_USERNAME') ?: (defined('DB_USERNAME') ? DB_USERNAME : 'root');
            $password = getenv('DB_PASSWORD') ?: (defined('DB_PASSWORD') ? DB_PASSWORD : '');

            $tentativas = [
                ['host' => $host, 'db' => $database, 'user' => $username, 'pass' => $password],
                ['host' => '127.0.0.1', 'db' => $database, 'user' => $username, 'pass' => $password],
                ['host' => 'localhost', 'db' => 'tcc', 'user' => 'root', 'pass' => ''],
                ['host' => '127.0.0.1', 'db' => 'tcc', 'user' => 'root', 'pass' => ''],
                ['host' => 'localhost', 'db' => 'tcc', 'user' => 'root', 'pass' => 'root'],
            ];

            $ultimoErro = '';
            foreach ($tentativas as $cfg) {
                try {
                    $dsn = "mysql:host={$cfg['host']};dbname={$cfg['db']};charset=utf8mb4";
                    self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], $options);
                    return self::$pdo;
                } catch (PDOException $e) {
                    try {
                        $dsn = "mysql:host={$cfg['host']};dbname={$cfg['db']};charset=utf8";
                        self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], $options);
                        return self::$pdo;
                    } catch (PDOException $e2) {
                        $ultimoErro = $e2->getMessage();
                    }
                }
            }

            // Se o MySQL falhar mas existir o arquivo ceep.db, usa como fallback de segurança
            if (file_exists($sqlitePath1)) {
                try {
                    self::$pdo = new PDO("sqlite:" . $sqlitePath1, null, null, $options);
                    return self::$pdo;
                } catch (PDOException $e) {
                    // Fallback falhou
                }
            }

            // Mensagem amigável de configuração
            die(
                "<div style='font-family: system-ui, sans-serif; max-width: 650px; margin: 50px auto; padding: 24px; border: 1px solid #fecaca; background: #fff5f5; border-radius: 14px; color: #991b1b;'>" .
                "<h2 style='margin-top:0; color:#b91c1c;'>Falha na Conexão com o Banco de Dados</h2>" .
                "<p>Não foi possível conectar ao MySQL nem ao arquivo local <code>ceep.db</code>.</p>" .
                "<div style='background: #fee2e2; padding: 12px; border-radius: 8px; font-family: monospace; font-size: 13px;'>" . htmlspecialchars($ultimoErro) . "</div>" .
                "</div>"
            );
        }
        return self::$pdo;
    }

    public static function mysqli() {
        if (self::$mysqli === null || (is_object(self::$mysqli) && !@mysqli_ping(self::$mysqli))) {
            $host = getenv('DB_HOSTNAME') ?: (defined('DB_HOSTNAME') ? DB_HOSTNAME : 'localhost');
            $database = getenv('DB_DATABASE') ?: (defined('DB_DATABASE') ? DB_DATABASE : 'tcc');
            $username = getenv('DB_USERNAME') ?: (defined('DB_USERNAME') ? DB_USERNAME : 'root');
            $password = getenv('DB_PASSWORD') ?: (defined('DB_PASSWORD') ? DB_PASSWORD : '');
            $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8';

            self::$mysqli = @mysqli_connect($host, $username, $password, $database);
            if (!self::$mysqli) {
                // Tenta conectar sem o banco e selecionar, caso o nome do banco varie
                self::$mysqli = @mysqli_connect($host, $username, $password);
                if (self::$mysqli) {
                    @mysqli_select_db(self::$mysqli, $database);
                }
            }
            if (self::$mysqli) {
                @mysqli_set_charset(self::$mysqli, $charset);
            }
        }
        return self::$mysqli;
    }

    /**
     * Executa consulta preparada segura
     */
    public static function query(string $sql, array $params = []): PDOStatement {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetchOne(string $sql, array $params = []): ?array {
        $res = self::query($sql, $params)->fetch();
        return $res ?: null;
    }

    public static function execute(string $sql, array $params = []): bool {
        return self::query($sql, $params)->rowCount() >= 0;
    }

    public static function lastInsertId(): string {
        return self::pdo()->lastInsertId();
    }
}

/**
 * Escapar saída HTML com segurança (prevenção contra XSS)
 */
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Proteção CSRF
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): bool {
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    if (!$token || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

/**
 * Funções de Flash Message (Toasts / Alertas elegantes)
 */
function set_flash(string $type, string $message, string $title = ''): void {
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'error', 'warning', 'info'
        'message' => $message,
        'title' => $title
    ];
}

function get_flash(): ?array {
    if (!empty($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Autenticação e Controle de Acesso
 */
function auth_user(): ?array {
    if (empty($_SESSION['usuario_id'])) {
        return null;
    }
    return [
        'id' => $_SESSION['usuario_id'],
        'nome' => $_SESSION['nome'] ?? '',
        'sobrenome' => $_SESSION['sobrenome'] ?? '',
        'nome_completo' => trim(($_SESSION['nome'] ?? '') . ' ' . ($_SESSION['sobrenome'] ?? '')),
        'matricula' => $_SESSION['matricula'] ?? '',
        'cpf' => $_SESSION['cpf'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'tipo' => $_SESSION['tipo'] ?? 'p', // 'a' = admin, 'p' = professor
        'is_admin' => ($_SESSION['tipo'] ?? '') === 'a'
    ];
}

function auth_check(): bool {
    return !empty($_SESSION['usuario_id']);
}

function auth_is_admin(): bool {
    return !empty($_SESSION['tipo']) && $_SESSION['tipo'] === 'a';
}

function auth_require(array $allowedTypes = ['a', 'p']): void {
    if (!auth_check()) {
        header("Location: index.php");
        exit();
    }
    $tipo = $_SESSION['tipo'] ?? '';
    if (!in_array($tipo, $allowedTypes, true)) {
        set_flash('error', 'Você não tem permissão para acessar esta página.', 'Acesso Negado');
        header("Location: home.php");
        exit();
    }
}

/**
 * Função de verificação de senha com compatibilidade com hashes legados e upgrade transparente
 */
function verify_and_upgrade_password(string $plainPassword, string $hashFromDb, int $userId): bool {
    $matches = false;
    $needsRehash = false;

    // 1. Padrão Moderno (password_hash bcrypt/argon2)
    if (password_verify($plainPassword, $hashFromDb)) {
        $matches = true;
        if (password_needs_rehash($hashFromDb, PASSWORD_DEFAULT)) {
            $needsRehash = true;
        }
    }
    // 2. Legado SHA-512 (usado no sistema antigo)
    elseif (hash('sha512', $plainPassword) === $hashFromDb) {
        $matches = true;
        $needsRehash = true;
    }
    // 3. Legado MD5 (alguns usuários antigos)
    elseif (md5($plainPassword) === $hashFromDb) {
        $matches = true;
        $needsRehash = true;
    }
    // 4. Legado texto puro (se houver senhas migradas sem hash)
    elseif ($plainPassword === $hashFromDb) {
        $matches = true;
        $needsRehash = true;
    }

    // Se a senha estiver correta e usar formato antigo, atualiza de forma transparente para password_hash moderno
    if ($matches && $needsRehash) {
        try {
            $newSecureHash = password_hash($plainPassword, PASSWORD_DEFAULT);
            DB::execute("UPDATE usuarios SET senha = ? WHERE usuario_id = ?", [$newSecureHash, $userId]);
        } catch (\Throwable $t) {
            // Silencioso para não interromper login caso haja restrição
            error_log('Rehash failed: ' . $t->getMessage());
        }
    }

    return $matches;
}

/**
 * Renderizador de Cabeçalho Moderno (Design 2026)
 */
function render_header(string $title = 'CEEP Reservas', string $activeTab = 'home'): void {
    $user = auth_user();
    $flash = get_flash();
    $isAdmin = auth_is_admin();
    $userName = $user ? $user['nome_completo'] : 'Usuário';
    $userRole = $isAdmin ? 'Administrador' : 'Professor';
    $initials = '';
    if ($user) {
        $parts = explode(' ', trim($userName));
        $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr($parts[count($parts) - 1] ?? '', 0, 1));
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= e($title) ?> — CEEP Reservas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="core/app.css?v=2026.1">
</head>
<body class="ceep-app">
    <!-- Header Principal -->
    <header class="ceep-header">
        <div class="header-inner">
            <div class="brand-container">
                <a href="home.php" class="brand-link" title="CEEP Reservas - Página Principal">
                    <img src="CRTI.png" alt="Logo CRTI" class="brand-logo-img">
                    <div class="brand-text">
                        <span class="brand-title">CEEP <span class="highlight">Reservas</span></span>
                        <span class="brand-subtitle">Gestão de Salas &amp; Recursos</span>
                    </div>
                </a>
            </div>

            <!-- Navegação Desktop -->
            <nav class="main-nav" id="mainNav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="home.php" class="nav-link <?= $activeTab === 'home' ? 'active' : '' ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>Início</span>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <button type="button" class="nav-link dropdown-toggle <?= in_array($activeTab, ['reservas', 'reservar', 'minhas_reservas', 'cancelar']) ? 'active' : '' ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Reservas</span>
                            <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <div class="dropdown-menu">
                            <a href="user1.php" class="dropdown-item <?= $activeTab === 'reservar' ? 'active' : '' ?>">
                                <div class="item-icon bg-blue"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg></div>
                                <div class="item-text"><strong>Nova Reserva</strong><small>Agendar sala ou recurso</small></div>
                            </a>
                            <a href="reservas.php" class="dropdown-item <?= $activeTab === 'minhas_reservas' ? 'active' : '' ?>">
                                <div class="item-icon bg-emerald"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg></div>
                                <div class="item-text"><strong>Minhas Reservas</strong><small>Consultar meus horários</small></div>
                            </a>
                            <a href="apagar.php" class="dropdown-item <?= $activeTab === 'cancelar' ? 'active' : '' ?>">
                                <div class="item-icon bg-rose"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></div>
                                <div class="item-text"><strong>Cancelar Reserva</strong><small>Liberar horário agendado</small></div>
                            </a>
                        </div>
                    </li>

                    <?php if ($isAdmin): ?>
                    <li class="nav-item dropdown">
                        <button type="button" class="nav-link dropdown-toggle <?= in_array($activeTab, ['recursos', 'add_recurso', 'gerencia_recurso']) ? 'active' : '' ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            <span>Recursos</span>
                            <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <div class="dropdown-menu">
                            <a href="gerencia_recurso.php" class="dropdown-item <?= $activeTab === 'gerencia_recurso' ? 'active' : '' ?>">
                                <div class="item-icon bg-indigo"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg></div>
                                <div class="item-text"><strong>Gerenciar Recursos</strong><small>Listar, editar e excluir</small></div>
                            </a>
                            <a href="recurso.php" class="dropdown-item <?= $activeTab === 'add_recurso' ? 'active' : '' ?>">
                                <div class="item-icon bg-cyan"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></div>
                                <div class="item-text"><strong>Novo Recurso</strong><small>Cadastrar sala ou equipamento</small></div>
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <button type="button" class="nav-link dropdown-toggle <?= in_array($activeTab, ['usuarios', 'add_usuario', 'gerencia_usuario', 'excluir_reservas']) ? 'active' : '' ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Usuários</span>
                            <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <div class="dropdown-menu">
                            <a href="view-paginated.php" class="dropdown-item <?= $activeTab === 'gerencia_usuario' ? 'active' : '' ?>">
                                <div class="item-icon bg-indigo"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></div>
                                <div class="item-text"><strong>Gerenciar Usuários</strong><small>Listar e editar contas</small></div>
                            </a>
                            <a href="formulario_cadastro.php" class="dropdown-item <?= $activeTab === 'add_usuario' ? 'active' : '' ?>">
                                <div class="item-icon bg-cyan"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></div>
                                <div class="item-text"><strong>Novo Usuário</strong><small>Cadastrar professor ou admin</small></div>
                            </a>
                            <a href="excluir_reservas.php" class="dropdown-item <?= $activeTab === 'excluir_reservas' ? 'active' : '' ?>">
                                <div class="item-icon bg-rose"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></div>
                                <div class="item-text"><strong>Todas as Reservas</strong><small>Moderação de agendamentos</small></div>
                            </a>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <!-- Perfil do Usuário e Ações -->
            <div class="user-action-area">
                <div class="user-badge dropdown">
                    <button type="button" class="profile-trigger dropdown-toggle" aria-label="Menu do perfil">
                        <div class="avatar-circle"><?= e($initials) ?></div>
                        <div class="user-meta hide-mobile">
                            <span class="user-name"><?= e($userName) ?></span>
                            <span class="user-role-badge <?= $isAdmin ? 'badge-admin' : 'badge-prof' ?>"><?= e($userRole) ?></span>
                        </div>
                        <svg class="chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header">
                            <strong><?= e($userName) ?></strong>
                            <small>Matrícula: <?= e($user['matricula'] ?? '—') ?></small>
                        </div>
                        <a href="editar_usuario.php" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>Meus Dados</span>
                        </a>
                        <a href="modificar_senha.php" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <span>Alterar Senha</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item text-danger">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            <span>Sair do Sistema</span>
                        </a>
                    </div>
                </div>

                <!-- Botão Menu Mobile -->
                <button type="button" class="mobile-toggle" id="mobileToggle" aria-label="Abrir menu de navegação">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Drawer Mobile -->
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="drawer-header">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <img src="CRTI.png" alt="Logo CRTI" style="height: 36px; width: auto; object-fit: contain;">
                <span class="drawer-title">CEEP Reservas</span>
            </div>
            <button type="button" class="drawer-close" id="drawerClose">&times;</button>
        </div>
        <div class="drawer-user">
            <div class="avatar-circle"><?= e($initials) ?></div>
            <div>
                <strong><?= e($userName) ?></strong>
                <span class="user-role-badge <?= $isAdmin ? 'badge-admin' : 'badge-prof' ?>"><?= e($userRole) ?></span>
            </div>
        </div>
        <nav class="drawer-nav">
            <a href="home.php" class="drawer-link <?= $activeTab === 'home' ? 'active' : '' ?>">Início</a>
            <div class="drawer-section">Reservas</div>
            <a href="user1.php" class="drawer-link <?= $activeTab === 'reservar' ? 'active' : '' ?>">Nova Reserva</a>
            <a href="reservas.php" class="drawer-link <?= $activeTab === 'minhas_reservas' ? 'active' : '' ?>">Minhas Reservas</a>
            <a href="apagar.php" class="drawer-link <?= $activeTab === 'cancelar' ? 'active' : '' ?>">Cancelar Reserva</a>
            <?php if ($isAdmin): ?>
            <div class="drawer-section">Administração</div>
            <a href="gerencia_recurso.php" class="drawer-link <?= $activeTab === 'gerencia_recurso' ? 'active' : '' ?>">Gerenciar Recursos</a>
            <a href="recurso.php" class="drawer-link <?= $activeTab === 'add_recurso' ? 'active' : '' ?>">Novo Recurso</a>
            <a href="view-paginated.php" class="drawer-link <?= $activeTab === 'gerencia_usuario' ? 'active' : '' ?>">Gerenciar Usuários</a>
            <a href="formulario_cadastro.php" class="drawer-link <?= $activeTab === 'add_usuario' ? 'active' : '' ?>">Novo Usuário</a>
            <a href="excluir_reservas.php" class="drawer-link <?= $activeTab === 'excluir_reservas' ? 'active' : '' ?>">Todas as Reservas</a>
            <?php endif; ?>
            <div class="drawer-section">Minha Conta</div>
            <a href="editar_usuario.php" class="drawer-link">Meus Dados</a>
            <a href="modificar_senha.php" class="drawer-link">Alterar Senha</a>
            <a href="logout.php" class="drawer-link text-danger">Sair</a>
        </nav>
    </div>
    <div class="drawer-backdrop" id="drawerBackdrop"></div>

    <!-- Container Principal do Conteúdo -->
    <main class="ceep-main">
        <div class="ceep-container">
            <?php if ($flash): ?>
            <div class="ceep-alert alert-<?= e($flash['type']) ?> animate-slide-in">
                <div class="alert-icon">
                    <?php if ($flash['type'] === 'success'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <?php elseif ($flash['type'] === 'error'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <?php elseif ($flash['type'] === 'warning'): ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    <?php else: ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    <?php endif; ?>
                </div>
                <div class="alert-content">
                    <?php if (!empty($flash['title'])): ?><strong><?= e($flash['title']) ?></strong><br><?php endif; ?>
                    <?= e($flash['message']) ?>
                </div>
                <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
            </div>
            <?php endif; ?>
<?php
}

/**
 * Renderizador de Rodapé Moderno
 */
function render_footer(): void {
?>
        </div>
    </main>

    <!-- Rodapé Moderno -->
    <footer class="ceep-footer">
        <div class="footer-inner">
            <div class="footer-left">
                <span class="footer-brand">CEEP Reservas</span>
                <span class="footer-divider">•</span>
                <span class="footer-desc">Sistema de Agendamento Pedagógico &amp; Patrimonial</span>
            </div>
            <div class="footer-right">
                <span>CEEP &copy; <?= date('Y') ?></span>
                <span class="footer-divider">•</span>
                <span class="footer-version">Versão Modernizada</span>
            </div>
        </div>
    </footer>

    <script src="core/app.js?v=2026.1"></script>
</body>
</html>
<?php
}
