<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<?php
if($_SESSION["cpf"]==""){
  echo("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ alert('Você ainda não está logado!.'); window.navigate('index.php'); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
  exit();
}

if($_SESSION["tipo"]!="p"){
  if ($_SESSION["tipo"]!="a"){
  echo("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ alert('Você não tem permissão para acessar essa página!.'); window.navigate('index.php'); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
  exit();
  }
}

$nome=$_SESSION["nome"];
?>

<!doctype html>
<html lang=''>
<head>
   <meta charset='utf-8'>
   <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/stilo.css" href="css/bootstrap-responsive">
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
   <title>Efetuar reserva</title>
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
   <li class='has-sub'><a href='#'>Recursos</a>
            <ul>
               <li><a href='recurso.php'><span>Adicionar</span></a></li>
               <li><a href='gerencia_recurso.php'><span>Gerenciar</span></a></li>
            </ul>
   </li>
   <?php } ?> 
   <li class='active has-sub'><a href='#'>Reservas</a>
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
  <div id="conteudo-left2">
      <?php include "user1_c.php";?>
  </div>

</div>
</body>
<html>
