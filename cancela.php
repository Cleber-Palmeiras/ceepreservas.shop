</style>
<!DOCTYPE html>
<html >
<script src="sweetalert-dev.min.js"/> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />
<link rel="stylesheet" href="css/stilo.css" href="css/bootstrap-responsive">
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
<head>
    <title>Reserva de recursos</title>     
</head>
</style>

<?php 

if (!isset($_SESSION)) {
    session_start();
}
if (empty($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

 $ID = $_REQUEST["id"];

 require_once('conecta.php');

 $link= DB_Connect();
 $iu = $_SESSION["matricula"]; // ID USUARIO
 $sql = "DELETE FROM reservas WHERE ID='$ID' AND matricula='$iu'";

  if ($link->query($sql) == TRUE) {
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Reserva Cancelada! :(',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'user1.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                         return;       
          
    } else {
           echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao cancelar',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'user1.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

    }

?>