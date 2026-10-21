
<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>
<?php

$nome = trim($_POST['nome']);

if (!$nome){

echo "Nome é requerido.<br />";

}

else {

include_once 'recursos.php'; 
    
    $categoria = $_POST["tipo"];
    $recurso = new recursos;
    $recurso->nome($nome);
    $valor=$recurso->VerificarRepeticao2();
    if ($valor==0){
      $recurso->Cadastro($categoria);
    }
}
?>