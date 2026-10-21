<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<?php
function session_checker(){

if (!isset($_SESSION['usuario_id'])){

header ("Location:index.php");
exit();

}

}

?>