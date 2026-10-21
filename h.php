<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset = "UTF-8 ">
    </head>
</html>


<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }
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
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
   <title>CSS MenuMaker</title>
</head>
<body>
<section>
<body class="h2">
       <img src="css/logook.jpg" alt="Imagem Logo" >  
</body>
</section>

<div id='cssmenu'>
<ul>
   <li ><a href='home.php'>Menu Principal</a></li>
   <li><a href='formulario_cadastro.php'>Adicionar</a></li>
   <li><a href='#'>Editar</a></li>
   <li><a href='#'>Excluir</a></li>
   <li><a href='logout.php'>Sair do Sistema</a></li>
</ul>
</div>
<body class="blurBg-false" style="background-color:#EBEBEB">

<!-- Start Formoid form-->
<link rel="stylesheet" href="cadastrar_files/formoid1/formoid-solid-blue.css" type="text/css" />
<script type="text/javascript" src="modificar_senha_files/formoid1/jquery.min.js"></script>
<form class="formoid-solid-blue" method="post" action="senha.php" style="background-color:#FFFFFF;font-size:14px;
font-family:'Roboto',Arial,Helvetica,sans-serif;color:#34495E;max-width:480px;min-width:150px" method="post">
<div class="title"><h2>Alterar E-mail</h2></div>
  <div class="element-email"><label class="title"><span class="required"></span></label><div class="item-cont">
    <input class="large" type="email" name="email" required="required" placeholder="Email" value=<?php echo $_SESSION['email'];?>>
    <span class="icon-place"></span></div></div>
<div class="submit"><input type="submit" value="Alterar"/></div></form>
<!-- Stop Formoid form-->
</body>

</body>
<html>
