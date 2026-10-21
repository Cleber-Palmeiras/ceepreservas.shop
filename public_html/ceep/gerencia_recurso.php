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
   <link rel="stylesheet" href="styles.css">
   <link rel="stylesheet" href="css/normalize.css"> 
   <link rel="stylesheet" type="text/css" href="datatables/dataTables.bootstrap.css">
   <link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <title>Recursos</title>
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
<divclass="h2" id='cssmenu'>
<ul>
   <li class='has-sub'><a href='home.php'>Inicio</a></li>
   <?php if ($_SESSION["tipo"]=="a"){ ?>
   <li class='has-sub' ><a href='#'>Usuarios</a>
            <ul>
               <li><a href='formulario_cadastro.php'><span>Adicionar</span></a></li>
               <li><a href='view-paginated.php'><span>Gerenciar</span></a></li>
                <li><a href='excluir_reservas.php'><span>Reservas</span></a></li> 
               
            </ul>
         </li>
   <li class='active has-sub'><a href='#'>Recursos</a>
            <ul>
               <li><a href='recurso.php'><span>Adicionar</span></a></li>
               <li><a href='gerencia_recurso.php'><span>Gerenciar</span></a></li>
            </ul>
   </li>
   <?php } ?> 
   <li class='has-sub'><a href='#'>Reservas</a>
            <ul>
               <li><a href='user1.php'><span>Reservar</span></a>
                     
                </li>
               <li><a href='apagar.php'><span>Cancelar</span></a></li>
               <li><a href='reservas.php'><span>Minhas reservas</span></a></li>
               
            </ul>
   </li>
   <li class='has-sub'><a href='#'>Meu Perfil</a>
            <ul>
               <li><a href='editar_usuario.php'><span>Dados pessoais</span></a></li>
               <li><a href='modificar_senha.php'><span>Alterar senha</span></a></li>
            </ul>
   </li>
   <li class='has-sub'><a href='logout.php'>Sair</a></li>
</ul>
</div>
  <div id="conteudo-left5">
      
  <?php 
    require_once('conecta.php');

  // connect to the database
  $link = DB_Connect();
  ?>
<br>
        <div >
                    
                    <span class="t_coluna">Gerenciar Recursos</span>             
                  
                    <!-- <div class="celebrar_cols"></div> -->
        </div>
        <br>

  <CENTER>
        
                    <!-- style="background-color:white;" --> 
  <?php 
  $fonte[3] = "<font face='arial' size='3'><center>";
  $cor_fonte["preta"] = "<font color='#000000'>";
/* 
  VIEW.PHP
  Displays all data from 'players' table
*/
  // get results from database

  mysqli_select_db($link, 'tcc');
  $result = "SELECT * from recursos ORDER BY nome_recurso";

    $query = $link->query($result);
    

    $result5 = $link->query($result) or die($link->error());  
    
  ?>
  <?php 
  $cor_fonte["preta"] = "<font color='#000000'>";
  echo $cor_fonte["branca"] = "<font color='white'>";
  
    //echo $cor_fonte["preta"];
  
    echo $fonte[3];
    //echo $cor_fonte["preta"];
    echo $cor_fonte["preta"];
?>

  <table  border='1' id = "example" bordercolor="#BEBEBE" width='60%' class = "table table-bordered table-striped">
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

  <?php 
  while($row = $query->fetch_array()) { ?>
    <?php 
    echo "<tr color='black'>";
    //echo '<td align="center" width="5%" bgcolor="#3498DB" >' . $row['usuario_id'] . '</td>';
    echo '<td  width="20%" bgcolor="white">' . $row['nome_recurso'] .'</td>';
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
    echo '<td width="5%" bgcolor="white" ><a href="edit_recurso.php?id=' . $row['cod_recurso'] . '"><CENTER><img allign="center" src="css/editar.png" title="Editar" width="25%" height="20%"<\CENTER></a></td>';
    echo '<td width="5%" bgcolor="white" ><a href="delete_recurso.php?id=' . $row['cod_recurso'] . '"><CENTER><img allign="center" src="css/excluir.png" title="Excluir" width="25%" height="20%"<\CENTER></a></td>';
    echo "</tr>"; 
  }
    ?>
  </tbody>
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