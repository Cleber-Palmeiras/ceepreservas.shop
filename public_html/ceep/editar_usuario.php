<?php

if (!isset($_SESSION)) {
  session_start();
}

if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

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
if (isset($_SESSION["categoria"])){
  $_SESSION["categoria"] = "1";
  $_SESSION["cod_sala"] = "1";  
  $_SESSION["qualsemana"] = "1";
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
   <title>Dados Pessoais</title>
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
<body class="blurBg-false" style="background-color:#EBEBEB">

<?php 

 function renderForm($id, $nome, $sobrenome, $cpf, $matricula, $email , $error)

 {
?>
<br>
<!-- Start Formoid form-->
<link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
<form class="formoid-solid-blue" method="post" action="" style="background-color:#FFFFFF;font-size:14px;
font-family:'Roboto',Arial,Helvetica,sans-serif;color:black;max-width:480px;min-width:150px" method="post">
<div class="title"><h2>Dados pessoais</h2></div>
<!--<div class="item-cont" >
<div class="element-input"><label class="title">ID:<span class="required"></span></label><div class="item-cont">
    <input  size="2" type="text" name="id" required="required"  value="<?php echo $id;?>"readonly>
    <span class="icon-place"></span></div></div> -->
<div class="item-cont" >
<div class="element-input"><label class="title"><b>Nome</b><span class="required"></span></label><div class="item-cont">
    <input class="large" type="text" size="12" maxlength=12 name="nome" required="required" placeholder="Matricula" value="<?php echo $nome;?>">
    <span class="icon-place"></span></div></div>
    <br>
<div class="element-input"><label class="title"><b>Sobrenome</b><span class="required"></span></label><div class="item-cont">
    <input class="large" type="text" name="sobrenome" required="required" placeholder="Matricula" value="<?php echo $sobrenome;?>">
    <span class="icon-place"></span></div></div>
    <br>
<!--
<div class="element-input"><label class="title">Matrícula:<span class="required"></span></label><div class="item-cont">
    <input class="large" type="text" name="matricula" required="required" placeholder="Matricula" value="<?php echo $matricula;?>"readonly>
    <span class="icon-place"></span></div></div> -->

  <div class="element-input"><label class="title"><b>CPF</b><span class="required"></span></label><div class="item-cont">
    <input class="large" type="text" name="cpf" required="required" placeholder="CPF" value=<?php echo $cpf;?>>
    <span class="icon-place"></span></div></div>
    <br>
  <div class="element-email"><label class="title"><b>Email</b><span class="required"></span></label><div class="item-cont">
    <input class="large" type="email" name="email" required="required" placeholder="Email" value=<?php echo $email;?>>
    <span class="icon-place"></span></div></div></br>
    <br>
    <!--<a href='modificar_senha.php' ><CENTER>Clique aqui para alterar senha</CENTER></a></div> -->
<div class="submit"><input type="submit" name="submit" value="Salvar"/></div></form>
<!-- Stop Formoid form-->
</body>
</body>
<html>

<?php 
}

 include_once 'usuario.php';
 // connect to the database
 
 // check if the form has been submitted. If it has, process the form and save it to the database
 if (isset($_POST['submit']))
 { 
 // confirm that the 'id' value is a valid integer before getting the form data
 if (is_numeric($_SESSION['usuario_id']))
 {
 // get form data, making sure it is valid
 $id = $_SESSION['usuario_id'];
 $nome = $_POST['nome'];
 $sobrenome = $_POST['sobrenome'];
 $cpf = $_POST['cpf'];
 //$matricula = $_POST['matricula'];
 $email = $_POST['email'];
   

 // check that firstname/lastname fields are both filled in
 if ($nome == '' || $sobrenome == '' || $cpf == ''  || $email == '')
 {
 // generate error message
 $error = 'ERROR: Please fill in all required fields!';
 
 //error, display form
 renderForm($id, $nome, $sobrenome, $cpf , $matricula , $email, $error);
 }
 else
 {
 // save the data to the database
    
    $user = new usuario;
    $user->editarperfil($id,$nome,$sobrenome, $cpf, $email);
    $valor=$user->VerificarRepeticao3();
    if ($valor==0){
      $user->atualizaperfil(); 

      $_SESSION['nome'] = $nome;
      $_SESSION['cpf'] = $cpf;
      $_SESSION['email'] = $email;
      
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
 
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }
 $id = $_SESSION["usuario_id"];
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
 
 // show form
 renderForm($id, $nome, $sobrenome, $cpf, $matricula, $email, '');
 }
 else
 // if no match, display result
 {
 echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                       swal ({
                                title:'',
                                text:'Sem resultados',
                                type:''
                                },
                                function(){
                                  top.location.href = 'editar_usuario.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");

 }
 
 }
?>