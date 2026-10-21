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

      <link rel="stylesheet" href="login-files/formoid1/formoid-solid-blue.css"> 
      <script type="text/javascript" src="formoid_files/formoid1/jquery.min.js"></script>
      <link rel="stylesheet" href="css/stil.css" href"css/bootstrap-responsive">

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
        <br>
        <div  align="left">
                    
                    <span class="t_coluna3">Minhas Reservas</span>             
                  
                    <!-- <div class="celebrar_cols"></div> -->
        </div>
                    <!-- style="background-color:white;" -->
                    <center>
              <br>
              <table id = "example" border='1' width="60%" bordercolor="#BEBEBE" class = "table table-bordered table-striped">
                <form >
                  <!-- Cor da celula -->
                <tr bgcolor="#87CEFA">

                  <td  width="40%" align="left" bgcolor="#3498DB"><font size=2 face="arial"><font color='black'>
                    <b><center><?php echo $fonte[4];
                                  echo $cor_fonte["preta"];echo "Data"; ?> </b>
                  </td>
                  <td  width="30%" align="left" bgcolor="#3498DB"><font size=2 face="arial"><font color='black'>
                    <b><center><?php echo $fonte[4];
                                  echo $cor_fonte["preta"];echo "Recurso"; ?> </b>
                  </td>
                  
                  <td  width="30%" align="left" bgcolor="#3498DB"><font size=2 face="arial"><font color='black'>
                    <b><center><?php echo $fonte[4];
                                    echo $cor_fonte["preta"]; echo "Horario"; ?>
                  </td>
                 
                    </b>
                </tr>

                <?php

                $sqlus = "SELECT * from reservas WHERE matricula = '$matricula' AND datatempo > '$tempo_inicio' ORDER BY  datatempo ";
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

                $sqlus = "SELECT * from reservas WHERE matricula = '$matricula'  ORDER BY datatempo DESC";
                  $query = $link->query($sqlus);
                           
                                $result5 = $link->query($sqlus) or die($link->error());
                           
                $ci=0;

                while ($linha_us = $query->fetch_array()) {
                ?>

                <tr bgcolor="FFFFFF">
                  
                  <td align="left"><font size="3" >
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
                        if($dia_da_semana=="Tue"){ echo(" - Terca-feira"); }
                        if($dia_da_semana=="Wed"){ echo(" - Quarta-feira"); }
                        if($dia_da_semana=="Thu"){ echo(" - Quinta-feira"); }
                        if($dia_da_semana=="Fri"){ echo(" - Sexta-feira"); }
                        if($dia_da_semana=="Sat"){ echo(" - Sabado"); }
                      ?>
                  </td>
                  <td align="left"> <font size="3" >
                    <?php 
                    include_once 'verificar.php';
                    $v = new verificar;
                    $codigo_sala = $linha_us["cod_recurso"];
                    $nome=$v->verifica($codigo_sala);
                    echo $nome;
                    
                    ?>
                  </td>
                  <td ><font size="3">
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
          
        </table>
    

      <?php

      } // FIM SALAS


      // -------------------------------------------------------------------------------

    ?>
</body>
</html>
