<script src="sweetalert-dev.min.js"/> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />
<?php 
	if (!isset($_SESSION)) {
    session_start();
   }
	if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

// Exibir Mensagem de vazia 
	if (isset($_SESSION["opcao"])){
	if ($_SESSION["opcao"] == "vazia"){
		
		   echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Por favor, selecione uma opção!',
                                type:''
                                },
                                function(){
                                  
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
		}

// Exibir Mensagem que excluiu. 
	if ($_SESSION["opcao"] == "apagou"){

	echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                       swal ({
                                title:'',
                                text:'Reserva(as) cancelada(as)',
                                type:'success'
                                },
                                function(){
                                 
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");

		}

	if ($_SESSION["opcao"] == "nao"){
			echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                       swal ({
                                title:'',
                                text:'Erro ao excluir, tente novamente!',
                                type:'error'
                                },
                                function(){
                                 
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
		}

		$_SESSION["opcao"] = "0";
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sem título</title>
</head>
<body>

			<link rel="stylesheet" href="login-files/formoid1/formoid-solid-blue.css"> 
			<script type="text/javascript" src="formoid_files/formoid1/jquery.min.js"></script>
			<link rel="stylesheet" href="css/stilo.css"> 

						<SCRIPT LANGUAGE="JavaScript">
					

						function marcardesmarcar(){
							   if ($("#todos").attr("checked")){
							      $('.marcar').each(
							         function(){
							            $(this).attr("checked", true);
							         }
							      );
							   }else{
							      $('.marcar').each(
							         function(){
							            $(this).attr("checked", false);
							         }
							      );
							   }
							}

						function alterarrecurso(){
							
							form = document.altrecurso
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
			$matricula=$_SESSION["matricula"];
			if ($datatempo=isset($_GET["datatempo"]));
			@$cod_recurso=$_POST["cod_recurso"];
			
			if(($nome)||($cod_recurso)){ // INICIO 


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

								date_default_timezone_set('America/Sao_Paulo');
								$tempo=date("Y-m-d");

								$tempo=explode("-",$tempo);
								$horaso = date("H");
								$ano=$tempo[0];
								$mes=$tempo[1];
								$dia=$tempo[2];

								$dia_inicio=$dia;
								$desconto=13+$inicio;
								$dia_final=$dia_inicio+$desconto;

								$tempo_inicio=$ano."-".$mes."-".$dia_inicio." ".$horaso.":00:00";





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
        $fonte[1] = "<font face='helvetica' size='2'>";
        $fonte[2] = "<font face='helvetica' size='2'>";
        $fonte[3] = "<font face='helvetica' size='3'>";
        $fonte[4] = "<font face='helvetica' size='4.5'>";
        $cor_fonte["azul"] = "<font color='black'>";
        $cor_fonte["az"] = "<font color='black'>";
        $cor_fonte["new"] = "<font color='#3498DB'>";
        $cor_fonte["branca"] = "<font color='#ffffff'>";
        $cor_fonte["preta"] = "<font color='#000000'>";
        echo $fonte[4];
			?>
		
        <div >
                    
                    <span class="t_coluna3">Cancelar Reservas</span>             
                  
                    <!-- <div class="celebrar_cols"></div> -->
        </div>
                    <!-- style="background-color:white;" -->

   
                    <!-- style="background-color:white;" -->

				<table width="55%" align="center">
					<tr align="center">	
						<td align="center" >
							<table width="100%">
								<form name="altrecurso" method="post" action="apagar2.php">
								<tr>	
									<td>
									
									<?php 
										echo $fonte[3];
                      				    echo $cor_fonte["preta"];
                      				    echo("<b>");
										echo "Recurso";
										echo("</b>");
										?> &nbsp;

										<?php

											$link = DB_Connect();

											mysqli_select_db($link,'tcc');

											$sql = "SELECT * from recursos ORDER BY nome_recurso";

											$query = $link->query($sql);
                           
                          				    $resultado = $link->query($sql) or die($link->error());
										

										?>
										<label>
										<select name="cod_recurso" OnChange="javascript: alterarrecurso();">
										<option value=> Todos</option>
										<?php
										 while ($linha_sup = $query->fetch_array()) {
										
										?>
											<option value="<?php echo $linha_sup["cod_recurso"]; ?>" 
											<?php if($linha_sup["cod_recurso"]==$cod_recurso){ 
												echo("selected"); 
												$nome_recurso=$linha_sup["nome_recurso"]; 
											} 
											?> >
											<?php echo $linha_sup["nome_recurso"]; ?> </option>
										<?php
										}
										?>
										</select>
										<input type="hidden" name="nome" value="<?php echo $nome; ?>">
										<input type="hidden" name="matricula" value="<?php echo $matricula; ?>">
									</td>
								</tr>
								</form>
							</table>
							<br>
							<!-- Cor da tabela --> 
							

							<table border='1' bordercolor="#BEBEBE" width='100%'  id="example">
								<form name="apagar" method="post" action="apagar3.php">
									<!-- Cor da celula -->
								<tr bgcolor="#87CEFA">

									<td bgcolor="#3498DB" width="5%" align="center"><font size=2 face="arial">

										<center>
										<font color='black'>
										<input type="hidden" name="matricula" value="<?php echo $matricula; ?>">
										<input type="hidden" name="cod_recurso" value="<?php echo $cod_recurso; ?>">
										<input type="hidden" name="nome" value="<?php echo $nome; ?>">
										<b ><?php echo $fonte[4];

                      				     ?>
                      				     <input type="checkbox" name="todos" id="todos" value="todos" onclick="marcardesmarcar();" />
										
									</td>

									<td  width="25%" align="left" bgcolor="#3498DB"><font size=3 face="arial"><font color='black'>
										<b><center><?php echo $fonte[4];
                      				    echo $cor_fonte["preta"];echo "Recurso"; ?>
									</td>

									<td  width="25%" align="left" bgcolor="#3498DB"><font size=3 face="arial"><font color='black'>
										<b><center><?php echo $fonte[4];
                      				    echo $cor_fonte["preta"];echo "Data"; ?>
									</td>

									<td  width="25%" align="left" bgcolor="#3498DB"><font size=3 face="arial"><font color='black'>
										<b><center><?php echo $fonte[4];
                      				    echo $cor_fonte["preta"]; echo "Horario"; ?>
									</td>
								</tr>

								<?php

								if ($cod_recurso==""){
									$sqlus = "SELECT * from reservas WHERE matricula = '$matricula' AND datatempo >= '$tempo_inicio' ORDER BY cod_recurso, datatempo ";	
								}

								else {
									$sqlus = "SELECT * from reservas WHERE matricula = '$matricula' AND datatempo >= '$tempo_inicio' AND cod_recurso = '$cod_recurso' ORDER BY cod_recurso, datatempo ";
							    }
							    $query = $link->query($sqlus);
                           
                                $result5 = $link->query($sqlus) or die($link->error());
                           
								$ci=0;
								$cont = 0 ;
								if (($query->fetch_array()=="")and($cod_recurso!="")){ ?>

									<tr bgcolor="FFFFFF">
									

									</tr>
								<?php 
									
								}
								
								else {

								if ($cod_recurso==""){
									$sqlus = "SELECT * from reservas WHERE matricula = '$matricula' AND datatempo >= '$tempo_inicio' ORDER BY cod_recurso, datatempo ";	
								}

								else {
									$sqlus = "SELECT * from reservas WHERE matricula = '$matricula' AND datatempo >= '$tempo_inicio' AND cod_recurso = '$cod_recurso' ORDER BY cod_recurso, datatempo ";
							    }
							    $query = $link->query($sqlus);
                           
                                $result5 = $link->query($sqlus) or die($link->error());
                           
								$ci=0;

								while ($linha_us = $query->fetch_array()) {
								?>

								<tr bgcolor="FFFFFF">
									<td align="center"><font size=3 face="arial">
										<center>

										<input type="checkbox" class="marcar"  name="apagarc[]" value="<?php echo $linha_us["ID"]; ?>">

									</td>

									<td align="left"><font face="arial" size="3">
										<?php 
										include_once 'verificar.php';
										$v = new verificar;
										$codigo_recurso = $linha_us["cod_recurso"];
										$nome=$v->verifica($codigo_recurso);
										
										echo $nome;
										
										?>
										

									</td>

									<td align="left"><font size=3 face="arial" >
										<?php

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
	
												if($dia_da_semana=="Sun"){ echo(" - Domingo"); }
												if($dia_da_semana=="Mon"){ echo(" - Segunda-feira"); }
												if($dia_da_semana=="Tue"){ echo(" - Terça-feira"); }
												if($dia_da_semana=="Wed"){ echo(" - Quarta-feira"); }
												if($dia_da_semana=="Thu"){ echo(" - Quinta-feira"); }
												if($dia_da_semana=="Fri"){ echo(" - Sexta-feira"); }
												if($dia_da_semana=="Sat"){ echo(" - Sábado"); }


											?>
									</td>
									<td align="left"><font size=3 face="arial">
										<center>
										<?php 
											$hora = $hora[0] ;	
										if ($hora == 7){
											echo "1° hórário - M";
											
										} else 
										if ($hora == 8){
											echo "2° hórário - M";	
											
										} else
										if ($hora == 9){
											echo "3° hórário - M";	
											
										} else
										if ($hora == 10){
											echo "4° hórário - M";	
											
										} else
										if ($hora == 11){
											echo "5° hórário - M";	
											
										} else
										if ($hora == 12){
											echo "6° hórário - M";
											
										} else
										if ($hora == 13){
											echo "1° hórário - T";	
											
										} else
										if ($hora == 14){
											echo "2° hórário - T";
											
										} else
										if ($hora == 15){
											echo "3° hórário - T";	
											
										} else
										if ($hora == 16){
											echo "4° hórário - T";	
											
										} else
										if ($hora == 17){
											echo "5° hórário - T";	
											
										} else
										if ($hora == 18){
											echo "6° hórário - T";	
											
										} else
										if ($hora == 19){
											echo "1° hórário - N";	
											
										}
										else
										if ($hora == 20){
											echo "2° hórário - N";	
											
										}
										else
										if ($hora == 21){
											echo "3° hórário - N";	
											
										}
										else
										if ($hora == 22){
											echo "4° hórário - N";	
											
										}

										?>
									</td>

								</tr>

								<?php
								$cont++;
								} // FIM WHILE_US

								}
								?>

								</form>
								
							</table>
							<?php 
							if ($cont==0){
								echo $fonte[4];
                      		    echo $cor_fonte["preta"];
                      		    echo "<br>";
                      		    echo "<b>";
								echo "Nenhuma reserva para listar.";
								echo "</b>";
								}; 
							?>
							<br>
							
						</td>
					</tr>

					<TD width="30%" class="cancelar" align="right">
		  			<P style="MARGIN-TOP: -20px; MARGIN-BOTTOM: 0px" ><SPAN 
                    class=box-header>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		             <INPUT class=frm onmouseover="this.className = 'frm-on';" 
                           onmouseout="this.className = 'frm';" 
                           type="submit"  name="enviar" value="Cancelar" onClick="javascript:envapa();"></SPAN>

				</table>
		

			<?php

			} // FIM SALAS

		?>
</body>
</html>
