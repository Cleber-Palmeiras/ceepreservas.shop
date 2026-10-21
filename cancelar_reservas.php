<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<?php

if($_SESSION["cpf"]==""){
  echo("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ alert('Você ainda não está logado!.'); window.navigate('index.php'); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
  exit();
}

if($_SESSION["tipo"]!="a"){
  
  echo("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ alert('Você não tem permissão para acessar essa página!.'); window.navigate('index.php'); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
  exit();
  
}
 $ID = $_REQUEST["id"];

 require_once('conecta.php');

 $link= DB_Connect();

$sql1 = "SELECT * FROM reservas WHERE ID='$ID'";
$query = $link->query($sql1);
$resultado1 = $link->query($sql1) or die($link->error());
$linha1 = $query->fetch_array();

$matricula =  $linha1["matricula"]; 
$cod_recurso = $linha1["cod_recurso"];
$data = $linha1["datatempo"];
$adm = $_SESSION["nome"]. " ". $_SESSION["sobrenome"] ;
$email = $_SESSION["email"] ;

include_once 'notificacao.php';
$n = new Notificacao;

$nome=$n->NotificarUsuario($matricula, $cod_recurso, $data, $adm, $email);

$n->VerificarUsuario(); 
$n->VerificarRecurso();
//$this->Horario();
$n->CriarMensagem();
$n->Mensagem();


 $sql = "DELETE FROM reservas WHERE ID='$ID'";

  if ($link->query($sql) == TRUE) {
      
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'excluir_reservas.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                         return;       
          
    } else {
           echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao cancelar',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'excluir_reservas.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

    }


?>