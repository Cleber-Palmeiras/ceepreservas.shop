
<?php 
 $ID = $_REQUEST["id"];

 require_once('conecta.php');

 $link= DB_Connect();

 $sql = "UPDATE reservas SET pegou=2 WHERE ID='$ID'";

  if ($link->query($sql) == TRUE) {

          $_SESSION["status"] = "devolveu";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'home.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                         return;       
                          
          
    } else {
           echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao atualizar os dados',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'home.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

    }

?>