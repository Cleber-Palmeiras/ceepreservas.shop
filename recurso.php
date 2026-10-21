<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<?php
include "functions.php"; // arquivo de funções.
session_checker(); // chama a função que verifica se a session iniciada da acesso à página. 
if ($_SESSION['tipo'] == 'a') {
    
} else {
    include_once "home.php";
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
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <title>Adicionar recurso</title>
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
<body class="blurBg-false" style="background-color:#EBEBEB">
  
<!-- Start Formoid form-->
<br>
<link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
<script type="text/javascript" src="cadastrar_files/formoid1/jquery.min.js"></script>
<form class="formoid-solid-blue" action="cadastrar_recurso.php" method="post" style="background-color:#FFFFFF;font-size:14px;
font-family:'Roboto',Arial,Helvetica,sans-serif;color:black;max-width:400px;min-width:500px">

<div class="title"><h2>Adicionar Recurso</h2></div>
  	<div class="element-input">
      <label class="title"><b>Nome</b><span class="required">
        </span></label>
              <div class="item-cont">
                <input class="large" type="text" name="nome" required="required" placeholder="Nome"/>
        <span class="icon-place"></span>

    </div>
  <br>
  <div class="element-radio"><label class="title"><b>Categoria</b><span class="required"></span>
  </label> <div class="column column1"><label><input type="radio" name="tipo" value="1" required="required"/><span>Datashow</span></label>
  <label><input type="radio" name="tipo" value="2" required="required"/><span>Laboratório</span></label>
  <label><input type="radio" name="tipo" value="3" required="required"/><span>Sala</span></label>
  <label><input type="radio" name="tipo" value="4" required="required"/><span>Outros</span></label>
</div><span class="clearfix"></span>
</div>
  <div class="submit"><input type="submit" value="Adicionar"/></div></form><p class="frmd"></script>
<!-- Stop Formoid form-->

</body>
</body>
<html>

