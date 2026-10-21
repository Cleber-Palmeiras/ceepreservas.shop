<head>
<meta charset="utf-8">
<title>Deletar</title>
</head>
<script src="sweetalert-dev.min.js"/> </script>
<script src="stylesheet" href="sweetalert.css" /> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />
<?php 

include_once 'recursos.php'; 
 
    $id = $_REQUEST['id'];
    $recurso = new recursos;
    $recurso->codigo($id);
    $recurso->excluir();

?>

 
    
