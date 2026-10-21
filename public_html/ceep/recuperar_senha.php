<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Recuperar Senha</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<?php

require_once('conecta.php');

class RecuperarSenha {

    private $email;
    private $novaSenha;

    private function conecta() {
        return DB_Connect();
    }

    public function setEmail($email) {
        $this->email = trim($email);
    }

    public function verificarEmail() {
        $link = $this->conecta();

        $stmt = $link->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            $this->alerta("error", "Email não cadastrado!", "formulario_senha_perdida.html");
            exit;
        }

        return true;
    }

    private function gerarSenha() {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $senha = "";

        for ($i = 0; $i < 8; $i++) {
            $senha .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $senha;
    }

    public function atualizarSenha() {
        $link = $this->conecta();

        $this->novaSenha = $this->gerarSenha();

        // 🔐 HASH MODERNO
        $hash = password_hash($this->novaSenha, PASSWORD_DEFAULT);

        $stmt = $link->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
        $stmt->bind_param("ss", $hash, $this->email);

        if (!$stmt->execute()) {
            $this->alerta("error", "Erro ao atualizar senha!", "formulario_senha_perdida.html");
            exit;
        }

        $this->enviarEmail();
    }

    private function enviarEmail() {
        $to = $this->email;
        $subject = "Recuperação de Senha";

        $mensagem = "
            <h3>Sua nova senha</h3>
            <p><strong>{$this->novaSenha}</strong></p>
        ";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: Sistema <no-reply@seusite.com>\r\n";

        mail($to, $subject, $mensagem, $headers);

        $this->alerta("success", "Nova senha enviada para seu email!", "index.php");
    }

    private function alerta($tipo, $mensagem, $redirect) {
        echo "
        <script>
            Swal.fire({
                icon: '$tipo',
                text: '$mensagem'
            }).then(() => {
                window.location.href = '$redirect';
            });
        </script>
        ";
    }
}

// ===================== EXECUÇÃO =====================

if (isset($_POST['email'])) {
    $rec = new RecuperarSenha();
    $rec->setEmail($_POST['email']);
    $rec->verificarEmail();
    $rec->atualizarSenha();
}

?>

</body>
</html>