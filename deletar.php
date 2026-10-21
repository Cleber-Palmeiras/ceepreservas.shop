<head>
<meta charset="utf-8">
<title>Deletar</title>
</head>
<script src="sweetalert-dev.min.js"/> </script>
<script src="stylesheet" href="sweetalert.css" /> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />
<?php 

include_once 'usuario.php'; 
 
 $id = isset($_REQUEST['id']);

 if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
 {
    $id = $_REQUEST['id'];
    $usuario = new usuario;
    $usuario->codigo($id);
    $usuario->excluir();
 }

?>