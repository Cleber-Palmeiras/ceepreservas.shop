<meta charset='utf-8'>
<script src="sweetalert-dev.min.js"/> </script>
<script src="stylesheet" href="sweetalert.css" /> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />

<?php 
 $ID = $_REQUEST["id"];

 require_once('conecta.php');

 $link= DB_Connect();

 $sql = "UPDATE reservas SET pegou=1 WHERE ID='$ID'";

  if ($link->query($sql) == TRUE) {

        $_SESSION["status"] = "pegou";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'home.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                            
          
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