<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
      if (tecla==8 || tecla==0) return true;
  else  return false;
    }
}
</script>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Tela de Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="blurBg-false" style="background-color:#EBEBEB">

<!-- Start Formoid form-->
<link rel="stylesheet" href="login-files/formoid1/formoid-solid-blue.css" type="text/css" />
<script type="text/javascript" src="formoid_files/formoid1/jquery.min.js"></script>
<form class="formoid-solid-blue" action="verifica_usuario.php" style="background-color:#ffffff;font-size:14px;font-family:'Roboto',Arial,Helvetica,sans-serif;color:#34495E;max-width:480px;min-width:150px" method="post"><div class="title"><h2>Login</h2></div class="center">
 
  <h1><img src="css/logo.jpg" alt="Imagem Logo"  title = "Logo CEEP" width="50%" ></h1>
  <div class="element-input"><label class="title"><span class="required"></span></label><div class="item-cont"><input class="large" type="text" name="matricula" onkeypress='return SomenteNumero(event)' required="required" pattern="[0-9]+$" placeholder="CPF (Somente números)"/><span class="icon-place"></span></div></div>
  <div class="element-password"><label class="title"><span class="required"></span></label><div class="item-cont"><input class="large" type="password" name="senha" value="" required="required" placeholder="Senha"/><span class="icon-place"></span>
  </div><?php 
    if (isset($_SESSION["login"])){
    if ($_SESSION["login"] == "false") { ?><br><div 
        style="display: block;
        background-color: #FBDBCF;
        text-shadow: none;
        border: none;
        padding: 5px;
        border-radius: 5px;
        margin-left: 0px;
        color: #900;
        line-height: 1;"> <font  ><?php echo "Dados inválidos"; } }?></div>
  <div style="text-align: right;"> <font face='arial' size='2' color='black'><br> <a href='formulario_senha_perdida.html' >Recuperar senha</a></div><br>
<div class="submit"><input type="submit" value="Entrar"/></div></form><p class="frmd"><script type="text/javascript" src="formoid_files/formoid1/formoid-solid-blue.js"></script>
<!-- Stop Formoid form-->
</body>
</html>
