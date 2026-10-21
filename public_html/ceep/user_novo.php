
<?php
if (!isset($_SESSION)) {
    session_start();
}
if (empty($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
if (isset($_SESSION["opcao"])){

    if ($_SESSION["opcao"] == "vazia"){
        
           echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Por favor, selecione um horário!',
                                type:''
                                },
                                function(){
                                  
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        }
        else 
    if ($_SESSION["opcao"] == "reservou"){

 echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Reserva efetuada com sucesso',
                                type:'success'
                                },
                                function(){
                                  
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
    }
    else 
     if ($_SESSION["opcao"] == "erro"){
            echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Ops, houve um erro. Tente novamente!',
                                type:'error'
                                },
                                function(){
                                  
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        }
    else
        if ($_SESSION["opcao"] == "erro2"){
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Ops, este recurso já está reservado por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        
        }
    $_SESSION["opcao"] = "0";
}

?>
 
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
<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
    <?php
    require_once('conecta.php');
    $link = DB_Connect();

    function mostrarsemana($data, $cod_recurso, $qual_semana, $prox_semana, $dia_atual) { // INICIO_FUNCTION_MOSTRARSEMANA
        
        ?>
        <script Language="JavaScript">
            function alterarrecurso() {

                form = document.recurso
                form.action = ''
                form.submit();

            }
        </script>

        <script Language="JavaScript">
            function alterartipo() {

                form = document.categoria
                form.action = ''
                form.submit();

            }
        </script>

        <script Language="JavaScript">
            function alterarturno() {

                form = document.turno
                form.action = ''
                form.submit();

            }
        </script>

        <script Language="JavaScript">

            function valida() {
                form = document.sas
                reservar = form.reservar

                marcado = -1

                for (i = 0; i < form.reservar.length; i++) {
                    if (document.sas.reservar[i].checked) {
                        marcado = i
                    }
                }

                if (marcado == -1) {

                    form.action = 'mensagem.php'

                } else {

                    marcado2 = -1
                    form.action = '?enviar=sim'
                    form.submit();
                }

            }
        </script> 
  
        <div id="overDiv" style="position:absolute; visibility:hide; z-index:1;"></div>
        <script language="JavaScript" src="overlib.js" type="text/javascript"></script>
        <?php
        
        $data1 = $data;
        $data_prox = $data;
        $data2 = $data;
        $data = explode("-", $data);
        
        $fgh = explode("-", $dia_atual);
        $mesfgh = $fgh[1];
        $dia = $data[2];
        $dia2 = $dia;
        $mes = $data[1];
        $mesd = $data[1];
        $ano = $data[0];
        $data_sem = $ano . "-" . $mes . "-" . $dia;
        $ano2 = $ano;
        $mes2 = $mes;
        $novomes = 0;
        $novomes2 = 0;
        date_default_timezone_set('America/Sao_Paulo');
        $tempo = date("w", mktime(0, 0, 0, $mes, $dia, $ano));
        $dia_da_semana2 = $tempo;

        if ($dia_da_semana2 == 1) {
            $dia_da_semana = "SEGUNDA FEIRA";
            $inicio = -1;
            $inicio2 = 1;
            $iniciok = 1;
            $inicio_prox = 8 /* 8 */;
        }
        if ($dia_da_semana2 == 2) {
            $dia_da_semana = "TERCA FEIRA";
            $inicio = -2;
            $inicio2 = -2;
            $iniciok = 2;
            if ($dia == 1) {
                $inicio6 = 0;
            } $inicio_prox = 9;
        }
        if ($dia_da_semana2 == 3) {
            $dia_da_semana = "QUARTA FEIRA";
            $inicio = -3;
            $inicio2 = -3;
            $iniciok = 3;
            if ($dia == 1) {
                $inicio6 = -1;
            } if ($dia == 2) {
                $inicio6 = 0;
            } $inicio_prox = 10;
        }
        if ($dia_da_semana2 == 4) {
            $dia_da_semana = "QUINTA FEIRA";
            $inicio = -4;
            $inicio2 = -4;
            $iniciok = 4;
            if ($dia == 1) {
                $inicio6 = -2;
            } if ($dia == 2) {
                $inicio6 = -1;
            } if ($dia == 3) {
                $inicio6 = 0;
            } $inicio_prox = 11;
        }
        if ($dia_da_semana2 == 5) {
            $dia_da_semana = "SEXTA FEIRA";
            $inicio = -5;
            $inicio2 = -5;
            $iniciok = 5;
            if ($dia == 1) {
                $inicio6 = -3;
            } if ($dia == 2) {
                $inicio6 = -2;
            } if ($dia == 3) {
                $inicio6 = -1;
            } if ($dia == 4) {
                $inicio6 = 0;
            } $inicio_prox = 12;
        }
        if ($dia_da_semana2 == 6) {
            $dia_da_semana = "SABADO";
            $inicio = -6;
            $inicio2 = -6;
            $iniciok = 6;
            if ($dia == 1) {
                $inicio6 = -4;
            } if ($dia == 2) {
                $inicio6 = -3;
            } if ($dia == 3) {
                $inicio6 = -2;
            } if ($dia == 4) {
                $inicio6 = -1;
            } $inicio_prox = 13;
        }
        if ($dia_da_semana2 == 0) {
            $dia_da_semana = "DOMINGO";
            //$dia++;
            $inicio = 0;
            $inicio2 = 0;
            $iniciok = 1;
            $inicio_prox = 14;
        } //CASO SEJA DOMINGO


        $nomes_meses["01"] = "Janeiro";
        $nomes_meses["02"] = "Fevereiro";
        $nomes_meses["03"] = "Março";
        $nomes_meses["04"] = "Abril";
        $nomes_meses["05"] = "Maio";
        $nomes_meses["06"] = "Junho";
        $nomes_meses["07"] = "Julho";
        $nomes_meses["08"] = "Agosto";
        $nomes_meses["09"] = "Setembro";
        $nomes_meses["10"] = "Outubro";
        $nomes_meses["11"] = "Novembro";
        $nomes_meses["12"] = "Dezembro";

        $data_atual78 = explode("-", $dia_atual);

        $dia78 = $data_atual78[2];
        $mes78 = $data_atual78[1];
        $ano78 = $data_atual78[0];

        $tempo78 = date("D", mktime(0, 0, 0, $mes78, $dia78, $ano78));

        $horaagora05 = date("H");

        $inicio3 = $inicio;
        $inicio4 = $inicio;

        // DIAS DA SEMANA . 
        $nome_semana[1] = "Domingo";
        $nome_semana[2] = "Segunda";
        $nome_semana[3] = "Terça";
        $nome_semana[4] = "Quarta";
        $nome_semana[5] = "Quinta";
        $nome_semana[6] = "Sexta";
        $nome_semana[7] = "Sábado";

        $fonte[1] = "<font face='arial' size='1'>";
        $fonte[2] = "<font face='helvetica' size='2'><center>";
        $fonte[3] = "<font face='helvetica' size='3'><center>";
        $fonte[4] = "<font face='helvetica' size='3'><center>";
        $fonte[5] = "<font face='helvetica' size='3' style='float:right;'>";
        $fonte[10] = "<font face='helvetica' size='5'><center>";
        $cor_fonte["azul"] = "<font color='black'>";
        $cor_fonte["az"] = "<font color='black'>";
        $cor_fonte["azulverdade"] = "<font color='blue'>";
        $cor_fonte["branca"] = "<font color='#ffffff'>";
        $cor_fonte["preta"] = "<font color='#000000'>";
        $cor_fonte["red"] = "<font color='red'>";
        
        // horas
        $total_horas = 18; // TOTAL HORAS + 1
        $tamanho_celula = 200; // LARGURA CELULAS
        $altura_celula = ""; // ALTURA CELULAS

        echo $fonte[4];
        ?>
        <br>
        
        <div >
                    
                    <span class="t_coluna3">Reservar</span>             
                  
                    <!-- <div class="celebrar_cols"></div> -->
        </div>
                    <!-- style="background-color:white;" -->

        <br>
        <!--<legend> <b> Efetuar Reserva </b></legend> -->

        <?php 
        echo $fonte[3];
        echo $cor_fonte["preta"];  ?>

        <table border='0' bordercolor=blue width='70%'> 
            <TR>
                <TD class="bl1">
                    <table border='0' bordercolor="#BEBEBE" width='60%' align="center" >
                        <FORM method="POST"  action="" name="turno">
                            <TD >
                                
                                <?php
                                echo $fonte[4];
                                echo $cor_fonte["az"];
                                echo("<b>");
                                echo "Turno ";   
                                //echo $_SESSION["categoria"];

                                ?>

                                <label>
                                    <select class="form-control"  name="turno" OnChange="javascript:alterarturno(); ">
                                            
                                            <option value="1" <?php
                                                        if ($_SESSION["turno"] == "1") {
                                                            echo ("selected=\"\"");
                                                        }
                                                        ?> 
                                                        >
                                                    <?php echo "Manhã"; ?></option>
                                            <option value="2" <?php
                                                        if ($_SESSION["turno"] == "2") {
                                                            echo ("selected=\"\"");
                                                        }
                                                        ?> 
                                                        >
                                                    <?php echo "Tarde"; ?></option> 
                                            <option value="3" <?php
                                                        if ($_SESSION["turno"] == "3") {
                                                            echo ("selected=\"\"");
                                                        }
                                                        ?> 
                                                        >
                                                    <?php echo "Noite"; ?></option>                                      
                                                                                                   
                                    </select>
                                 </TD>
                        </FORM>

                        <FORM method="POST"  action="" name="categoria">
                            <TD >
                                
                                <?php
                                echo $fonte[4];
                                echo $cor_fonte["az"];
                                echo("<b>");
                                echo "Categoria ";   
                                //echo $_SESSION["categoria"];

                                ?>

                                <label>
                                    <select class="form-control"  name="categoria" OnChange="javascript:alterartipo(); ">
                                            
                                            <option value="1" <?php
                                                        if ($_SESSION["categoria"] == "1") {
                                                            echo ("selected=\"\"");
                                                        }
                                                        ?> 
                                                        >
                                                    <?php echo "Datashow"; ?></option>
                                            <option value="2" <?php
                                                        if ($_SESSION["categoria"] == "2") {
                                                            echo ("selected=\"\"");
                                                        }
                                                        ?> 
                                                        >
                                                    <?php echo "Laboratório"; ?></option> 
                                            <option value="3" <?php
                                                        if ($_SESSION["categoria"] == "3") {
                                                            echo ("selected=\"\"");
                                                        }
                                                        ?> 
                                                        >
                                                    <?php echo "Sala"; ?></option>                                      
                                            <option value="4" <?php
                                                        if ($_SESSION["categoria"] == "4") {
                                                            echo ("selected=\"\"");
                                                        }
                                                        ?> 
                                                        >
                                                    <?php echo "Outros"; ?></option>                                                                           
                                    </select>
                                 </TD>
                        </FORM>

                        <FORM method="POST" action="" name="recurso" >

                            <TD >
                                <input type="hidden" name="env_cod_recurso" border=0 value="Alterar Recurso" >
                                <input border="0" type="hidden" name="inicio1" border=0 value="<?php echo $inicio; ?>">
                                <input border="0" type="hidden" name="prox_semana" border=0 value="<?php echo $prox_semana; ?>">
                                <input border="0" type="hidden" name="dia_atual" border=0 value="<?php echo $dia_atual; ?>">
                                <input border="0" type="hidden" name="categoria" border=0 value="<?php echo $_SESSION["categoria"]; ?>">
                                <?php
                                echo $fonte[4];
                                echo $cor_fonte["az"];
                                echo("<b>");
                                echo "Recurso ";

                                ?>

                                <label>
                                    <select class="form-control" name="cod_recurso" OnChange="javascript:alterarrecurso(); ">

                                        <?php
                                        $_SESSION["qualsemana"] = $qual_semana;
                                        $link = DB_Connect();

                                        mysqli_select_db($link, 'tcc');
                                        $categoria = $_SESSION["categoria"];

                                        $sql5 = "SELECT * from recursos WHERE status='Disponivel' and categoria = '$categoria' ORDER BY nome_recurso";
                                        
                                        $query = $link->query($sql5) or die($link->error());
                                        $teste = 0;
                                        while ($linha5 = $query->fetch_array()) {

                                            
                                            if (($teste == 0) and ($_SESSION["teste"] != $_SESSION["categoria"])){
                                                $cod_recurso = $linha5["cod_recurso"];
                                                $_SESSION["cod_recurso"] = $cod_recurso;
                                            }
                                            
                                            ?>
                                            <option value="<?php
                                            if (($linha5["status"] == "Disponivel") and ($linha5["categoria"] == $_SESSION["categoria"])) {
                                                echo $linha5["cod_recurso"];
                                                ?>" 
                                                        <?php
                                                        if ($cod_recurso == $linha5["cod_recurso"]) {
                                                            echo ("selected=\"\"");
                                                            
                                                        }
                                                        ?> 
                                                        >
                                                    <?php echo $linha5["nome_recurso"]; ?></option>
                                                <?php
                                                $teste++;
                                                $_SESSION["teste"] = $categoria;
                                            }
                                        }
                                        if ($teste == 0){
                                            $cod_recurso = 0;
                                            echo "Não tem recurso cadastrado";
                                        }
                                        echo("</b>");
                                        ?>
                                    </select>
                                 </TD>
                        </FORM>

                    </table>
                    </TD>

                <?php
                    if ($qual_semana == 1){ ?>
                       
                    
               <?php } 
                    
                    else {
                    ?>
                <form method="POST" action="" name="anterior">
                  
                    <input border="0" type="hidden" name="cod_recurso" border=0 value="<?php echo $cod_recurso; ?>">
                    <input border="0" type="hidden" name="data1" border=0 value="<?php echo $data_prox; ?>">
                    <input border="0" type="hidden" name="inicio1" border=0 value="<?php echo $inicio; ?>">
                    <input border="0" type="hidden" name="prox_semana" border=0 value="<?php echo $prox_semana; ?>">
                    <input border="0" type="hidden" name="dia_atual" border=0 value="<?php echo $dia_atual; ?>">
                    <input border="0" type="hidden" name="categoria" border=0 value="<?php echo $_SESSION["categoria"]; ?>">
                    <TD width="2%" class="adm">

                        <P style="MARGIN-TOP: -20px; MARGIN-BOTTOM: 0px" align="center"><SPAN 
                                class=box-header>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                
                                <INPUT class=frm onmouseover="this.className = 'frm-on';" 
                                       onmouseout="this.className = 'frm';" 
                                       type=submit name="anterior" value="<< Semana" ></SPAN>
                               
                   </TD>
                </form>
               <?php } 
                if ($qual_semana == 2){
               ?>
                    
                
               <?php } 

                else { 
            ?>
            <form method="POST" action="" name="proxima">
                   
                    <input border="0" type="hidden" name="cod_recurso" border=0 value="<?php echo $cod_recurso; ?>">
                    <input border="0" type="hidden" name="data1" border=0 value="<?php echo $data1; ?>">
                    <input border="0" type="hidden" name="inicio1" border=0 value="<?php echo $iniciok; ?>">
                    <input border="0" type="hidden" name="inicio_prox" border=0 value="<?php echo $inicio_prox; ?>">
                    <input border="0" type="hidden" name="prox_semana" border=0 value="<?php echo $prox_semana; ?>">
                    <input border="0" type="hidden" name="dia_atual" border=0 value="<?php echo $dia_atual; ?>">
                    <input border="0" type="hidden" name="categoria" border=0 value="<?php echo $_SESSION["categoria"]; ?>">
                    <TD width="3%" class="adm">
                        <P style="MARGIN-TOP: -20px; MARGIN-BOTTOM: 0px" align="center"><SPAN 
                                class=box-header>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                <INPUT class=frm onmouseover="this.className = 'frm-on';" 
                                       onmouseout="this.className = 'frm';" 
                                       type=submit name="proxima" value="Semana >>"></SPAN>
                   </TD>
                </form>
                <?php } ?> 

            </TR>
            </table>
    <br>
    <?php if($cod_recurso!=0){ ?>
    <table border='1' bordercolor="#BEBEBE" class = "table table-bordered table-striped" width='70%'  id="example"> <!-- Cor da borda -->
        <form method="POST" action="" name="sas">
            <?php
            //echo $cod_sala;
            //echo $_SESSION["categoria"];
            $contador = 0;
            // CRIAï¿½ï¿½O DA TABELA EM SI 
            $aula = 1; 

            if ($_SESSION["turno"] == "1"){
                $tamanho = 6;
                $hora_tab = 1;
                $hora_inicio = 7;
            } 
            if ($_SESSION["turno"] == "2"){
                $tamanho = 6;
                $hora_tab = 1;
                $hora_inicio = 13;
            }
            if ($_SESSION["turno"] == "3"){
                $tamanho = 4;
                $hora_tab = 1;
                $hora_inicio = 19;
            }
            for ($x = 0; $x <= $tamanho; $x++) { // INICIO_FOR_LINHA
                ?>
                <TR>

                    <?php
                    $cont = 0;
                    for ($y = 0; $y < 8; $y++) { // INICIO_FOR_COLUNA
                        ?>
                       <!-- <TD WIDTH='<?php echo $tamanho_celula; ?>' -->
                        <?php
                        if (($x >= 1) && ($y == 0)) {
                            
                        }
                        ?>
                        <?php
                        if (($x == 0) && ($y == 0)) {
                            ?> 
                            <TD width="12%" 
                            <?php echo(" bgcolor=#3498DB "); // Célula Horário
                            ?> BORDER='0'> <?php
                                    echo $fonte[3];
                                    echo $cor_fonte["preta"];

                                    echo("<b> Horário </b>");
                                }
                                if (($x == 0) && ($y >= 1)) { // IMPRIMIR SEMANA 1a LINHA
                                    ?> 
                            <TD colspan=1 width="12%"   HEIGHT='<?php echo $altura_celula; ?>' <?php echo ("bgcolor=#3498DB"); ?> > <?php
                                if ($qual_semana == 2) {
                                    $rt = date("d", mktime(0, 0, 0, date("m"), date("d") + $inicio + 7, date("Y")));
                                    $mes = date("m", mktime(0, 0, 0, date("m"), date("d") + $inicio + 7, date("Y")));
                                    $ano = date("Y", mktime(0, 0, 0, date("m"), date("d") + $inicio + 7, date("Y")));
                                } else {

                                    $rt = date("d", mktime(0, 0, 0, date("m"), date("d") + $inicio, date("Y")));
                                    $mes = date("m", mktime(0, 0, 0, date("m"), date("d") + $inicio, date("Y")));
                                    $ano = date("Y", mktime(0, 0, 0, date("m"), date("d") + $inicio, date("Y")));
                                }
                                echo $fonte[3];
                                echo $cor_fonte["azul"];

                                echo("<b>");
                                echo $nome_semana[$y];
                                echo("</b>");
                                echo ("<br>");

                                $tempo78 = date("D", mktime(0, 0, 0, $mes78, $dia78, $ano78));


                                echo "<b>";
                                echo $fonte[1];
                                echo $rt;
                                echo ("-");
                                echo $mes;
                                echo ("-");
                                echo $ano;
                                //  }
                                echo "<b>";
                            }
                            
                            if (($x >= 1) && ($y == 0)) { // IMPRIMIR HORA 1a COLUNA E DEFINIR HORA
                                ?> 
                            <TD WIDTH="12%" HEIGHT='<?php echo $altura_celula; ?>' <?php
                            echo ("bgcolor=#3498DB"); // Altera a cor da celula horario.
                                ?> BORDER='0' > <?php
                                echo $fonte[3];
                                echo $cor_fonte["preta"];
                                
                                    if ($_SESSION["turno"]=="1") {
                                        echo("<b>");
                                        echo $aula;
                                        echo " °";
                                        echo " - Manhã"; 
                                    }
                                    else  
                                    if ($_SESSION["turno"]=="2") {
                                        echo("<b>");
                                        echo $aula;
                                        echo " °";
                                        echo " - Tarde"; 
                                    }
                                    else
                                    if ($_SESSION["turno"]=="3") {
                                        echo("<b>");
                                        echo $aula;
                                        echo " °";
                                        echo " - Noite"; 
                                    }
                                    $hora_inicio = $hora_inicio+ 1;
                                    
                                    echo("</b>");

                                $aula = $aula + 1;
                                $hora = $hora_tab;    
                                
                                echo $hora; 
                                echo "-----";
                                echo $hora_tab;
                            }

                            if (($x >= 1) && ($y >= 1)) { // PREENCHER CELULAS DE RESERVA
                                if ($qual_semana == 2) {

                                    $rt2 = date("d", mktime(0, 0, 0, date("m"), date("d") + $inicio2 + 7, date("Y")));
                                    $mes2 = date("m", mktime(0, 0, 0, date("m"), date("d") + $inicio2 + 7, date("Y")));
                                    $ano2 = date("Y", mktime(0, 0, 0, date("m"), date("d") + $inicio2 + 7, date("Y")));
                                } else {

                                    $rt2 = date("d", mktime(0, 0, 0, date("m"), date("d") + $inicio2, date("Y")));
                                    $mes2 = date("m", mktime(0, 0, 0, date("m"), date("d") + $inicio2, date("Y")));
                                    $ano2 = date("Y", mktime(0, 0, 0, date("m"), date("d") + $inicio2, date("Y")));
                                }


                                $datatempo = $ano2 . "-" . $mes2 . "-" . $rt2 . " " . $hora . ":00:00";   /* echo $datatempo; echo ("MES: $mes"); echo ("- MES2: $mes2"); */

                                $dataso = $ano2 . "-" . $mes2 . "-" . $rt2;


                                $tempof = date("Y-m-d");

                                $diaso = explode("-", $tempof);

                                $diaso1 = $diaso[1];

                                $horaso = date("H");

                                $hora_tab = $hora_tab + 1;

                                $t = $_SESSION ["turno"];
                                mysqli_select_db($link, 'tcc');

                                $datatempo = $ano2 . "-" . $mes2 . "-" . $rt2 . " " . $hora . ":00:00"; 

                                if ($_SESSION["turno"] == "1"){
                                    $dat = $ano2 . "-" . $mes2 . "-" . $rt2 . " " . "06" . ":00:00"; 
                                    $datafim = $ano2 . "-" . $mes2 . "-" . $rt2 . " " . "13" . ":00:00"; 
                                    $sql = "SELECT * FROM reservas WHERE  turno = '$t' AND datatempo >'$dat' AND datatempo < '$datafim' 
                                    AND cod_recurso='$cod_recurso'";
                                }
                                if ($_SESSION["turno"] == "2"){
                                    $dat = $ano2 . "-" . $mes2 . "-" . $rt2 . " " . "12" . ":00:00"; 
                                    $datafim = $ano2 . "-" . $mes2 . "-" . $rt2 . " " . "19" . ":00:00"; 
                                    $sql = "SELECT * FROM reservas WHERE turno = '$t' AND datatempo >'$dat' AND datatempo < '$datafim' AND cod_recurso='$cod_recurso'";
                                   
                                }

                                if ($_SESSION["turno"] == "3"){
                                    $dat = $ano2 . "-" . $mes2 . "-" . $rt2 . " " . "18" . ":00:00"; 
                                    $datafim = $ano2 . "-" . $mes2 . "-" . $rt2 . " " . "23" . ":00:00"; 
                                    $sql = "SELECT * FROM reservas WHERE turno = '$t' AND datatempo >'$dat' AND datatempo < '$datafim' 
                                    AND cod_recurso='$cod_recurso'";
                            
                                }
                                $resultado = $link->query($sql) or die($link->error());
                                $query = $link->query($sql);
                                $linha = $query->fetch_array();

                                $dia_atual1 = date("d");
                                $hora_atual = date("H");
                                $min_atual1 = date("i");
                                $ano_atual = date("Y");
                                $mes_atual = date("m");

                                ?>
                            </TD>
                            <?php
                                echo $fonte[1];
                                if ($y==1){
                                    echo("<TD width='12%''  HEIGHT='' bgcolor='WHITE'><center>"); // Cor das celulas
                                    echo "<b>";
                                    echo $cor_fonte["preta"];
                                    echo("Domingo");
                                    echo "<b>";
                                }
                                if ($linha["cod_recurso"] == "") {
                                    if (((($cod_recurso != "1")or ( $cod_recurso != "2"))AND ( ($y != 6)or ( $y != 7))) AND $y!="1"){
                                        echo("<TD  bgcolor='WHITE'><center>"); // Cor das celulas
                                        ?>

                                        <input WIDTH="100%" HEIGHT=""  type="radio" name="reservar" value="<?php echo $datatempo; ?>"
                        <?php
                        
                        if ($mes2 < $mesfgh) {
                            echo ("style='background-color:#DCDCDC ; cursor: not-allowed; color: #DCDCDC; border: #DCDCDC; border: double 2px DCDCDC; '");
                            echo(" disabled ");
                        }

                        if ($mes2 == $mesfgh) {
                            if ($rt2 < $dia_atual1) {
                                echo ("style='background-color:#DCDCDC ; cursor: not-allowed; color: #DCDCDC; border: #DCDCDC; border: double 2px DCDCDC; '");
                                echo(" disabled ");
                                $gh = 1;
                            } else {
                                $gh = 2;
                            }

                            if ($rt2 == $dia_atual1) {

                                if ($hora <= $hora_atual) {
                                    echo ("style='background-color:#DCDCDC ; cursor: not-allowed; color: #DCDCDC; border: #DCDCDC; border: double 2px DCDCDC; '");
                                    echo(" disabled");
                                    $gh = 1;
                                } else {
                                    $gh = 2;
                                }

                            }
                        }
                        ?>
                                            >
                                        <?php
                                        echo("</TD>");
                                    }
                                } 
                        else {

                                        echo("<TD WIDTH='12%' HEIGHT='' bgcolor='red'><center>");

                                        $matricula = $linha["matricula"];
                                        $sql1 = "SELECT * FROM usuarios WHERE matricula='$matricula'";
                                        $query = $link->query($sql1);
                                        $resultado1 = $link->query($sql1) or die($link->error());
                                        $linha1 = $query->fetch_array();
                                        $supervisor = $linha1["nome"];
                                        $sobrenome = $linha1["sobrenome"];
                                        
                                        $id = $linha1["usuario_id"];
                                        echo $cor_fonte["preta"];
                                        if ($_SESSION["nome"]==$supervisor){
                                            echo "<b>";
                                                echo("<b href=\"javascript:\" onMouseOver=\"return overlib('<html><table class=bordas bgcolor=#3498DB><tr><td><font face=arial color=#000000 size=2><b>Reservado por você.</b></table></html>',STATUS,'RESERVADO',FULLHTML)\" onMouseOut=\"nd()\"> $supervisor</b>");
                                        echo "<b>";    
                                        }
                                        else {
                                        echo "<b>";
                                                echo("<b href=\"javascript:\" onMouseOver=\"return overlib('<html><table class=bordas bgcolor=#3498DB><tr><td><font face=arial color=#000000 size=2><b>Reservado por: $supervisor $sobrenome.</table></html>',STATUS,'RESERVADO',FULLHTML)\" onMouseOut=\"nd()\"><b>$supervisor</b></a>");
                                        echo "<b>";
                                        }
                                        echo("</TD>");

                                    
                                }
                                // Começo do contador. 

                                $matricula = $_SESSION["matricula"];
                                $cont = 0; 
                                $cod = array();
                                                                    
                                $sql6 = "SELECT * from recursos WHERE categoria = 1 ";
                                        
                                        $query = $link->query($sql6) or die($link->error());
                                        
                                        while ($linha6 = $query->fetch_array()) {
                                            $cod[$cont] = $linha6["cod_recurso"];
                                             $cont++;
                                        }
                                

                                $cont = 0;

                                foreach ($cod as $c) {

                                      $sql8 = "SELECT * from reservas WHERE datatempo='$datatempo' AND matricula='$matricula' AND cod_recurso='$c'";
                                      $query = $link->query($sql8) or die($link->error());
                                        
                                        while ($linha2 = $query->fetch_array()) {
                                             $contador++;
                                             
                                        }
                                  
                                }
                                

                                // Fim do contador // 

                                $sql = "SELECT * FROM reservas WHERE datatempo='$datatempo' AND cod_recurso='$cod_recurso'";
                                $resultado = $link->query($sql) or die($link->error());

                                $query = $link->query($sql);

                                $linha = $query->fetch_array();

                                

                                $matricula = $linha["matricula"];

                                $sql1 = "SELECT * FROM usuarios WHERE matricula='$matricula'";
                                $query = $link->query($sql1);
                                $resultado1 = $link->query($sql1) or die($link->error());
                                $linha1 = $query->fetch_array();
                                $supervisor = $linha1["nome"];
                                
                            }
                            ?>

                                    <?php
                                    if (($x == 0) && ($y >= 1)) {
                                        $inicio++;
                                    }
                                    if (($x >= 1) && ($y >= 1)) {
                                        $inicio2++;
                                    }
                                } // FINAL_FOR_COLUNA

                                $inicio2 = $inicio3;
                                $novomes2 = 0;
                                $mes2 = $mesd;
                                // $marc2=0; ANTIGO
                                ?>
                    </TD>
                </TR>
                        <?php
                    } // FINAL_FOR_LINHA
                    ?>
    </table>
    <table width="70%" border="0" cellpadding="0" cellspacing="0"><tr><br></td></tr></table>
    <table BORDER="0" WIDTH="70%">
        <TR>
            <TD ALIGN="LEFT" WIDTH="10%">
                <table>

            </TD>
        </tr>
    </table>
    </TD>
    <TD WIDTH="20%">
    <?php
    echo $fonte[1];
    echo $cor_fonte["branca"];
    ?>
        <input border="0" class="bordas" type="hidden" name="cod_recurso"  value="<?php echo $cod_recurso; ?>">
        <input border="0" class="bordas" type="hidden" name="prox_semana"  value="<?php echo $prox_semana; ?>">
        <input border="0" class="bordas" type="hidden" name="dia_atual"  value="<?php echo $dia_atual; ?>">
        <input border="0" class="bordas" type="hidden" name="qualsemana1"  value="<?php echo $qual_semana; ?>">
        <input border="0" type="hidden" name="categoria" border=0 value="<?php echo $_SESSION["categoria"]; ?>">

    </TD>
    <h1 style="color: #900;">
        <?php
        echo $fonte[3];
        if ($contador == 1) {
            //echo "Você efetuou ", $contador, " reservas de Datashow para esta semana. ";  
        } else {
            //echo "Você efetuou ", $contador, " reservas de Datashow para esta semana. ";   
        }
        
        if (($contador >= 3) AND ($_SESSION["categoria"] == "1") AND ($_SESSION["tipo"]=="p")) {
            
            echo "Você atingiu o limite de reservas de Datashow.";
        
        } else {
            
            if (($contador < 3) AND ($_SESSION["categoria"] == "1"))  {

            if ($contador == 1){
                echo "Você efetuou ", $contador, " reserva de Datashow nesta semana.";    
            }
            else {
                echo "Você efetuou ", $contador, " reservas de Datashow nesta semana.";
            }
        }
            ?>
            <TD>
                <!-- <BUTTON border="0" class="bordas" OnClick="AAA=window.open('http://LINK_USUARIO,'_blank');">URL adiconal</BUTTON> -->
        </h1>       
        <div >
        <TD width="30%" class="reserva" align="right">
            <P style="MARGIN-TOP: -20px; MARGIN-BOTTOM: 0px" ><SPAN 
                    class=box-header>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <INPUT class=frm onmouseover="this.className = 'frm-on';" 
                           onmouseout="this.className = 'frm';" 
                           type="submit" name="enviar" value="Reservar" onClick="javascript:valida();"></SPAN>
        <?php } ?>
        </TR>
    </form>
    </table>
    </TD>
    </TR>
    </table>
    <?php } 
        else {
            echo "Nenhum recurso cadastrado nesta categoria!";
        }
    ?>

</fieldset>
    <?php
}

// FINAL_FUNCTION_MOSTRARSEMANA
// -------------------------------------------------------------------------------------------------- //
// ---------           ENVIAR          -------------------------------------------------------------- //
// -------------------------------------------------------------------------------------------------- //

if ($enviar = isset($_GET["enviar"]))
    ;

if ($enviar == "sim") { // INICIO_ENVIAR_0
    
    if (isset($_POST["qualsemana1"])) {
    $matricula = $_SESSION["matricula"];
    $prox_semana = $_POST["prox_semana"];
    $dia_atual = $_POST["dia_atual"];
    $_SESSION["categoria"] = $_POST["categoria"];
    $cod_recurso = $_POST["cod_recurso"];
    $qual_semana1 = $_POST["qualsemana1"];
    $_SESSION["cod_recurso"] = $cod_recurso;
    $_SESSION["qualsemana1"] = isset($qualsemana1);


    mysqli_select_db($link, 'tcc');

    $sql1 = "SELECT * FROM usuarios WHERE matricula='$matricula'";
    $query = $link->query($sql1);
    $resultado1 = $link->query($sql1) or die($link->error());
    $linha1 = $query->fetch_array();

    if ($linha1["matricula"] == $matricula) { // REL_000
        $reservar = $_POST["reservar"];
        $tempo = 4;

        if ($tempo == 0) {
            $tempo_meio = 1;
        }

        $dia_hora = explode(" ", $reservar);
        $dia_mes_ano = explode("-", $dia_hora[0]);
        $hora_min_seg = explode(":", $dia_hora[1]);
        $hora_inicio = $hora_min_seg[0] - 1;
        $hora_inicio2 = $hora_min_seg[0];
        echo $hora_inicio2; 

        $min_inicio = $hora_min_seg[1];
        $e_ano = $dia_mes_ano[0];
        $e_mes = $dia_mes_ano[1];
        $e_dia = $dia_mes_ano[2];

        $email_data = $e_dia . "/" . $e_mes . "/" . $e_ano;
        $email_hora = $dia_hora[1];
        $existe = 0;

        $matricula = $_SESSION["matricula"];
        $datam = $dia_hora[0];
        $salvar_data = $datam . " " . $hora_inicio2 . ":00:00";
        mysqli_select_db($link, 'tcc');
        $turn = $_SESSION ["turno"];

                  $sql = "SELECT * FROM reservas WHERE datatempo='$salvar_data' AND cod_recurso='$cod_recurso'";
                  $query = $link->query($sql);
                  $resultado = $link->query($sql) or die($link->error());
                  

                while ($linha = $query->fetch_array()) {
                 
                      $existe++;
                   
                  }

        if (($min_inicio == 00) && ($tempo >= 1) && ($existe==0)) { // INICIO IF 4
            
            
                if ($cod_recurso != 0) {
                
                $con=mysqli_connect("localhost","root","","tcc");
                // VERIFICA A LIGAÇÃO NÃO TEM ERROS
                        if (mysqli_connect_errno())
                        {
                            // CASO TENHA ERROS MOSTRA O ERRO DE LIGAÇÃO À BASE DE DADOS
                            echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        }
                $sql = "INSERT INTO reservas (datatempo,cod_recurso,matricula, turno) VALUES ('$salvar_data','$cod_recurso',
                '$matricula', '$turn')";
                if (!mysqli_query($con,$sql))
                {
                    die('Error: ' . mysqli_error($con));
                }

                // MOSTRA A MENSAGEM DE SUCESSO

                mysqli_close($con);

                $_SESSION["cod_recurso"] = $cod_recurso;
                $_SESSION["qualsemana"] = $qual_semana1;
                $_SESSION["categoria"] = $_POST["categoria"];

                 $_SESSION["opcao"] = "reservou";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'user1.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");          
                            return;
            } else {

                 $_SESSION["opcao"] = "erro";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'user1.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");          
                            return;

            }
            ?>

            <?php
        } // FIM IF 4

        else {

         $_SESSION["opcao"] = "erro2";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'user1.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");          
                            return;

            }

        ?> 
        <BODY > 
        </body>
        <?php
    } else {

         $_SESSION["opcao"] = "erro";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'user1.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");          
                            return;

            }
        }
        else {

         $_SESSION["opcao"] = "erro";
                      echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                                  top.location.href = 'user1.php'
                         } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");          
                            return;

            }
} else { // ELSE_ENVIAR_0

    if ($env_cod_recurso = isset($_POST["env_cod_recurso"]))
        ;
    if ($proxima = isset($_POST["proxima"]))
        ;
    if ($anterior = isset($_POST["anterior"]))
        ;
    if ($data1 = isset($_POST["data1"]))
        ;
    if ($inicio = isset($_POST["inicio1"]))
        ;
    if ($dia_atual = isset($_POST["dia_atual"]))
        ;
    if (isset($_POST["categoria"])){
        $_SESSION["categoria"] = $_POST["categoria"];
    }


    // ] 
    if (($env_cod_recurso) || ($proxima) || ($anterior)) { // REF_005
        $cod_recurso = $_POST["cod_recurso"];
        $tempo = date("Y-m-d");
        $dia_atual = $_POST["dia_atual"];

        if ($proxima) {

            $prox_semana = $_POST["prox_semana"];
            $qual_semana = 2;
            
            mostrarsemana($prox_semana, $cod_recurso, $qual_semana, $prox_semana, $dia_atual);
        }

        if ($anterior) {

            $prox_semana = $_POST["prox_semana"];

            $qual_semana = 1;

            mostrarsemana($dia_atual, $cod_recurso, $qual_semana, $prox_semana, $dia_atual);
        }

        if ((!$proxima) && (!$anterior)) {

            $qual_semana = $_SESSION["qualsemana"];

            $prox_semana = $_POST["prox_semana"];

            mostrarsemana($dia_atual, $cod_recurso, $qual_semana, $prox_semana, $dia_atual);
        }

    } else { // ELSE_REF_005

        if (isset($_POST["categoria"])){
        $_SESSION["categoria"] = $_POST["categoria"];
        }
        
        if (isset($_POST["turno"])){
        $_SESSION["turno"] = $_POST["turno"];
        }

        if ($proxima = isset($_POST["proxima"]))
            ;
        if ($anterior = isset($_POST["anterior"]))
            ;
        if ($data1 = isset($_POST["data1"]))
            ;
        if ($inicio = isset($_POST["inicio1"]))
            ;
        if ($prox_semana = isset($_POST["prox_semana"]))
            ;
        if ($dia_atual = isset($_POST["dia_atual"]))
            ;
        if ($codrecurso = isset($_GET["codrecurso"]))
            ;
        if ($qualsemana = isset($_GET["qualsemana"]))
            ;

        if ($codrecurso) { // REF_006
            $prox_semana = $_GET["prox_semana"];
            $dia_atual = $_GET["dia_atual"];

            if ($qualsemana == 2) { // REF_007
                mostrarsemana($prox_semana, $codrecurso, $qualsemana, $prox_semana, $dia_atual);
            } else { // ELSE_REF_007
                mostrarsemana($dia_atual, $codrecurso, $qualsemana, $prox_semana, $dia_atual);
            } // REF_007
        } else { // ELSE_REF_006

            date_default_timezone_set('America/Sao_Paulo');
            
            $tempo = date("Y-m-d");

            $tempod = $tempo;

            $tempod = explode("-", $tempod);

            $dia = $tempod[2];
            $mes = $tempod[1];
            $ano = $tempod[0];

            $tempo = date("w", mktime(0, 0, 0, $mes, $dia, $ano));

            $dia_da_semana2 = $tempo;

            $tempo = "";

            $tempo = date("Y-m-d");

            $tempod = $tempo;

            $tempod = explode("-", $tempod);

            $dia = $tempod[2];
            $dia = $dia + 6;
            $mes = $tempod[1];
            $ano = $tempod[0];

            $temp_ano = $ano . "-12-31";

            if ($dia_da_semana2 == 1) {
                $dia_da_semana = "Segunda";
                $inicio = 1;
                $inicio2 = 1;
                $iniciok = 2;
                $inicio_prox = 8;
            }
            if ($dia_da_semana2 == 2) {
                $dia_da_semana = "Terça";
                $inicio = -2;
                $inicio2 = -2;
                $iniciok = 3;
                $inicio_prox = 7;
            }
            if ($dia_da_semana2 == 3) {
                $dia_da_semana = "Quarta";
                $inicio = -3;
                $inicio2 = -3;
                $iniciok = 4;
                $inicio_prox = 6;
            }
            if ($dia_da_semana2 == 4) {
                $dia_da_semana = "Quinta";
                $inicio = -4;
                $inicio2 = -4;
                $iniciok = 5;
                $inicio_prox = 5;
            }
            if ($dia_da_semana2 == 5) {
                $dia_da_semana = "Sexta";
                $inicio = -5;
                $inicio2 = -5;
                $iniciok = 6;
                $inicio_prox = 4;
            }
            if ($dia_da_semana2 == 6) {
                $dia_da_semana = "Sábado";
                $inicio = -6;
                $inicio2 = -6;
                $iniciok = 7;
                $inicio_prox = 3;
            }
            if ($dia_da_semana2 == 0) {
                $dia_da_semana = "Domingo";
                //$dia++;
                $inicio = 0;
                $inicio2 = 0;
                $inicio_prox = 1;
            } //CASO SEJA DOMINGO


            if ($dia < 10) {
                $dia = "0" . $dia;
            }

            $prox_semana = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 7, date("Y")));
            if (isset($_SESSION["cod_recurso"])){
                $cod_recurso = $_SESSION["cod_recurso"];
                $qualsemana = $_SESSION["qualsemana"];
            }
            else {
                $cod_recurso = "1";
                $qualsemana = "1";
            }

            if (!isset($_SESSION["categoria"])){
                $_SESSION["categoria"] = "1";
                $_SESSION["teste"] = "1";
            }
            mostrarsemana($tempo, $cod_recurso, $qualsemana, $prox_semana, $tempo);
        } // FIM_REF_006
    } // FIM_REF_005
} // FIM_ENVIAR_0
?>