<html >
<meta charset='utf-8'>
<script src="sweetalert-dev.min.js"/> </script>
<script src="stylesheet" href="sweetalert.css" /> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />

<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<?php 

$senha = $_POST['senha'];
$senha1 = $_POST['senhanova'];
$senha2 = $_POST['senhanova2'];
$id = $_SESSION["usuario_id"];

if ($senha1 != $senha2){
	echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'As novas senhas não são idênticas!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'modificar_senha.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
}

else {
    $codifica = hash('sha512',$senha);
    $senha=$codifica;
    $codifica = hash('sha512',$senha1);
    $senha1=$codifica;

if ($_SESSION["senha"] == $senha){
  include_once 'usuario.php';
    $user = new usuario;
    $user->senha($id,$senha,$senha1);
    $user->alterarsenha();
    $_SESSION["senha"] = $senha1; 

}

else {
  echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Senha inválida!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'modificar_senha.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");  
}
}
?>