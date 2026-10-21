<?php
    if (!isset($_SESSION)) {
        session_start();
    }


    if (isset($_SESSION["opcao"])){
        if ($_SESSION["opcao"] == "vazia"){
            echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                            swal({ title:'', text:'Por favor, selecione um horário!', type:'' }, function(){} ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        }
        else if ($_SESSION["opcao"] == "reservou"){
            echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                            swal({ title:'', text:'Reserva efetuada com sucesso! :). ', type:'success' }, function(){} ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        }
        else if ($_SESSION["opcao"] == "erro"){
            echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                            swal({ title:'', text:'Ops, houve um erro. Tente novamente!', type:'error' }, function(){} ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        }
        else if ($_SESSION["opcao"] == "erro2"){
            echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                            swal({ title:'', text:'Ops, este recurso já está reservado por outro usuário!', type:'error' }, function(){} ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        }
        $_SESSION["opcao"] = "0";
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Recursos</title>
    <script src="sweetalert-dev.min.js"></script>
    <link rel="stylesheet" href="sweetalert.min.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #5b72d4 0%, #7c5cbf 50%, #9b59b6 100%);
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.18);
            padding: 0 2rem;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar-brand {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar-brand img {
            height: 34px;
            width: 34px;
            border-radius: 50%;
            object-fit: cover;
            background: #fff;
        }
        .topbar-user {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
        }
        .topbar-user strong { color: #fff; }

        /* ── PAGE ── */
        .page-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem 3rem;
        }

        /* ── CARD ── */
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 980px;
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem 2rem 1.2rem;
            border-bottom: 1px solid #eef0f6;
        }
        .card-header h1 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a1d2e;
        }
        .card-header p {
            font-size: 13px;
            color: #8b90a7;
            margin-top: 3px;
        }

        .card-body {
            padding: 1.5rem 2rem 2rem;
        }

        /* ── FILTER BAR ── */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .filter-group label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #8b90a7;
        }
        .filter-group select {
            background: #f5f6fa;
            border: 1.5px solid #e2e5f0;
            border-radius: 8px;
            color: #1a1d2e;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 32px 8px 12px;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238b90a7' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            min-width: 140px;
            transition: border-color .2s;
        }
        .filter-group select:hover,
        .filter-group select:focus {
            border-color: #6e7ff3;
        }

        /* ── WEEK NAV ── */
        .week-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }
        .btn-week {
            background: #f5f6fa;
            border: 1.5px solid #e2e5f0;
            border-radius: 8px;
            color: #3d4166;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 18px;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-week:hover { background: #eaecf8; border-color: #6e7ff3; color: #4c5bcc; }
        .btn-week:disabled { opacity: 0.35; cursor: not-allowed; }

        /* ── TABLE ── */
        .table-wrap {
            border: 1.5px solid #e8eaf4;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }
        .schedule-table th {
            background: #f5f6fa;
            padding: 11px 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #8b90a7;
            border-bottom: 1.5px solid #e8eaf4;
            text-align: center;
        }
        .schedule-table th.th-hora { text-align: left; padding-left: 16px; }
        .schedule-table td {
            border: 1px solid #eef0f7;
            height: 48px;
            vertical-align: middle;
            text-align: center;
            padding: 0;
        }
        .schedule-table td.td-hora {
            background: #f9fafc;
            padding: 0 14px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #5a5f80;
            white-space: nowrap;
            min-width: 120px;
        }
        .turn-badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.4px;
            padding: 1px 5px;
            border-radius: 4px;
            margin-left: 5px;
            vertical-align: middle;
        }
        .turn-m { background: #e8f0fe; color: #4c5bcc; }
        .turn-t { background: #fff4e5; color: #d97706; }
        .turn-n { background: #f3e8ff; color: #7c3aed; }

        /* SLOTS */
        .slot-free { position: relative; height: 48px; }
        .slot-free input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 100%; height: 100%;
            top: 0; left: 0;
            margin: 0;
            cursor: pointer;
        }
        .slot-free label {
            display: block;
            width: 100%; height: 100%;
            cursor: pointer;
            transition: background .15s;
        }
        .slot-free input[type="radio"]:checked + label {
            background: #e8f0fe;
            border: 2px solid #6e7ff3;
        }
        .slot-free label:hover { background: #f0f3ff; }

        .slot-mine {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            height: 100%;
            background: #ecfdf5;
            font-size: 12px;
            font-weight: 600;
            color: #059669;
        }
        .cancel-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #dc2626;
            font-size: 12px;
            line-height: 1;
            padding: 2px 4px;
            border-radius: 4px;
            transition: background .15s;
        }
        .cancel-btn:hover { background: #fee2e2; }

        .slot-taken {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: #fff5f5;
            font-size: 12px;
            font-weight: 500;
            color: #dc2626;
        }
        .slot-past { background: #fafafa; }
        .slot-sunday { background: #f9fafc; color: #c5c8da; font-size: 12px; }

        /* ACTION BAR */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .legend {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #8b90a7;
        }
        .legend-dot {
            width: 10px; height: 10px;
            border-radius: 3px;
        }
        .dot-free  { background: #c7d2fe; border: 1.5px solid #6e7ff3; }
        .dot-mine  { background: #a7f3d0; }
        .dot-taken { background: #fecaca; }
        .dot-past  { background: #e5e7eb; }

        .btn-reservar {
            background: linear-gradient(135deg, #6e7ff3, #7c5cbf);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;
            padding: 11px 36px;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
            box-shadow: 0 4px 14px rgba(110,127,243,0.35);
        }
        .btn-reservar:hover  { opacity: 0.88; }
        .btn-reservar:active { transform: scale(.97); }

        .empty-state {
            text-align: center;
            padding: 2.5rem;
            color: #8b90a7;
            font-size: 14px;
        }

        #overDiv { position: absolute; visibility: hidden; z-index: 200; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-brand">
        <!-- Substitua pelo caminho real do logo -->
        <img src="logo.png" alt="Logo" onerror="this.style.display='none'">
        Reserva de Recursos
    </div>
    <div class="topbar-user">
        Olá, <strong><?php echo htmlspecialchars($_SESSION["nome"] ?? 'Usuário'); ?></strong>
    </div>
</div>

<div class="page-body">
    <div class="card">
        <div class="card-header">
            <h1>Agendamento de Recursos</h1>
            <p>Selecione o recurso, turno e clique em um horário disponível para reservar.</p>
        </div>
        <div class="card-body">

<?php
    require_once('conecta.php');
    $link = DB_Connect();

    function mostrarsemana($data, $cod_recurso, $qual_semana, $prox_semana, $dia_atual) {
        global $link;
        ?>
        <script language="JavaScript">
            function alterarrecurso() { document.recurso.action=''; document.recurso.submit(); }
            function alterartipo()    { document.categoria.action=''; document.categoria.submit(); }
            function alterarturno()   { document.turno.action=''; document.turno.submit(); }
            function valida() {
                var form = document.sas;
                if (form.reservar.value == '') { form.action='mensagem.php'; }
                else { form.action='?enviar=sim'; form.submit(); }
            }
        </script>
        <div id="overDiv" style="position:absolute;visibility:hidden;z-index:1;"></div>
        <script language="JavaScript" src="overlib.js" type="text/javascript"></script>
        <?php

        $data1    = $data;
        $data_prox= $data;
        $data     = explode("-", $data);
        $fgh      = explode("-", $dia_atual);
        $mesfgh   = $fgh[1];
        $dia      = $data[2];
        $mes      = $data[1];
        $mesd     = $data[1];
        $ano      = $data[0];

        date_default_timezone_set('America/Sao_Paulo');
        $dia_da_semana2 = date("w", mktime(0,0,0,$mes,$dia,$ano));

        if ($dia_da_semana2==1){ $inicio=-1; $inicio2=1;  $iniciok=1; $inicio_prox=8;  }
        if ($dia_da_semana2==2){ $inicio=-2; $inicio2=-2; $iniciok=2; $inicio_prox=9;  }
        if ($dia_da_semana2==3){ $inicio=-3; $inicio2=-3; $iniciok=3; $inicio_prox=10; }
        if ($dia_da_semana2==4){ $inicio=-4; $inicio2=-4; $iniciok=4; $inicio_prox=11; }
        if ($dia_da_semana2==5){ $inicio=-5; $inicio2=-5; $iniciok=5; $inicio_prox=12; }
        if ($dia_da_semana2==6){ $inicio=-6; $inicio2=-6; $iniciok=6; $inicio_prox=13; }
        if ($dia_da_semana2==0){ $inicio=0;  $inicio2=0;  $iniciok=1; $inicio_prox=14; }

        $nome_semana=["","Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"];
        $data_atual78=explode("-",$dia_atual);
        $dia78=$data_atual78[2]; $mes78=$data_atual78[1]; $ano78=$data_atual78[0];
        $inicio3=$inicio;

        if ($_SESSION["turno"]=="1"){ $tamanho=5; $hora_tab=7;  $horaula=7;  }
        if ($_SESSION["turno"]=="2"){ $tamanho=5; $hora_tab=13; $horaulat=13;}
        if ($_SESSION["turno"]=="3"){ $tamanho=4; $hora_tab=19; $horaulan=18;}
        ?>

        <!-- FILTER BAR -->
        <div class="filter-bar">

            <div class="filter-group">
                <label>Turno</label>
                <form method="POST" action="" name="turno" style="margin:0">
                    <select name="turno" onchange="alterarturno()">
                        <option value="1" <?php if($_SESSION["turno"]=="1") echo 'selected';?>>Manhã</option>
                        <option value="2" <?php if($_SESSION["turno"]=="2") echo 'selected';?>>Tarde</option>
                        <option value="3" <?php if($_SESSION["turno"]=="3") echo 'selected';?>>Noite</option>
                    </select>
                </form>
            </div>

            <div class="filter-group">
                <label>Categoria</label>
                <form method="POST" action="" name="categoria" style="margin:0">
                    <select name="categoria" onchange="alterartipo()">
                        <option value="1" <?php if($_SESSION["categoria"]=="1") echo 'selected';?>>Datashow</option>
                        <option value="2" <?php if($_SESSION["categoria"]=="2") echo 'selected';?>>Laboratório</option>
                        <option value="3" <?php if($_SESSION["categoria"]=="3") echo 'selected';?>>Sala</option>
                        <option value="4" <?php if($_SESSION["categoria"]=="4") echo 'selected';?>>Outros</option>
                    </select>
                </form>
            </div>

            <div class="filter-group">
                <label>Recurso</label>
                <form method="POST" action="" name="recurso" style="margin:0">
                    <input type="hidden" name="env_cod_recurso" value="Alterar Recurso">
                    <input type="hidden" name="inicio1"     value="<?php echo $inicio;?>">
                    <input type="hidden" name="prox_semana" value="<?php echo $prox_semana;?>">
                    <input type="hidden" name="dia_atual"   value="<?php echo $dia_atual;?>">
                    <input type="hidden" name="categoria"   value="<?php echo $_SESSION["categoria"];?>">
                    <input type="hidden" name="turno"       value="<?php echo $_SESSION["turno"];?>">
                    <?php
                    $_SESSION["qualsemana"]=$qual_semana;
                    mysqli_select_db($link,'tcc');
                    $categoria=$_SESSION["categoria"];
                    $sql5="SELECT * from recursos WHERE status='Disponivel' and categoria='$categoria' ORDER BY nome_recurso";
                    $qr5=$link->query($sql5) or die($link->error());
                    $teste=0;
                    $opts='';
                    while($ln5=$qr5->fetch_array()){
                        if(($teste==0) and ($_SESSION["teste"]!=$_SESSION["categoria"])){
                            $cod_recurso=$ln5["cod_recurso"];
                            $_SESSION["cod_recurso"]=$cod_recurso;
                        }
                        if(($ln5["status"]=="Disponivel") and ($ln5["categoria"]==$_SESSION["categoria"])){
                            $sel=($cod_recurso==$ln5["cod_recurso"])?' selected':'';
                            $opts.='<option value="'.$ln5["cod_recurso"].'"'.$sel.'>'.htmlspecialchars($ln5["nome_recurso"]).'</option>';
                            $teste++;
                            $_SESSION["teste"]=$categoria;
                        }
                    }
                    if($teste==0){ $cod_recurso=0; $opts='<option>Nenhum recurso</option>'; }
                    echo '<select name="cod_recurso" onchange="alterarrecurso()">'.$opts.'</select>';
                    ?>
                </form>
            </div>

            <!-- Week nav -->
            <div class="week-nav">
                <?php if($qual_semana!=1): ?>
                <form method="POST" action="" name="anterior" style="margin:0">
                    <input type="hidden" name="cod_recurso"  value="<?php echo $cod_recurso;?>">
                    <input type="hidden" name="data1"        value="<?php echo $data_prox;?>">
                    <input type="hidden" name="inicio1"      value="<?php echo $inicio;?>">
                    <input type="hidden" name="prox_semana"  value="<?php echo $prox_semana;?>">
                    <input type="hidden" name="dia_atual"    value="<?php echo $dia_atual;?>">
                    <input type="hidden" name="categoria"    value="<?php echo $_SESSION["categoria"];?>">
                    <input type="hidden" name="turno"        value="<?php echo $_SESSION["turno"];?>">
                    <button type="submit" name="anterior" class="btn-week">← Semana</button>
                </form>
                <?php else: ?>
                    <button class="btn-week" disabled>← Semana</button>
                <?php endif; ?>

                <?php if($qual_semana!=2): ?>
                <form method="POST" action="" name="proxima" style="margin:0">
                    <input type="hidden" name="cod_recurso"  value="<?php echo $cod_recurso;?>">
                    <input type="hidden" name="data1"        value="<?php echo $data1;?>">
                    <input type="hidden" name="inicio1"      value="<?php echo $iniciok;?>">
                    <input type="hidden" name="inicio_prox"  value="<?php echo $inicio_prox;?>">
                    <input type="hidden" name="prox_semana"  value="<?php echo $prox_semana;?>">
                    <input type="hidden" name="dia_atual"    value="<?php echo $dia_atual;?>">
                    <input type="hidden" name="categoria"    value="<?php echo $_SESSION["categoria"];?>">
                    <input type="hidden" name="turno"        value="<?php echo $_SESSION["turno"];?>">
                    <button type="submit" name="proxima" class="btn-week">Semana →</button>
                </form>
                <?php else: ?>
                    <button class="btn-week" disabled>Semana →</button>
                <?php endif; ?>
            </div>
        </div><!-- /filter-bar -->

        <?php if($cod_recurso!=0): ?>

        <!-- SCHEDULE TABLE -->
        <form method="POST" action="" name="sas">
            <input type="hidden" name="cod_recurso"  value="<?php echo $cod_recurso;?>">
            <input type="hidden" name="prox_semana"  value="<?php echo $prox_semana;?>">
            <input type="hidden" name="dia_atual"    value="<?php echo $dia_atual;?>">
            <input type="hidden" name="qualsemana1"  value="<?php echo $qual_semana;?>">
            <input type="hidden" name="categoria"    value="<?php echo $_SESSION["categoria"];?>">
            <input type="hidden" name="turno"        value="<?php echo $_SESSION["turno"];?>">

            <div class="table-wrap">
            <table class="schedule-table">
                <tr>
                    <th class="th-hora">Horário</th>
                    <?php
                    $ini_tmp=$inicio;
                    for($y=1;$y<8;$y++){
                        if($qual_semana==2){
                            $rt_h =date("d",mktime(0,0,0,date("m"),date("d")+$ini_tmp+7,date("Y")));
                            $ms_h =date("m",mktime(0,0,0,date("m"),date("d")+$ini_tmp+7,date("Y")));
                        } else {
                            $rt_h =date("d",mktime(0,0,0,date("m"),date("d")+$ini_tmp,date("Y")));
                            $ms_h =date("m",mktime(0,0,0,date("m"),date("d")+$ini_tmp,date("Y")));
                        }
                        echo '<th>'.$nome_semana[$y].'<br><span style="font-weight:400;font-size:10px;opacity:.65">'.$rt_h.'/'.$ms_h.'</span></th>';
                        $ini_tmp++;
                    }
                    ?>
                </tr>

                <?php
                $horaula  = isset($horaula)  ? $horaula  : 7;
                $horaulat = isset($horaulat) ? $horaulat : 13;
                $horaulan = isset($horaulan) ? $horaulan : 18;
                $sid=0;

                for($x=1; $x<=$tamanho; $x++){
                    $hora = ($hora_tab<=9)?"0".$hora_tab:$hora_tab;
                    if($_SESSION["turno"]=="1")     { $hstr=$horaula;  $badge='M'; $bc='turn-m'; }
                    elseif($_SESSION["turno"]=="2") { $hstr=$horaulat; $badge='T'; $bc='turn-t'; }
                    else                            { $hstr=$horaulan; $badge='N'; $bc='turn-n'; }

                    echo '<tr>';
                    echo '<td class="td-hora">'.$hstr.':00 – '.(intval($hstr)+1).':00 <span class="turn-badge '.$bc.'">'.$badge.'</span></td>';

                    for($y=1;$y<8;$y++){
                        if($qual_semana==2){
                            $rt2 =date("d",mktime(0,0,0,date("m"),date("d")+$inicio2+7,date("Y")));
                            $mes2=date("m",mktime(0,0,0,date("m"),date("d")+$inicio2+7,date("Y")));
                            $ano2=date("Y",mktime(0,0,0,date("m"),date("d")+$inicio2+7,date("Y")));
                        } else {
                            $rt2 =date("d",mktime(0,0,0,date("m"),date("d")+$inicio2,date("Y")));
                            $mes2=date("m",mktime(0,0,0,date("m"),date("d")+$inicio2,date("Y")));
                            $ano2=date("Y",mktime(0,0,0,date("m"),date("d")+$inicio2,date("Y")));
                        }
                        $datatempo=$ano2."-".$mes2."-".$rt2." ".$hora.":00:00";
                        $dia_atual1=date("d"); $hora_atual=date("H"); $min_atual1=date("i");
                        $ano_atual=date("Y");  $mes_atual=date("m");

                        // Domingo
                        if($y==1){ echo '<td class="slot-sunday">—</td>'; $inicio2++; continue; }

                        // Past?
                        $is_past=false;
                        if($mes2<$mesfgh) $is_past=true;
                        if($mes2==$mesfgh){
                            if($rt2<$dia_atual1) $is_past=true;
                            if($rt2==$dia_atual1 && $hora<=$hora_atual) $is_past=true;
                        }

                        mysqli_select_db($link,'tcc');
                        $sql="SELECT * FROM reservas WHERE datatempo='$datatempo' AND cod_recurso='$cod_recurso'";
                        $qr=$link->query($sql);
                        $ln=$qr->fetch_array();

                        if($ln["cod_recurso"]==""){
                            if($is_past){
                                echo '<td class="slot-past"></td>';
                            } else {
                                $sid++;
                                echo '<td><div class="slot-free">';
                                echo '<input type="radio" name="reservar" id="s'.$sid.'" value="'.$datatempo.'">';
                                echo '<label for="s'.$sid.'"></label>';
                                echo '</div></td>';
                            }
                        } else {
                            $mat=$ln["matricula"];
                            $qr1=$link->query("SELECT * FROM usuarios WHERE matricula='$mat'");
                            $ln1=$qr1->fetch_array();
                            $supervisor=$ln1["nome"]; $sobrenome=$ln1["sobrenome"];
                            $lid=$ln["ID"];
                            $hora_exata=$ano_atual."-".$mes_atual."-".$dia_atual1." ".$hora_atual.":".$min_atual1.":00";

                            if($_SESSION["nome"]==$supervisor){
                                echo '<td><div class="slot-mine">';
                                echo '<span title="Reservado por você">'.htmlspecialchars($supervisor).'</span>';
                                if($hora_exata<$datatempo)
                                    echo '<a href="cancela.php?id='.$lid.'" class="cancel-btn" title="Cancelar">✕</a>';
                                echo '</div></td>';
                            } else {
                                echo '<td><div class="slot-taken" title="Reservado por '.htmlspecialchars($supervisor.' '.$sobrenome).'">';
                                echo htmlspecialchars($supervisor);
                                echo '</div></td>';
                            }
                        }
                        $inicio2++;
                    }
                    echo '</tr>';
                    $inicio2=$inicio3;
                    $hora_tab++; $horaula++; $horaulat++; $horaulan++;
                }
                ?>
            </table>
            </div><!-- /table-wrap -->

            <div class="action-bar">
                <div class="legend">
                    <div class="legend-item"><div class="legend-dot dot-free"></div>Disponível</div>
                    <div class="legend-item"><div class="legend-dot dot-mine"></div>Minha reserva</div>
                    <div class="legend-item"><div class="legend-dot dot-taken"></div>Ocupado</div>
                    <div class="legend-item"><div class="legend-dot dot-past"></div>Passado</div>
                </div>
                <button type="submit" name="enviar" class="btn-reservar" onclick="javascript:valida()">Reservar</button>
            </div>
        </form>

        <?php else: ?>
            <div class="empty-state">Nenhum recurso cadastrado nesta categoria.</div>
        <?php endif; ?>

    <?php } // END mostrarsemana

    // ─── ENVIAR ────────────────────────────────────────────────
    if($enviar=isset($_GET["enviar"]));
    if($enviar=="sim"){
        if(isset($_POST["qualsemana1"])){
            $matricula=$_SESSION["matricula"];
            $prox_semana=$_POST["prox_semana"];
            $dia_atual=$_POST["dia_atual"];
            $_SESSION["categoria"]=$_POST["categoria"];
            $_SESSION["turno"]=$_POST["turno"];
            $cod_recurso=$_POST["cod_recurso"];
            $qual_semana1=$_POST["qualsemana1"];
            $_SESSION["cod_recurso"]=$cod_recurso;

            mysqli_select_db($link,'tcc');
            $qr1=$link->query("SELECT * FROM usuarios WHERE matricula='$matricula'");
            $ln1=$qr1->fetch_array();

            if($ln1["matricula"]==$matricula){
                $reservar=$_POST["reservar"];
                $dh=explode(" ",$reservar); $dma=explode("-",$dh[0]); $hms=explode(":",$dh[1]);
                $hora_inicio2=$hms[0]; $min_inicio=$hms[1];
                $salvar_data=$dh[0]." ".$hora_inicio2.":00:00";

                $sql="SELECT * FROM reservas WHERE datatempo='$salvar_data' AND cod_recurso='$cod_recurso'";
                $qr=$link->query($sql); $existe=0;
                while($link->query($sql)->fetch_array()) $existe++;

                $tempo=4;
                if(($min_inicio==00)&&($tempo>=1)&&($existe==0)){
                    if($cod_recurso!=0){
                        $con=mysqli_connect("localhost","u749688875_ceep","1234554123","u749688875_ceep");
                        if(mysqli_connect_errno()) echo "Failed: ".mysqli_connect_error();
                        $sql="INSERT INTO reservas (datatempo,cod_recurso,matricula) VALUES ('$salvar_data','$cod_recurso','$matricula')";
                        if(!mysqli_query($con,$sql)) die('Error: '.mysqli_error($con));
                        mysqli_close($con);
                        $_SESSION["cod_recurso"]=$cod_recurso; $_SESSION["qualsemana"]=$qual_semana1;
                        $_SESSION["categoria"]=$_POST["categoria"]; $_SESSION["turno"]=$_POST["turno"];
                        $_SESSION["opcao"]="reservou";
                        echo "<script>top.location.href='user1.php'</script>"; return;
                    } else { $_SESSION["opcao"]="erro"; echo "<script>top.location.href='user1.php'</script>"; return; }
                } else { $_SESSION["opcao"]="erro2"; echo "<script>top.location.href='user1.php'</script>"; return; }
            } else { $_SESSION["opcao"]="erro"; echo "<script>top.location.href='user1.php'</script>"; return; }
        } else { $_SESSION["opcao"]="erro"; echo "<script>top.location.href='user1.php'</script>"; return; }
    } else {
        if($env_cod_recurso=isset($_POST["env_cod_recurso"]));
        if($proxima=isset($_POST["proxima"]));
        if($anterior=isset($_POST["anterior"]));
        if(isset($_POST["categoria"])) $_SESSION["categoria"]=$_POST["categoria"];
        if(isset($_POST["turno"]))     $_SESSION["turno"]=$_POST["turno"];

        if(($env_cod_recurso)||($proxima)||($anterior)){
            $cod_recurso=$_POST["cod_recurso"]; $dia_atual=$_POST["dia_atual"];
            if($proxima){ $prox_semana=$_POST["prox_semana"]; $qual_semana=2; mostrarsemana($prox_semana,$cod_recurso,$qual_semana,$prox_semana,$dia_atual); }
            if($anterior){ $prox_semana=$_POST["prox_semana"]; $qual_semana=1; mostrarsemana($dia_atual,$cod_recurso,$qual_semana,$prox_semana,$dia_atual); }
            if(!$proxima&&!$anterior){ $qual_semana=$_SESSION["qualsemana"]; $prox_semana=$_POST["prox_semana"]; mostrarsemana($dia_atual,$cod_recurso,$qual_semana,$prox_semana,$dia_atual); }
        } else {
            if($codrecurso=isset($_GET["codrecurso"]));
            if($qualsemana=isset($_GET["qualsemana"]));
            if($codrecurso){
                $prox_semana=$_GET["prox_semana"]; $dia_atual=$_GET["dia_atual"];
                if($qualsemana==2) mostrarsemana($prox_semana,$codrecurso,$qualsemana,$prox_semana,$dia_atual);
                else               mostrarsemana($dia_atual,$codrecurso,$qualsemana,$prox_semana,$dia_atual);
            } else {
                date_default_timezone_set('America/Sao_Paulo');
                $tempo=date("Y-m-d"); $td=explode("-",$tempo);
                $dia=$td[2]; $mes=$td[1]; $ano=$td[0];
                $dia_da_semana2=date("w",mktime(0,0,0,$mes,$dia,$ano));
                if($dia_da_semana2==1){ $inicio=1; $iniciok=2; $inicio_prox=8; }
                if($dia_da_semana2==2){ $inicio=-2;$iniciok=3; $inicio_prox=7; }
                if($dia_da_semana2==3){ $inicio=-3;$iniciok=4; $inicio_prox=6; }
                if($dia_da_semana2==4){ $inicio=-4;$iniciok=5; $inicio_prox=5; }
                if($dia_da_semana2==5){ $inicio=-5;$iniciok=6; $inicio_prox=4; }
                if($dia_da_semana2==6){ $inicio=-6;$iniciok=7; $inicio_prox=3; }
                if($dia_da_semana2==0){ $inicio=0;              $inicio_prox=1; }
                $prox_semana=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+7,date("Y")));
                if(isset($_SESSION["cod_recurso"])){ $cod_recurso=$_SESSION["cod_recurso"]; $qualsemana=$_SESSION["qualsemana"]; }
                else { $cod_recurso="1"; $qualsemana="1"; }
                if(!isset($_SESSION["categoria"])){ $_SESSION["categoria"]="1"; $_SESSION["teste"]="1"; }
                if(!isset($_SESSION["turno"]))    { $_SESSION["turno"]="1"; }
                $tempo=date("Y-m-d");
                mostrarsemana($tempo,$cod_recurso,$qualsemana,$prox_semana,$tempo);
            }
        }
    }
?>
        </div><!-- /card-body -->
    </div><!-- /card -->
</div><!-- /page-body -->
</body>
</html>