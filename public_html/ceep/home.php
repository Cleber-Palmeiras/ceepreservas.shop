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
  $_SESSION["cod_recurso"] = "1";  
  $_SESSION["qualsemana"] = "1";
}

?>

<!doctype html>
<html lang=''>
<head>
   <meta charset='utf-8'>
   <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/stilo.css"> 
   <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
   <title>Página principal</title>
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
   <li class='active has-sub'><a href='home.php'>Inicio</a></li>
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
   <li class='has-sub'><a href='#'>Meu Perfil</a>
            <ul>
               <li><a href='editar_usuario.php'><span>Dados pessoais</span></a></li>
               <li><a href='modificar_senha.php'><span>Alterar senha</span></a></li>
            </ul>
   </li>
   <li class='has-sub'><a href='logout.php'>Sair</a></li>
</ul>
</div>

<body class="main" style="background-image: url(css/inicial.jpg); " class="blurBg-false" >
  
</br>
  <div id="conteudo-left1">
    <?php $fonte[4] = "<font face='helvetica' size='4'>"; ?>
    <?php $fonte[2] = "<font face='helvetica' size='2'>"; ?>
    <b> <?php echo $fonte[4]; echo "Olá, ", $_SESSION["nome"] , ".  "?>
    <?php
                        $tempo4=$_SESSION["data_ultimo_login"];
                        $tempo4=explode(" ",$tempo4);
                        $data=explode("-",$tempo4[0]);
                        $hora=explode(":",$tempo4[1]);
                        
                        if (($data[2] == 00)){
                          echo "seja bem vindo, este é o seu primeiro acesso!";
                        }

                        else {
                          echo "Seu último acesso foi em: ";
                          echo $data[2];
                          echo("/");
                          echo $data[1];
                          echo("/");
                          echo $data[0];
                          echo " às ";
                          echo $hora[0]; echo(":"); echo $hora[1]; 
                          echo ".";  
                        }
                        
                      ?>   
    </b> 
  </div>
  <br>
  <?php echo $fonte[2]; ?>
  <?php if ($_SESSION["tipo"] == "p"){ ?>
   <div id="conteudo-left">
      <?php include "hoje.php";?>
  </div>
  <div id="conteudo-left">
      <?php include "listareserva.php";?>
  </div>

  <?php } else { ?>
  <div id="conteudo-left">
      <?php include "listahoje.php";?>
  </div>
  <div id="conteudo-left">
      <?php include "estaorecursos.php";?>
  </div>
  <?php } ?>
</body>
</body>
<html>
