
<?php 
 $ID = $_REQUEST["id"];

 require_once('conecta.php');

 $link= DB_Connect();

 $sql = "DELETE FROM reservas WHERE ID='$ID'";

  if ($link->query($sql) == TRUE) {
      
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'home.php'
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
                                  top.location.href = 'home.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

    }

?>