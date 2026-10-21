# -*- coding: utf-8 -*-
import re

with open('core/bootstrap.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Novo método pdo() com suporte a SQLite automático e fallback para MySQL
new_method = """public static function pdo(): PDO {
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
    }"""

old_method_regex = r'public static function pdo\(\): PDO \{.*?return self::\$pdo;\s*\}'
content = re.sub(old_method_regex, new_method, content, flags=re.DOTALL)

with open('core/bootstrap.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("core/bootstrap.php atualizado com suporte dual (SQLite .db + MySQL)!")
