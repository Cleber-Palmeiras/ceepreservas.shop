<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<?php 

  $_SESSION["opcao"] = "vazia";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        
                                  top.location.href = 'user1.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
                          
                          return;
 
?> 