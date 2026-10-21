<?php
/**
 * Conexao com o banco de dados - Camada retrocompativel modernizada
 */

require_once __DIR__ . '/core/bootstrap.php';

if (!function_exists('DB_Connect')) {
    function DB_Connect() {
        return DB::mysqli();
    }
}

if (!function_exists('DB_Close')) {
    function DB_Close($link = null) {
        return true;
    }
}
