<?php 
  if (!isset($_SESSION)) {
    session_start();
   }

$_SESSION['tipo'] = 'a'
?>

<!doctype html>
<html lang=''>
<head>
   <meta charset='utf-8'>
   <meta charset=iso-8859-1" />
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="styles.css">
   <link rel="stylesheet" href="css/normalize.css"> 
   <link rel="stylesheet" type="text/css" href="datatables/dataTables.bootstrap.css">
   <link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
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
<divclass="h2" id='cssmenu'>
<ul>
   <li class='has-sub'><a href='home.php'>Inicio</a></li>
   <?php if ($_SESSION["tipo"]=="a"){ ?>
   <li class='active has-sub' ><a href='#'>Usuarios</a>
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
<br>
<!-- Start Formoid form-->
<link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
<script type="text/javascript" ></script>
<form class="formoid-solid-blue" method="post" action="cadastrar.php" style="background-color:#FFFFFF;font-size:14px;
font-family:'Roboto',Arial,Helvetica,sans-serif;color:black;max-width:600px;min-width:300px" method="post">
<div class="title"><h2>Adicionar Usuário</h2></div>
  <div class="element-input"><label class="title"><b> Nome</b><span class="required"></span>
  </label><div class="item-cont"><input class="large" size="12" maxlength=12 type="text" name="nome" required="required" placeholder="Nome" value="<?php if (isset($_SESSION["n"])){ echo $_SESSION["n"];}?>"/>
<span class="icon-place"></span></span></div>
<br>
  <div class="element-input"><label class="title"><b> Sobrenome</b><span class="required"></span>
  </label><div class="item-cont"><input class="large" type="text" size="30" maxlength=30 name="sobrenome" required="required" placeholder="Sobrenome" value="<?php if (isset($_SESSION["s"])){ echo $_SESSION["s"];}?>"/>
<span class="icon-place"></span></span></div>
<br>
  <div class="element-input"><label class="title"><b>CPF</b><span class="required"></span></label><div class="item-cont">
    <input class="large" type="text" name="cpf" required="required" placeholder="CPF" value="<?php if (isset($_SESSION["c"])){ echo $_SESSION["c"];}?>"/><span class="icon-place"></span></div></div>

  <div class="element-email"><br><label class="title"><b>Email</b><span class="required"></span></label><div class="item-cont">
    <input class="large" type="email" name="email" required="required" placeholder="Email" value="<?php if (isset($_SESSION["e"])){ echo $_SESSION["e"];} ?>">
    <span class="icon-place"></span></div></div>

<br>  
  <div class="element-radio"><label class="title"><b>Tipo de Usuario</b><span class="required"></span>
    <?php if (isset($_SESSION["t"])) { if ($_SESSION["t"] == "a"){?>
    <label><input type="radio" name="tipo" value="a" checked required="required"/><span>Administrador</span></label>
    <label><input type="radio" name="tipo" value="p"  required="required"/><span>Professor</span></label>
    <?php } else {?> 
    <label><input type="radio" name="tipo" value="a" required="required"/><span>Administrador</span></label>
    <label><input type="radio" name="tipo" value="p" checked required="required"/><span>Professor</span></label>
    <?php } } else {?>
    <label><input type="radio" name="tipo" value="a" required="required"/><span>Administrador</span></label>
    <label><input type="radio" name="tipo" value="p" required="required"/><span>Professor</span></label>
    <?php } ?>
  </div><span class="clearfix"></span>
</div>
<div class="submit"><input type="submit" value="Adicionar"/></div></form>
<!-- Stop Formoid form-->
</body>

</body>
<html>
