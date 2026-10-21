<head>
<meta charset="utf-8">
<title>Cancelar reserva</title>
</head>
<script src="sweetalert-dev.min.js"/> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />

<?php 
	if (!isset($_SESSION)) {
    session_start();
   }
	if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }
?>

<?php

	require_once('conecta.php');
    $link = DB_Connect();

    if (isset($_POST["apagarc"])){
    	$apagarc=$_POST["apagarc"];	
    }
	else {
					$_SESSION["opcao"] = "vazia";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        
                                  top.location.href = 'apagar.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
                      		
                      		return;
	}

	$matricula=$_POST["matricula"];
	$cod_recurso=$_POST["cod_recurso"];
	$nome=$_POST["nome"];


	if($apagarc==""){ vazio($matricula,$cod_recurso,$nome); }

	foreach ($apagarc as $k => $v) {


		/* echo "\$apagarc[$k] => $v.\n"; */
		
		$sql = "DELETE FROM reservas WHERE ID = '$v' AND matricula = '$matricula' ";
		$query = $link->query($sql);
		if ($link->query($sql) == TRUE) {
			$deletado=1;

		} else {
			$deletado=0;
		}

	}

	if($deletado==1){

					?>
					<HTML>
					
					<BODY>
					
							<form name="verifica" method="post" action="apagar.php">
								<input type="hidden" name="matricula" border=0 value="<?php echo $matricula; ?>">
								<input type="hidden" name="cod_recurso" border=0 value="<?php echo $cod_recurso; ?>">
								<input type="hidden" name="nome" border=0 value="<?php echo $nome; ?>">
							</form>

						<SCRIPT LANGUAGE="JavaScript">
							tela();
						</SCRIPT>
					</BODY>
					</HTML>				
					<?php
					 
					  $_SESSION["opcao"] = "apagou";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        
                                  top.location.href = 'apagar.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
                      		
                      		return;
	} else {

			$_SESSION["opcao"] = "nao";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        
                                  top.location.href = 'apagar.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
                      		
                      		return;

	}


	/*
	foreach($apagar1 as $apa) { 

		echo $apa; echo("<br>");

	} 
	*/

?>
