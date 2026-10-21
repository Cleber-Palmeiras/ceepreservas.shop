<?php
/* 
 EDIT.PHP
 Allows user to edit specific entry in database
*/

 // creates the edit record form
 // since this form is used multiple times in this file, I have made it a function that is easily reusable
 function renderForm($id, $nome_recurso, $status, $categoria , $error)

 {
 ?>

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
<body class="h2" style="background-color:#DCDCDC">
       <img src="css/logook1.jpg" alt="Imagem Logo" width="100%" height="18%"> 
</body>
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

<br>
<body class="blurBg-false" style="background-color:#EBEBEB">

<!-- Start Formoid form-->
<link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
<form class="formoid-solid-blue" method="post" action="" style="background-color:#FFFFFF;font-size:14px;
font-family:'Roboto',Arial,Helvetica,sans-serif;color:black;max-width:480px;min-width:150px" method="post">
<div class="title"><h2>Editar Recursos</h2></div>
<div class="item-cont" >
  <span class="icon-place"></span></div>
  <div class="item-cont" >

    <div class="element-input">
      <label class="title"><b>Nome</b><span class="required">
        </span></label>
              <div class="item-cont">
                <input class="large" type="text" name="nome" required="required" placeholder="Nome" value="<?php echo $nome_recurso; ?>">
        <span class="icon-place"></span>

    </div>
 
  <div class="element-radio"><label class="title">Status:<span class="required"></span>
  </label><div class="column column1">
    <?php if ($status == 'Disponivel') {?>
    <label><input type="radio" name="status" value="Disponivel" checked required="required"/><span>Disponivel</span></label>
    <label><input type="radio" name="status" value="Indisponivel"  required="required"/><span>Indisponivel</span></label>
    <?php } else {?> 
    <label><input type="radio" name="status" value="Disponivel" required="required"/><span>Disponivel</span></label>
    <label><input type="radio" name="status" value="Indisponivel" checked required="required"/><span>Indisponivel</span></label>
    <?php } ?>
  </div><span class="clearfix"></span>
</div>

<div class="element-radio"><label class="title">Categoria<span class="required"></span></label> 
  <div class="column column1">
        <?php if ($categoria == "1"){ ?>
              <label><input type="radio" name="categoria" value="1" checked required="required"/><span>Datashow</span></label>
              <label><input type="radio" name="categoria" value="2"  required="required"/><span>Laboratório</span></label>
              <label><input type="radio" name="categoria" value="3" required="required"/><span>Sala</span></label>
              <label><input type="radio" name="categoria" value="4" required="required"/><span>Outros</span></label>
        <?php } if ($categoria == "2"){ ?>
              <label><input type="radio" name="categoria" value="1"  required="required"/><span>Datashow</span></label>
              <label><input type="radio" name="categoria" value="2" checked required="required"/><span>Laboratório</span></label>
              <label><input type="radio" name="categoria" value="3" required="required"/><span>Sala</span></label>
              <label><input type="radio" name="categoria" value="4" required="required"/><span>Outros</span></label>
        <?php } if ($categoria == "3"){ ?>
              <label><input type="radio" name="categoria" value="1"  required="required"/><span>Datashow</span></label>
              <label><input type="radio" name="categoria" value="2"  required="required"/><span>Laboratório</span></label>
              <label><input type="radio" name="categoria" value="3" checked required="required"/><span>Sala</span></label>
              <label><input type="radio" name="categoria" value="4" required="required"/><span>Outros</span></label>
        <?php } if ($categoria == "4"){ ?>
              <label><input type="radio" name="categoria" value="1"  required="required"/><span>Datashow</span></label>
              <label><input type="radio" name="categoria" value="2"  required="required"/><span>Laboratório</span></label>
              <label><input type="radio" name="categoria" value="3"  required="required"/><span>Sala</span></label>
              <label><input type="radio" name="categoria" value="4" checked required="required"/><span>Outros</span></label>
        <?php } ?>
  </div>
<span class="clearfix"></span>
</div>
<div class="submit"><input name="submit" type="submit" value="Salvar"/></div></form>
<!-- Stop Formoid form-->
</body>

</body>
</html>
 <?php
 }
 include_once 'recursos.php';
 // connect to the database
 
 // check if the form has been submitted. If it has, process the form and save it to the database
 if (isset($_POST['submit']))
 { 
 // confirm that the 'id' value is a valid integer before getting the form data
 if (is_numeric($_SESSION['id']))
 {
 // get form data, making sure it is valid
 $id = $_SESSION['id'];
 $nome_recurso = $_POST['nome'];
 $status = $_POST['status'];
 $categoria = $_POST['categoria'];


 if ($nome_recurso == '' || $status == '' )
 {
 // generate error message
 $error = 'ERROR: Please fill in all required fields!';
 
 //error, display form
 renderForm($id, $nome_recurso, $status, $error);
 }
 else
 {
 // save the data to the database
 
    $recurso = new recursos;
    $recurso->editar($id,$nome_recurso,$status,$categoria);
    $recurso->VerificarRepeticao2();
    $recurso->atualiza();

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
 $id = $_GET['id'];
 $link = DB_Connect();
 mysqli_select_db($link, 'tcc');
 $result = "SELECT * from recursos WHERE cod_recurso=$id";

    $query = $link->query($result);
                           
    $row = $link->query($result) or die($link->error());	

    $result5 = $link->query($result) or die($link->error());	

 	$row = $query->fetch_array();
 
 // check that the 'id' matches up with a row in the databse
 if($row)
 {
 
 // get data from db
 $nome_recurso = $row['nome_recurso'];
 $status = $row['status'];
 $categoria = $row['categoria'];
 $_SESSION['id'] = $row['cod_recurso'];
 // show form
 renderForm($id, $nome_recurso, $status, $categoria, '');
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
 echo 'Error!';
 }
 }
?>