<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Poppins', sans-serif;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background: linear-gradient(135deg, #667eea, #764ba2);
}

/* Card */
.login-box{
    background:#fff;
    padding:40px;
    border-radius:15px;
    width:100%;
    max-width:380px;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
    text-align:center;
}

/* Logo */
.login-box img{
    width:120px;
    margin-bottom:20px;
}

/* Título */
.login-box h2{
    margin-bottom:20px;
    color:#333;
}

/* Inputs */
.input-group{
    margin-bottom:15px;
}

.input-group input{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
    transition:0.3s;
}

.input-group input:focus{
    border-color:#667eea;
    box-shadow:0 0 5px rgba(102,126,234,0.5);
}

/* Botão */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#667eea;
    color:#fff;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#5a67d8;
}

/* Link */
.link{
    margin-top:15px;
    display:block;
    font-size:14px;
}

.link a{
    text-decoration:none;
    color:#667eea;
}

/* Erro */
.erro{
    background:#fdecea;
    color:#b71c1c;
    padding:8px;
    border-radius:5px;
    margin-top:10px;
    font-size:13px;
}
</style>
</head>

<body>

<div class="login-box">

    <img src="css/logo.jpg" alt="Logo">

    <h2>Bem-vindo</h2>

    <form action="verifica_usuario.php" method="post">

        <div class="input-group">
            <input type="text" name="matricula" placeholder="CPF (somente números)" required onkeypress='return SomenteNumero(event)'>
        </div>

        <div class="input-group">
            <input type="password" name="senha" placeholder="Senha" required>
        </div>

        <?php 
        if (isset($_SESSION["login"]) && $_SESSION["login"] == "false") {
            echo "<div class='erro'>Dados inválidos</div>";
        }
        ?>

        <button type="submit">Entrar</button>

    </form>

    <div class="link">
        <a href="formulario_senha_perdida.html">Esqueceu a senha?</a>
    </div>

</div>

<script>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
        if (tecla==8 || tecla==0) return true;
        else return false;
    }
}
</script>

</body>
</html>