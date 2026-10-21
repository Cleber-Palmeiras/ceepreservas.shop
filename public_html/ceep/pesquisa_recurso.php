<?php 
  if (!isset($_SESSION)) {
    session_start();
   }

include "functions.php"; // arquivo de funções.
session_checker(); // chama a função que verifica se a session iniciada da acesso à página. 
if ($_SESSION['tipo'] == 'a') {
    
} else {
    echo "Acesso não permitido!";
    exit();
}
?>

<!doctype html>
<html lang=''>
<head>
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
   <link rel="stylesheet" href="css/normalize.css"> 
   <link rel="stylesheet" type="text/css" href="datatables/dataTables.bootstrap.css">
   <link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
<script type="text/javascript" src="modificar_senha_files/formoid1/jquery.min.js"></script>
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
   <title>Usuários</title>
</head>
<body>
<section>
<?php if ($_SESSION["tipo"]=="p"){
?>
<body class="h2" style="background-color:#DCDCDC">
       <img src="css/logook.jpg" alt="Imagem Logo" width="100%" height="10%" > 
</body>
<?php } else {
  ?>

<body class="h2" style="background-image:#DCDCDC">
       <img src="css/logook1.jpg" alt="Imagem Logo" width="100%" height="10%" > 
</body>
<?php } 
?>
</section>

<div id='cssmenu'>
<ul>
   <li ><a href='home.php'>Inicio</a></li>
   <?php if ($_SESSION["tipo"]=="a"){ ?>
   <li  class='active has-sub'><a href='#'>Usuarios</a>
            <ul>
               <li><a href='formulario_cadastro.php'><span>Adicionar</span></a></li>
               <li><a href='view-paginated.php'><span>Gerenciar</span></a></li>

            </ul>
         </li>
   <li  class='has-sub'><a href='#'>Recursos</a>
            <ul>
               <li><a href='recurso.php'><span>Adicionar</span></a></li>
               <li><a href='gerencia_recurso.php'><span>Gerenciar</span></a></li>

            </ul>
   </li>
   <?php } ?> 
   <li class='has-sub'><a href='#'>Reserva</a>
            <ul>
               <li><a href='user1.php'><span>Reservar</span></a></li>
               <li><a href='apagar.php'><span>Cancelar</span></a></li>
            </ul>
   </li>
   <li class='has-sub'><a href='#'>Meu Perfil</a>
            <ul>
               <li><a href='editar_usuario.php'><span>Dados pessoais</span></a></li>
               <li><a href='modificar_senha.php'><span>Alterar senha</span></a></li>
            </ul>
   </li>
   <li class='has-sub'><a href='ajuda.php'>Ajuda</a></li>
   <li class='has-sub'><a href='logout.php'>Sair</a></li>
</ul>
</div>

<body class="blurBg-false" style="background-color:#EBEBEB">

</body>
  <?php
  $fonte[3] = "<font face='arial' size='3'><center>";
  $cor_fonte["preta"] = "<font color='#000000'>";
/* 
  VIEW.PHP
  Displays all data from 'players' table
*/
?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
</head>
<body>
  
  <div id="conteudo-left5">
  <br>
        <div >
                    
                    <span class="t_coluna">Gerenciar usuários</span>             
                  
                    <!-- <div class="celebrar_cols"></div> -->
        </div>
        <br>

  <?php 
  
  require_once('conecta.php');
  $pesquisa = preg_replace('/[^[:alnum:]_]/', '',$_POST["pesquisa"]);
  // connect to the database
  $link = DB_Connect();

  $pesquisa = mysqli_real_escape_string($link,$pesquisa);    
  // number of results to show per page
  
  // figure out the total pages in the database
  mysqli_select_db($link, 'tcc');
  $sql = "SELECT * from salas where nome_sala='$pesquisa' OR status='$pesquisa' OR categoria='$pesquisa' 
  ORDER BY nome_sala"; 
    $query = $link->query($sql);
                           
    $result5 = $link->query($sql) or die($link->error()); 
    ?> 
  <?php 
  $cor_fonte["preta"] = "<font color='#000000'>";
  echo $cor_fonte["branca"] = "<font color='white'>";
  
    //echo $cor_fonte["preta"];
  
    echo $fonte[3];
    //echo $cor_fonte["preta"];
    echo $cor_fonte["preta"];
?>
  <!--<table border='1' bordercolor=blue width='70%' id="example">-->
  <!--<FORM align="center" method="post" action="pesquisa.php"> 
  <INPUT name="pesquisa"> <BUTTON>Pesquisar</BUTTON></FORM><br>
  <br> -->
  <b>
  <table border='0' width='60%' >
  <FORM align="right" method="post" action="pesquisa.php"> 
  <a href='gerencia_recurso.php'> Listar todos</a>&nbsp;&nbsp;
  <INPUT name="pesquisa"> <BUTTON>Pesquisar</BUTTON></FORM>
</table>
  <br>
  <b>
  <table  border='1' bordercolor="#BEBEBE" width='60%' class = "table table-bordered table-striped">
  <thead>
    <tr><!--<th class="site_name" bgcolor="#3498DB">ID</th>-->
      <th bgcolor="#3498DB">Nome </th>
      <th bgcolor="#3498DB">Categoria </th>
      <th bgcolor="#3498DB">Status </th>
      <th bgcolor="#3498DB">Editar </th>
      <th bgcolor="#3498DB">Excluir </th>
    </tr>
  </thead> 
    <tbody>
  </b>
  <?php 
  while($row = $query->fetch_array()) { ?>
    <?php 
    echo "<tr color='black'>";
    //echo '<td align="center" width="5%" bgcolor="#3498DB" >' . $row['usuario_id'] . '</td>';
    echo '<td  width="20%" bgcolor="white">' . $row['nome_sala'] .'</td>';
    if ($row['categoria'] == "1"){
      $categoria  = "Datashow";
    }
    else if ($row['categoria'] == "2"){
      $categoria  = "Laboratório";
    }
    else if ($row['categoria'] == "3"){
      $categoria  = "Sala";
    }
    else if ($row['categoria'] == "4"){
      $categoria  = "Outros";
    }
    
    echo '<td width="10%" bgcolor="white">' . $categoria . '</td>';
    echo '<td  width="10%" bgcolor="white">' . $row['status'] . '</td>';
    echo '<td width="5%" bgcolor="white" ><a href="edit_recurso.php?id=' . $row['cod_sala'] . '"><CENTER><img allign="center" src="css/editar.png" title="Editar" width="25%" height="20%"<\CENTER></a></td>';
    echo '<td width="5%" bgcolor="white" ><a href="delete_recurso.php?id=' . $row['cod_sala'] . '"><CENTER><img allign="center" src="css/excluir.png" title="Excluir" width="25%" height="20%"<\CENTER></a></td>';
    echo "</tr>"; 
  }
    ?>
  </tbody>
  <script type="text/javascript" charset="utf8" src="jquery.min.js"></script>
  <script type="text/javascript" src = "datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src = "datatables/dataTables.bootstrap.min.js"></script>
  <script>
  $(function(){
    $("#example").DataTable();
  })
  </script>

</CENTER>
</body>
</html>
  </div>

</body>
</html>