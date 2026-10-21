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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
   <link rel="stylesheet" href="css/normalize.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <title>Alterar senha</title>
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
   <li class='has-sub'><a href='#'>Reservas</a>
            <ul>
               <li><a href='user1.php'><span>Reservar</span></a>
                     
                </li>
               <li><a href='apagar.php'><span>Cancelar</span></a></li>
               <li><a href='reservas.php'><span>Minhas reservas</span></a></li>
               
            </ul>
   </li>
   <li class='active has-sub'><a href='#'>Meu Perfil</a>
            <ul>
               <li><a href='editar_usuario.php'><span>Dados pessoais</span></a></li>
               <li><a href='modificar_senha.php'><span>Alterar senha</span></a></li>
            </ul>
   </li>
   <li class='has-sub'><a href='logout.php'>Sair</a></li>
</ul>
</div>
</div><body class="blurBg-false" style="background-color:#EBEBEB">

<br>

<!-- Start Formoid form-->
<link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
<form class="formoid-solid-blue" method="post" action="senha.php" style="background-color:#FFFFFF;font-size:14px;font-family:'Roboto',Arial,Helvetica,sans-serif;color:black;max-width:480px;min-width:150px" method="post"><div class="title"><h2>Alterar senha</h2></div>
	<div class="element-password"><label class="title"><b>Senha atual</b><span class="required"></span></label><div class="item-cont"><input class="large" type="password" name="senha" required="required" placeholder="Senha atual"/><span class="icon-place"></span></div></div>
	<div class="element-password"><label class="title"><b>Nova senha</b><span class="required"></span></label><div class="item-cont"><input class="large" type="password" name="senhanova"  required="required" placeholder="Nova senha"/><span class="icon-place"></span></div></div>
	<div class="element-password"><label class="title"><b>Nova senha</b><span class="required"></span></label><div class="item-cont"><input class="large" type="password" name="senhanova2" required="required" placeholder="Digite novamente a nova senha"/><span class="icon-place"></span></div></div>
<div class="submit"><input type="submit" value="Enviar"/></div></form><p class="frmd"><a href="http://formoid.com/v29.php">contact form</a> Formoid.com 2.9</p>
<!-- Stop Formoid form-->

</body>

</body>
<html>
