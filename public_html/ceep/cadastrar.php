<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }
?>

<?php

include_once 'usuario.php';

$matricula = $_POST['cpf'];
$nome = $_POST['nome'];
$sobrenome = $_POST['sobrenome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$tipo = $_POST['tipo'];
 

if ((!$nome) || (!$sobrenome) || (!$cpf) || (!$email) || (!$matricula) || (!$tipo)) {
    
  echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Por favor, preecha todos os campos!',
                                type:''
                                },
                                function(){
                                  top.location.href = 'formulario_cadastro.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

    include "formulario_cadastro.php";
} 

else {

    $user = new usuario;
    $user->Cadastro($matricula,$nome, $sobrenome, $cpf, $email, $tipo);
    $valor=$user->VerificarRepeticao();
    if ($valor == 0){
      $user->Reg();  
    }
    

}

?>