<?php
include "functions.php"; // arquivo de funções.
session_checker(); // chama a função que verifica se a session iniciada da acesso à página. 
if ($_SESSION['tipo'] == 'a') {
    
} else {
    echo "Acesso não permitido!";
    exit();
}
?>

<?php
/* 
 EDIT.PHP
 Allows user to edit specific entry in database
*/

 // creates the edit record form
 // since this form is used multiple times in this file, I have made it a function that is easily reusable
 function renderForm($id, $nome, $sobrenome, $cpf, $matricula, $email , $tipo, $error)

 {
 ?>
<!doctype html>
<html lang=''>
<head>
   <meta charset='utf-8'>
   <link rel="stylesheet" href="styles.css">
   <link rel="stylesheet" href="css/normalize.css">
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <title>Editar Usuário</title>
</head>
<body>
<section>
<body class="h2" style="background-color:#DCDCDC">
       <img src="css/logook1.jpg" alt="Imagem Logo" width="100%" height="18%"> 
</body>
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
<form class="formoid-solid-blue" method="post" action="" style="background-color:#FFFFFF;font-size:14px;
font-family:'Roboto',Arial,Helvetica,sans-serif;color:black;max-width:600px;min-width:300px" method="post">
<div class="title"><h2>Editar dados de usuários</h2></div>
<div class="item-cont" >
<label class="title"><span class="required"></span></label>
    <div class="item-cont">
      <input  size="2" type="hidden" name="id" required="required"  value="<?php echo $id;?>"readonly>
    <span class="icon-place"></span></div></div>
<div class="item-cont" >
 <div class="element-input"><label class="title"><b>Nome</b><span class="required"><div class="item-cont"></span>
  </label><div class="item-cont">
   <span class="icon-place"></span></div>
 </div>
  <input class="large" type="text" name="nome" required="required" placeholder="Nome" value="<?php echo $nome; ?>">

  <div class="element-input"><br><label class="title"><b>Sobrenome</b><span class="required"></span>
  </label><div class="item-cont">
   <span class="icon-place"></span></div></div>
  <input class="large" type="text" name="sobrenome" required="required" placeholder="Sobrenome" value="<?php echo $sobrenome; ?>">
  
   <div class="element-input"><br><label class="title"><b>Matrícula</b><span class="required"></span>
  </label><div class="item-cont">
  <input class="large" type="text" name="matricula" required="required" placeholder="Matrícula" value="<?php echo $matricula; ?>">
  <span class="icon-place"></span></div></div>
  <div class="element-input"><br><label class="title"><b>CPF</b><span class="required"></span></label><div class="item-cont">
    <input class="large" type="text" name="cpf" required="required" placeholder="CPF" value="<?php echo $cpf; ?>">
    <span class="icon-place"></span></div></div>
  <div class="element-email"><br><label class="title"><b>Email</b><span class="required"></span></label><div class="item-cont">
    <input class="large" type="email" name="email" required="required" placeholder="Email" value="<?php echo $email; ?>">
    <span class="icon-place"></span></div></div>
  <div class="element-radio"><br><label class="title"><b>Tipo de Usuario</b><span class="required"></span>
  </label><div class="column column1">
    <?php if ($tipo == 'a') {?>
    <label><input type="radio" name="tipo" value="a" checked required="required"/><span>Administrador</span></label>
    <label><input type="radio" name="tipo" value="p"  required="required"/><span>Professor</span></label>
    <?php } else {?> 
    <label><input type="radio" name="tipo" value="a" required="required"/><span>Administrador</span></label>
    <label><input type="radio" name="tipo" value="p" checked required="required"/><span>Professor</span></label>
    <?php } ?>
  </div><span class="clearfix"></span>
</div>
<div class="submit"><input name="submit" type="submit" value="Salvar"/></div></form>
<!-- Stop Formoid form-->
</body>

</body>
</html>
 <?php
 }

 include_once 'usuario.php';
 // connect to the database
 
 // check if the form has been submitted. If it has, process the form and save it to the database
 if (isset($_POST['submit']))
 { 
 // confirm that the 'id' value is a valid integer before getting the form data
 if (is_numeric($_POST['id']))
 {
 // get form data, making sure it is valid
 $id = $_POST['id'];
 $nome = $_POST['nome'];
 $sobrenome = $_POST['sobrenome'];
 $cpf = $_POST['cpf'];
 $matricula = $_POST['matricula'];
 $email = $_POST['email'];
 $tipo = $_POST['tipo'];
 
 
 // check that firstname/lastname fields are both filled in
 if ($nome == '' || $tipo == '' || $sobrenome == '' || $cpf == '' || $matricula == '' || $email == '')
 {
 // generate error message
 $error = 'ERROR: Please fill in all required fields!';
 
 //error, display form
 renderForm($id, $nome, $sobrenome, $cpf , $matricula , $email, $tipo, $error);
 }
 else
 {
 // save the data to the database
 
    $user = new usuario;
    $user->editar($id,$matricula,$nome,$sobrenome, $cpf, $email, $tipo);
    $valor=$user->VerificarRepeticao2();
    if ($valor == 0){
      $user->atualiza();  
    }
    
 }
 }
 else
 {
 // if the 'id' isn't valid, display an error
 echo 'Error';
 }
 }
 else
 // if the form hasn't been submitted, get the data from the db and display the form
 {
 // get the 'id' value from the URL (if it exists), making sure that it is valid (checing that it is numeric/larger than 0)
 if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)
 {
 // query db
 $id = (int)$_GET['id'];
 $link = DB_Connect();
 mysqli_select_db($link, 'tcc');
 $result = "SELECT * from usuarios WHERE usuario_id=$id";

    $query = $link->query($result);
                           
    $row = $link->query($result) or die($link->error());  

    $result5 = $link->query($result) or die($link->error());  

  $row = $query->fetch_array();
 
 // check that the 'id' matches up with a row in the databse
 if($row)
 {
 
 // get data from db
 $nome = $row['nome'];
 $sobrenome = $row['sobrenome'];
 $cpf = $row['cpf'];
 $matricula = $row['matricula'];
 $email = $row['email'];
 $tipo = $row['tipo'];
 $_SESSION["mat"] = $row['matricula'];
 
 // show form
 renderForm($id, $nome, $sobrenome, $cpf, $matricula, $email, $tipo, '');
 }
 else
 // if no match, display result
 {
 echo "No results!";
 }
 }
 else
 // if the 'id' in the URL isn't valid, or if there is no 'id' value, display an error
 {
 echo 'rror!';
 }
 }
?>