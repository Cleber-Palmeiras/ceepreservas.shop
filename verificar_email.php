<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
}
?>
<?php 
include_once 'recuperar_senha.php';

$email = trim($_POST['email']);

    $user = new recuperar_senha;
    $user->email($email);
    $cont=$user->VerificarEmail();
    
    if ($cont>0){
    	$user->Reg();	
    }
    else {
           	
    }

?>