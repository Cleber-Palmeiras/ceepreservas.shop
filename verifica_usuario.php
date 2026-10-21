<html>
<head>
        <meta charset = "UTF-8 ">
    </head>
<html>
<?php

require_once "conecta.php";

$matricula = preg_replace('/[^[:alnum:]_]/', '',$_POST["matricula"]);
$senha = preg_replace('/[^[:alnum:]_]/', '',$_POST["senha"]);

//$matricula = $_POST["matricula"]; 
//$senha = $_POST["senha"]; 


if ((!$matricula) || (!$senha)){

//echo "Por favor, todos campos devem ser preenchidos! <br /><br />";

echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ swal('Dados inválidos!', '', 'error'); window.navigate('index.php'); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");

include "index.php";

}else{

        $codifica = hash('sha512',$senha);
        //AGORA RETORNO O VALOR FINAL ENCRIPTADO
        
        $senha = $codifica;

        $link = DB_Connect();

session_start(); // Inicia a session

$sql = "SELECT * FROM usuarios WHERE matricula='{$matricula}' AND senha='{$senha}'";
/* AND ativado='1'"
*/

$query = $link->query($sql);

$login_check = $query->num_rows;

if ($login_check > 0){

while ($row = $query->fetch_array()){

foreach ($row AS $key => $val){

$$key = stripslashes( $val );

}


$_SESSION['usuario_id'] = $usuario_id;
$_SESSION['nome'] = $nome;
$_SESSION['sobrenome'] = $sobrenome;
$_SESSION['cpf'] = $cpf;
$_SESSION['matricula'] = $matricula;
$_SESSION['email'] = $email;
$_SESSION['tipo'] = $tipo;
$_SESSION['leitura'] = $leitura;
$_SESSION['mensagem'] = $mensagem;
$_SESSION['nivel_usuario'] = $nivel_usuario;
$_SESSION['data_ultimo_login'] = $data_ultimo_login;
$_SESSION['senha'] = $senha;
$_SESSION["login"] = "true";

$id = $_SESSION['usuario_id'];

date_default_timezone_set('America/Fortaleza');
$date = date('Y-m-d H:i');

$sql1 = "UPDATE usuarios SET data_ultimo_login = '$date' WHERE usuario_id ='$id'";

if ($link->query($sql1) == TRUE) {
 //echo "FOI" ;                             
}
else {
	//echo "Não foi-se";
}

header("Location: home.php");
}
}else{

//echo "Você não pode logar-se! Este usuário e/ou senha não são válidos!<br />
//Por favor tente novamente!<br />";
$_SESSION["login"] = "false";
//echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ swal('Dados inválidos!', '', 'error');  } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
include "index.php";


}

}

?>