<?php
require_once __DIR__ . '/core/bootstrap.php';

class verifica_acesso {
    public function checar_se_foi_logado() {
        auth_require(['a', 'p']);
    }

    public function destruir() {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
