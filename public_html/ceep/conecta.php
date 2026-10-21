<?php
require_once('conf.inc.php');

function DB_Connect(){

    $link = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    // Verifica conexão
    if (!$link) {
        die("Erro na conexão com o banco: " . mysqli_connect_error());
    }

    // Define charset
    if (!mysqli_set_charset($link, DB_CHARSET)) {
        die("Erro ao definir charset: " . mysqli_error($link));
    }

    return $link;
}

// Fecha conexão
function DB_Close($link){
    if ($link) {
        mysqli_close($link);
    }
}
?>