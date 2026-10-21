<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<html>
<head>
<script src="sweetalert-dev.min.js"/> </script>
<script src="stylesheet" href="sweetalert.css" /> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />
<meta charset = "UTF-8 ">
</head>
</html>
<?php

//echo ("<SCRIPT LANGUAGE=\"JavaScript\"> 
 //                       swal({ title: 'Saindo....', timer: 500, showConfirmButton: false}) </SCRIPT>"); 

session_destroy();

include "index.php";


?>