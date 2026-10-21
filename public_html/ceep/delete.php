<meta charset='utf-8'>
<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<script src="sweetalert-dev.min.js"/> </script>
<script src="stylesheet" href="sweetalert.css" /> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />

<?php

 $id = isset($_GET['id']);

 if (isset($_GET['id']) && is_numeric($_GET['id']))
 {
 $id = $_GET['id'];
 
    ?> 
    <BODY > 
    <?php
    
    echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({   title: '',   
                        text: 'Deseja realmente excluir este usuário?',   
                        type: 'warning',   
                        showCancelButton: true,   
                        confirmButtonColor: '#DD6B55',   
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Não',   
                        closeOnConfirm: false 
                        }, 

                        function(isConfirm){   
                            if (isConfirm) {     
                               top.location.href = 'deletar.php?id=$id' 
                            } 
                            else {     
                                top.location.href = 'view-paginated.php'  
                            } 
                        }

                        );} </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
                        
}
 else
 {
 	echo "Não foi-se";
 }
 
?>