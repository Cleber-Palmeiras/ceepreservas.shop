<?php 
  if (!isset($_SESSION)) {
    session_start();
   }

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
   <title>Usuários</title>
</head>

  <?php 
    require_once('conecta.php');

  // connect to the database
  $link = DB_Connect();
  ?>
<br>
        <div >
                    
                    <span class="t_coluna">Gerenciar usuários</span>             
                  
                    <!-- <div class="celebrar_cols"></div> -->
        </div>
     

  <CENTER>
        
                    <!-- style="background-color:white;" --> 
  <?php 
  $fonte[3] = "<font face='helvetica' size='3'><center>";
  $cor_fonte["preta"] = "<font color='#000000'>";
/* 
  VIEW.PHP
  Displays all data from 'players' table
*/
  // get results from database
  mysqli_select_db($link, 'tcc');
  $result = "SELECT * from usuarios ORDER BY nome";

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
  <!--<table border='1' bordercolor=blue width='70%' id="example">-->
  <!--<FORM align="center" method="post" action="pesquisa.php"> 
  <INPUT name="pesquisa"> <BUTTON>Pesquisar</BUTTON></FORM><br>
  <br> -->
  <b>
  
  <br>
  </b>
  <table  border='1' id = "example" bordercolor="#BEBEBE" width='100%' class = "table table-bordered table-striped">
  <thead>
    <tr><!--<th class="site_name" bgcolor="#3498DB">ID</th>-->
      <th bgcolor="#3498DB">Nome </th>
      <th bgcolor="#3498DB">CPF </th>
      <th bgcolor="#3498DB">Matricula </th><th bgcolor="#3498DB">Email</th>
      <th bgcolor="#3498DB">Tipo</th><th bgcolor="#3498DB">Editar </th><th bgcolor="#3498DB">Excluir </th></tr>
  </thead> 
    <tbody>

  <?php 
  while($row = $query->fetch_array()) { ?>
    <?php 

    echo "<tr color='black'>" ;
    //echo '<td align="center" width="5%" bgcolor="#3498DB" >' . $row['usuario_id'] . '</td>';
    echo '<td  width="20%" bgcolor="white">' . $row['nome'] . ' '. $row['sobrenome'] .'</td>';
    echo '<td width="10%" bgcolor="white">' . $row['cpf'] . '</td>';
    echo '<td  width="10%" bgcolor="white">' . $row['matricula'] . '</td>';
    echo '<td  width="20%" bgcolor="white">' . $row['email'] . '</td>';
    if ($row['tipo'] == "p"){
      echo '<td width="10%" bgcolor="white">' . 'Professor' . '</td>';  
    }
    else {
      echo '<td width="10%" bgcolor="white">' . 'Administrador' . '</td>';   
    }
    echo '<td width="5%" bgcolor="white" ><a href="edit.php?id=' . $row['usuario_id'] . '"><CENTER><img allign="center" src="css/editar.png" title="Editar" width="25%" height="20%"<\CENTER></a></td>';
    echo '<td width="5%" bgcolor="white" ><a href="delete.php?id=' . $row['usuario_id'] . '"><CENTER><img allign="center" src="css/excluir.png" title="Excluir" width="25%" height="20%"<\CENTER></a></td>';
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