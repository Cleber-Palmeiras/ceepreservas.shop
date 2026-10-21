<?php 
	if (!isset($_SESSION)) {
    session_start();
   }
	if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sem título</title>
</head>
<body>
<?php
include('languages/padrao.inc.php');
?>
			<link rel="stylesheet" href="login-files/formoid1/formoid-solid-blue.css"> 
			<script type="text/javascript" src="formoid_files/formoid1/jquery.min.js"></script>
			<link rel="stylesheet" href="css/stil.css" href"css/bootstrap-responsive">
			<style type="text/css">

			<!--
			.bordas {
				border: 1px solid #999999;
			}
			-->
			</style>


						<SCRIPT LANGUAGE="JavaScript">
					
						function alterarsala(){
							
							form = document.altsala
							form.action = 'apagar.php'
							form.submit();	
						}

						function envapa(){

							form = document.apagar
							form.action = 'apagar3.php'
							form.submit();
						}




				
						</SCRIPT>
<br>

<?php

			require_once('conecta.php');
			$link = DB_Connect();
			$nome=$_SESSION["nome"];
			$matricula=$_SESSION["cpf"];
			if ($datatempo=isset($_GET["datatempo"]));
			@$cod_sala=$_POST["cod_sala"];
			
			if(($nome)||($cod_sala)){ // INICIO 


								$tempo1 = date("D");
								$dia_da_semana=$tempo1;

								if($dia_da_semana=="Mon"){    $dia_da_semana="Segunda"; $inicio=0;  }
								if($dia_da_semana=="Tue"){   $dia_da_semana="Terça";   $inicio=-1; }
								if($dia_da_semana=="Wed"){ $dia_da_semana="Quarta";  $inicio=-2; }
								if($dia_da_semana=="Thu"){  $dia_da_semana="Quinta";  $inicio=-3; }
								if($dia_da_semana=="Fri"){    $dia_da_semana="Sexta";   $inicio=-4; }
								if($dia_da_semana=="Sat"){  $dia_da_semana="Sábado";  $inicio=-5; }
								if($dia_da_semana=="Sun"){    
								$dia_da_semana="Domingo";
								if ($da=isset($dia)+1); 
								if ($dia=isset($da)); 
								$inicio=0; 
								$inicio2=0; 
								} //CASO SEJA DOMINGO


								$tempo=date("Y-m-d");

								$tempo=explode("-",$tempo);

								$ano=$tempo[0];
								$mes=$tempo[1];
								$dia=$tempo[2];

								$dia_inicio=$dia;
								$desconto=13+$inicio;
								$dia_final=$dia_inicio+$desconto;

								$tempo_inicio=$ano."-".$mes."-".$dia_inicio." 08:00:00";





					$marc=0;
					if(($mes==1)||($mes==3)||($mes==5)||($mes==7)||($mes==8)||($mes==10)||($mes==12)){

						if($dia_final>=32){
							$mes++;
							if($mes==12){ $ano++; $mes=1; }
							if($mes<10){ $mes="0".$mes; }
							$marc=1;
							$dia_final=$dia_final-31;
							
						}
						if($marc!=1){
							if($dia_final<=0){
								$dia_final=31+$inicio;
								if($mes==3){ $dia_final=28+$inicio; } 
								$mes--;
								if($mes<10){ $mes="0".$mes; }
								$marc=1;
							}
						}

					}

					if($marc!=1){
						if(($mes==4)||($mes==6)||($mes==9)||($mes==11)){
							if($dia_final>=31){
								$marc=1;
								$dia_final=$dia_final-30;
							
							}
							if($marc!=1){
								if($dia_final<=0){
									$dia_final=30+$inicio;
									$mes--;
									if($mes<10){ $mes="0".$mes; }
									$marc=1;
								}
							}
						}
					}

					if($marc!=1){
						if($mes==2){
							if($dia_final>=29){
								$mes++;
								if($mes<10){ $mes="0".$mes; }
								$marc=1;
								$dia_final=$dia_final-28;
							}
							if($dia_final<=0){
								$dia_final=30+$inicio;
								$mes--;
								if($mes<10){ $mes="0".$mes; }
								$marc=1;
							}
						}
					}

					if($dia_final<10){ $dia_final="0".$dia_final; }

					$tempo_final=$ano."-".$mes."-".$dia_final." 21:00:00";

					$tempo_final2=$ano."-".$mes."-".$dia_final;


					// echo date("D",$tempo_final2);

 // TAMANHO, FONTE DOS DIAS. 
        $fonte[1] = "<font face='times new roman' size='2'>";
        $fonte[2] = "<font face='arial' size='2'><center>";
        $fonte[3] = "<font face='arial' size='3'><center>";
        $fonte[4] = "<font face='arial' size='3'><center>";
        $cor_fonte["azul"] = "<font color='black'>";
        $cor_fonte["az"] = "<font color='black'>";
        $cor_fonte["new"] = "<font color='#3498DB'>";
        $cor_fonte["branca"] = "<font color='#ffffff'>";
        $cor_fonte["preta"] = "<font color='#000000'>";
			?>
			<br>
			<fieldset>
        <legend><h2> <b> Tela cancelar reserva </b></h2></legend>

				
							<br>
							<!-- Cor da tabela --> 
							

							<table border='1' bordercolor=blue width='100%'  id="example">
								<form name="apagar" method="post" action="apagar3.php">
									<!-- Cor da celula -->
								<tr bgcolor="#87CEFA">

									<td bgcolor="#3498DB" width="20%" align="center"><font size=2 face="arial">

										<center>
										<font color='black'>
										<input type="hidden" name="matricula" value="<?php echo $matricula; ?>">
										<input type="hidden" name="cod_sala" value="<?php echo $cod_sala; ?>">
										<input type="hidden" name="nome" value="<?php echo $nome; ?>">
										<b ><?php echo $fonte[4];

                      				    echo $cor_fonte["preta"]; Echo "Selecione para excluir"; ?>
										
									</td>
									<td  width="50%" align="left" bgcolor="#3498DB"><font size=2 face="arial"><font color='black'>
										<b><center><?php echo $fonte[4];
                      				    echo $cor_fonte["preta"];echo "Data"; ?>
									</td>
									<td  width="45%" align="left" bgcolor="#3498DB"><font size=2 face="arial"><font color='black'>
										<b><center><?php echo $fonte[4];
                      				    echo $cor_fonte["preta"]; echo "Horario"; ?>
									</td>
								</tr>

								<?php

								
								$sqlus = "SELECT * from reservas WHERE matricula = '$matricula' AND datatempo >= '$tempo_inicio' ORDER BY datatempo ";
							    $query = $link->query($sqlus);
                           
                                $result5 = $link->query($sqlus) or die($link->error());
                           
								$ci=0;
								if (($query->fetch_array()=="")and($cod_sala!="")){ ?>

									<tr bgcolor="<?php if($ci==0){ echo("FFFFFF"); $ci=1; } else { echo("#F8F8FF"); $ci=0; } ?>">
									<td align="center"><font size=4 face="arial">
										<center>

									</td>
									
									<td align="left"><font size=2 face="arial" >
										<center><?php

												echo ("<br> Nenhuma reserva para este recurso </br>");

											?>
									</td>

									<td align="left"><font size=1 face="arial">
										
									</td>

									</tr>
								<?php 
								}
								else {

								$sqlus = "SELECT * from reservas WHERE matricula = '$matricula' AND datatempo >= '$tempo_inicio' ORDER BY datatempo ";
							    $query = $link->query($sqlus);
                           
                                $result5 = $link->query($sqlus) or die($link->error());
                           
								$ci=0;

								while ($linha_us = $query->fetch_array()) {
								?>

								<tr bgcolor="#<?php if($ci==0){ echo("FFFFFF"); $ci=1; } else { echo("F5F5F5"); $ci=0; } ?>">
									<td align="center"><font size=3 face="arial">
										<center>

										<input type="checkbox" name="apagarc[]" value="<?php echo $linha_us["datatempo"]; ?>">


									</td>
									<td align="left"><font size=2 face="arial" >
										<center><?php

												$tempo4=$linha_us["datatempo"];
												$tempo4=explode(" ",$tempo4);
												$data=explode("-",$tempo4[0]);
												$hora=explode(":",$tempo4[1]);
												
												echo $data[2];
												echo("/");
												echo $data[1];
												echo("/");
												echo $data[0];

												$dia_da_semana=date("D",mktime(0, 0, 0, $data[1], $data[2], $data[0]));
	
												if($dia_da_semana=="Sun"){ echo(" Domingo"); }
												if($dia_da_semana=="Mon"){ echo(" Segunda-feira"); }
												if($dia_da_semana=="Tue"){ echo(" Terca-feira"); }
												if($dia_da_semana=="Wed"){ echo(" Quarta-feira"); }
												if($dia_da_semana=="Thu"){ echo(" Quinta-feira"); }
												if($dia_da_semana=="Fri"){ echo(" Sexta-feira"); }
												if($dia_da_semana=="Sat"){ echo(" Sabado"); }


											?>
									</td>
									<td align="left"><font size=2 face="arial">
										<center><?php echo $hora[0]; echo(":"); echo $hora[1]; ?>
										<?php 
											if ($hora[0]==8){
												$hor_fim = 12; 
												echo " - " ,$hor_fim, ":" ,$hora[1] ;
											}
											else if ($hora[0]==14){
												$hor_fim = 18;
												echo " - " ,$hor_fim, ":" ,$hora[1] ;
											}
											else if ($hora[0]==18){
												$hor_fim=22;	
												echo " - " ,$hor_fim, ":" ,$hora[1] ;
											}

										?>
									</td>

								</tr>

								<?php

								} // FIM WHILE_US
								}
								?>

								</form>
								
							</table>

							<br>
							
						</td>

					</tr>
					<TD width="3%" class="sair" >
		  			<P style="MARGIN-TOP: -20px; MARGIN-BOTTOM: 0px"><SPAN 
		             class=box-header>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		             <INPUT class=frm onmouseover="this.className = 'frm-on';" 
		             onmouseout="this.className = 'frm';" 
		             type="submit" name="enviar" value="Excluir" onClick="javascript:envapa();"></SPAN>

				</table>
		

			<?php

			} // FIM SALAS


			// -------------------------------------------------------------------------------


			if ($edsala=isset($_GET["edsala"]));

			if($edsala){ // INICIO ED_SALA

				$nova=$_GET["nova"];

				if($nova=="sim"){

					echo("");

				} else {

				$sql = "SELECT * from salas WHERE cod_sala = $edsala";
				$result = mysql_query($sql) or die(mysql_error());
				$linha=mysql_fetch_array($result);

				}
	
			?>

			

			<?php
	
			} // FIM NOVO_SALA


			// ------------------------------------------------------------

	// ----------------------------------------------------------- 

		if ($edsala_env=isset($_POST["edsala_env"]));

		if($edsala_env){ // INICIO SALA_ENV

			$nome=$_POST["nome"];
			$cod_sala=$_POST["cod_sala"];

			$sql = "UPDATE salas SET nome_sala='$nome',cod_sala='$cod_sala' WHERE cod_sala = $cod_sala";

			if(mysql_query($sql)){

				echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ alert('Sala Atualizada'); window.navigate('salas.php?salas=sim'); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
			} else {
				echo ("<i><b><font face=\"arial,verdana\" size=\"-1\">SALA NAO ATUALIZADA. CONTATE O SUPORTE LOCAL!!!</i><br> ERRO MySQL:</b> ". mysql_error());
			}

		} // FIM SALA_ENV	

		// CONTINUAR AQUI


		// -----------------------------------------------------------

		if ($edsala_nova=isset($_POST["edsala_nova"]));

		if($edsala_nova){ // INICIO SALA_NOVA

			$nome=$_POST["nome"];

			$sql = "INSERT INTO salas (nome_sala) VALUES ('$nome')";

			if(mysql_query($sql)){

				echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ alert('Sala Adicionada'); window.navigate('salas.php?salas=sim'); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
			} else {
				echo ("<i><b><font face=\"arial,verdana\" size=\"-1\">SALA NAO ADICIONADA. CONTATE O SUPORTE LOCAL!!!</i><br> ERRO MySQL:</b> ". mysql_error());
			}

		} // FIM SALA_NOVA	


		// ----------------------------------------------------------- USUÁRIOS


		if($datatempo){ // INICIO APAGAR

			// $confirmado=$_GET["confirmado"];

			$matricula=$_GET["cpf"];
			$cod_sala=$_GET["cod_sala"];

			mysql_select_db($banco);

			$sql1 = "SELECT * from sups WHERE matsup = $matricula";
			$result1 = mysql_query($sql1) or die(mysql_error());
			$linha1=mysql_fetch_array($result1);

			$nome=$linha1["nome_sup"];

				mysql_select_db($banco);

				$sql = "DELETE FROM reservas WHERE datatempo = '$datatempo' AND matricula = '$matricula'";
			
				if(mysql_query($sql)){

					?>
					<HTML>
					<HEAD>
						<SCRIPT LANGUAGE="JavaScript">
					
						function tela(){
		
							alert('Agendamento apagado!')
							form = document.verifica
							form.action = 'apagar.php'
							form.submit();	
						}
						
						</SCRIPT>
					<HEAD>
					<BODY>
					
							<form name="verifica" method="post" action="apagar.php">
								<input type="hidden" name="matricula" border=0 value="<?php echo $matricula; ?>">
								<input type="hidden" name="nome" border=0 value="<?php echo $nome; ?>">
							</form>

						<SCRIPT LANGUAGE="JavaScript">
							tela();
						</SCRIPT>
					</BODY>
					</HTML>				
					<?php

					exit();

				} else {
					echo ("<i><b><font face=\"arial,verdana\" size=\"-1\">CONTATE O SUPORTE LOCAL!!!</i><br> ERRO MySQL:</b> ". $link->error());
				}
	

		} // FIM APAGAR
		?>
</body>
</html>
