# -*- coding: utf-8 -*-
import re

with open('core/bootstrap.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Substitui o método pdo() inteiro
old_method_regex = r'public static function pdo\(\): PDO \{.*?return self::\$pdo;\s*\}'

new_method = """public static function pdo(): PDO {
        if (self::$pdo === null) {
            $host = getenv('DB_HOSTNAME') ?: (defined('DB_HOSTNAME') ? DB_HOSTNAME : 'localhost');
            $database = getenv('DB_DATABASE') ?: (defined('DB_DATABASE') ? DB_DATABASE : 'tcc');
            $username = getenv('DB_USERNAME') ?: (defined('DB_USERNAME') ? DB_USERNAME : 'root');
            $password = getenv('DB_PASSWORD') ?: (defined('DB_PASSWORD') ? DB_PASSWORD : '');

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            // Lista de tentativas (configuração do conf.inc.php + fallbacks para desenvolvimento local)
            $tentativas = [
                ['host' => $host, 'db' => $database, 'user' => $username, 'pass' => $password],
                ['host' => '127.0.0.1', 'db' => $database, 'user' => $username, 'pass' => $password],
                ['host' => 'localhost', 'db' => 'tcc', 'user' => 'root', 'pass' => ''],
                ['host' => '127.0.0.1', 'db' => 'tcc', 'user' => 'root', 'pass' => ''],
                ['host' => 'localhost', 'db' => $database, 'user' => 'root', 'pass' => ''],
                ['host' => 'localhost', 'db' => $database, 'user' => 'root', 'pass' => 'root'],
                ['host' => '127.0.0.1', 'db' => 'tcc', 'user' => 'root', 'pass' => 'root'],
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

            // Exibe diagnóstico claro e técnico caso todas as conexões falhem
            die(
                "<div style='font-family: system-ui, sans-serif; max-width: 650px; margin: 50px auto; padding: 24px; border: 1px solid #fecaca; background: #fff5f5; border-radius: 14px; color: #991b1b; box-shadow: 0 10px 20px rgba(0,0,0,0.05);'>" .
                "<h2 style='margin-top:0; color:#b91c1c; font-size: 1.25rem;'>Falha na Conexão com o Banco de Dados</h2>" .
                "<p style='font-size: 14px;'>O PHP tentou conectar ao MySQL mas o servidor retornou o seguinte erro:</p>" .
                "<div style='background: #fee2e2; padding: 12px 14px; border-radius: 8px; font-family: monospace; font-size: 13px; margin: 14px 0; word-break: break-all;'>" . htmlspecialchars($ultimoErro) . "</div>" .
                "<hr style='border: none; border-top: 1px solid #fecaca; margin: 16px 0;'>" .
                "<p style='font-size: 14px; margin-bottom: 6px;'><strong>Configurações lidas em conf.inc.php:</strong></p>" .
                "<ul style='font-size: 13px; line-height: 1.6; margin-left: 20px;'>" .
                "<li><strong>DB_HOSTNAME:</strong> " . htmlspecialchars($host) . "</li>" .
                "<li><strong>DB_DATABASE:</strong> " . htmlspecialchars($database) . "</li>" .
                "<li><strong>DB_USERNAME:</strong> " . htmlspecialchars($username) . "</li>" .
                "</ul>" .
                "<p style='font-size: 13px; color: #7f1d1d; margin-top: 14px; line-height: 1.5;'><strong>Como resolver no seu ambiente:</strong><br>" .
                "1. Abra o arquivo <code>conf.inc.php</code> e ajuste o usuário e senha do seu MySQL local (ex: usuário <code>root</code> e senha vazia).<br>" .
                "2. Certifique-se de que o serviço MySQL esteja iniciado (no XAMPP, MAMP, Herd ou Homebrew).<br>" .
                "3. Caso ainda não tenha criado o banco, crie o banco <code>tcc</code> (ou <code>u749688875_ceep</code>) e importe o arquivo <code>tcc.sql</code>.</p>" .
                "</div>"
            );
        }
        return self::$pdo;
    }"""

content = re.sub(old_method_regex, new_method, content, flags=re.DOTALL)

with open('core/bootstrap.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("core/bootstrap.php atualizado com sucesso!")
