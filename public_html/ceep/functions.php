<?php
require_once __DIR__ . '/core/bootstrap.php';

if (!function_exists('session_checker')) {
    function session_checker() {
        auth_require(['a', 'p']);
    }
}
