<html >
<meta charset='utf-8'>
<script src="sweetalert-dev.min.js"/> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />

  
<?php 
  if (!isset($_SESSION)) {
    session_start();
   }
  if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

?>

<?php

require_once('conecta.php');

class usuario {

	private $nome;
	private $cpf;
	private $email;
	private $tipo_usuario;
	private $senha;
    private $senha1;
	private $matricula;
    private $id; 
    private $sobrenome;


function Conecta(){
	$link= DB_Connect();
    return $link;
}


function desconecta($link){
    DB_Close($link);
    return $link;
}

function Cadastro($matricula,$nome,$sobrenome,$cpf,$email,$tipo_usuario){
	$this->matricula = $matricula;
	$this->nome = $nome;
    $this->sobrenome = $sobrenome;
	$this->cpf = $cpf;
	$this->email = $email;
	$this->tipo_usuario = $tipo_usuario;

}

function senha ($id,$senha,$senha1){
    $this->senha = $senha;
    $this->id = $id;
    $this->senha1 = $senha1;
}

function codigo ($id){
    $this->id = $id;
}

function excluir (){

$link = $this->Conecta();

$sql1 = "SELECT * FROM usuarios WHERE usuario_id='$this->id'";
                                        $query = $link->query($sql1);
                                        $resultado1 = $link->query($sql1) or die($link->error());
                                        $linha1 = $query->fetch_array();
                                        $matricula = $linha1["matricula"];

$sql = "DELETE FROM usuarios WHERE usuario_id=$this->id";
        $query = $link->query($sql);
        if ($link->query($sql) == TRUE) {

            $sql = "DELETE FROM reservas WHERE matricula=$matricula";
            $query = $link->query($sql);
        if ($link->query($sql) == TRUE) {            

             echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                       swal ({
                                title:'',
                                text:'Exclusão realizada com sucesso!',
                                type:''
                                },
                                function(){
                                  top.location.href='view-paginated.php'
                                }); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
                              
                               if ($_SESSION["usuario_id"] == $this->id){
                                    session_destroy();
                                    include "index.php";
                                 }

                                else {
                                    include "view-paginated.php";        
                                }
                              
        } else {
            echo "Não foi-se";
        }
       } else {
            echo "Não foi-se";
        } 

}
function alterarsenha (){

    $link = $this->Conecta();
    mysqli_select_db($link, 'tcc'); 

    $sql = "UPDATE usuarios SET senha='$this->senha1' WHERE usuario_id='$this->id' AND senha='$this->senha'";

    if ($link->query($sql) == "TRUE") {
        
        $sql = mysqli_query($link, "SELECT * FROM usuarios WHERE usuario_id = '$this->id' and senha='$this->senha1'" ) or print mysql_error();

                    if(mysqli_num_rows($sql)>0) {
                         echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Senha modificada',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'modificar_senha.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

                        include "modificar_senha.php";
                    }
                    
                    else { 
                          echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Senha inválida, tente novamente!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'modificar_senha.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                          include "modificar_senha.php";
                    }


    } else {
        echo "Senha inválida: " . $link->error;
    }

}
function editar($id,$matricula,$nome,$sobrenome,$cpf,$email,$tipo_usuario){

    $this->id = $id;
    $this->matricula = $matricula;
    $this->nome = $nome;
    $this->sobrenome = $sobrenome;
    $this->cpf = $cpf;
    $this->email = $email;
    $this->tipo_usuario = $tipo_usuario;
}

function editarperfil($id,$nome,$sobrenome,$cpf,$email){

    $this->id = $id; 
    $this->nome = $nome;
    $this->sobrenome = $sobrenome;
    $this->cpf = $cpf;
    $this->email = $email;
}

function InserirValor (){
    $_SESSION["n"] = $this->nome;
    $_SESSION["s"] = $this->sobrenome;
    $_SESSION["m"] = $this->matricula;
    $_SESSION["c"] = $this->cpf;
    $_SESSION["e"] = $this->email;
    $_SESSION["t"] = $this->tipo_usuario;

}
function VerificarRepeticao (){
    $existe = 0; 
    $link = $this->Conecta();
	mysqli_select_db($link, 'tcc');

                // Verificar se o email já existe no Banco de Dados

                $sql = mysqli_query($link, "SELECT * FROM usuarios WHERE email = '{$this->email}'") or print mysql_error();

                if(mysqli_num_rows($sql)>0) {
                    $this->InserirValor();
                    $_SESSION["e"] = "";
                    echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Este email já está sendo usado por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'formulario_cadastro.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    include "formulario_cadastro.php";
                    return 1;
                    
                }
                else { 
                    //echo "EMAIL VALIDO";
                }

                // Vericiar se a matrícula já existe no Banco de Dados 

                $sql1 = mysqli_query($link, "SELECT * FROM usuarios WHERE matricula = '{$this->matricula}'") or print mysql_error();

                if(mysqli_num_rows($sql1)>0) {
                    $this->InserirValor();
                    $_SESSION["m"] = "";
                    echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Esta matricula já está sendo usada por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'formulario_cadastro.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    include "formulario_cadastro.php";
                    return 1;
                }
                else { 
                    // echo "MATRICULA VALIDA";
                }

                // Vericiar se o CPF já existe no Banco de Dados 
                
                $sql2 = mysqli_query($link, "SELECT * FROM usuarios WHERE cpf = '{$this->cpf}'") or print mysql_error();

                if(mysqli_num_rows($sql2)>0) {
                    $this->InserirValor();
                    $_SESSION["c"] = "";
                    echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Este CPF já está sendo usado por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'formulario_cadastro.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    include "formulario_cadastro.php";
                    return 1;
                }
                else { 
                   // echo "CPF VALIDO";
                }
                return 0;
}

function VerificarRepeticao2 (){
    $existe = 0; 
    $link = $this->Conecta();
    mysqli_select_db($link, 'tcc');

     $this->nome = mysqli_real_escape_string($link,($this->nome));
     $this->sobrenome = mysqli_real_escape_string($link,($this->sobrenome));
     $this->cpf = mysqli_real_escape_string($link,($this->cpf));
     $this->matricula = mysqli_real_escape_string($link,($this->matricula));
     $this->email = mysqli_real_escape_string($link,($this->email));
     $this->tipo = mysqli_real_escape_string($link,($this->tipo_usuario));
     
                
                // Verificar se o email já existe no Banco de Dados

                $sql = mysqli_query($link, "SELECT * FROM usuarios WHERE email = '{$this->email}' and usuario_id != '{$this->id}' ") or print mysql_error();

                if(mysqli_num_rows($sql)>0) {
                        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Este email já está sendo usado por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'edit.php?id=$this->id'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    
                    return 1;

                }
                else { 
                    //echo "EMAIL VALIDO";
                }

                // Vericiar se a matrícula já existe no Banco de Dados 

                $sql1 = mysqli_query($link, "SELECT * FROM usuarios WHERE matricula = '{$this->matricula}' and usuario_id != '{$this->id}' ") or print mysql_error();

                if(mysqli_num_rows($sql1)>0) {
                        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Esta matricula já está sendo usada por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'edit.php?id=$this->id'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    
                    return 1;

                }
                else { 
                    // echo "MATRICULA VALIDA";
                }

                // Vericiar se o CPF já existe no Banco de Dados 
                
                $sql2 = mysqli_query($link, "SELECT * FROM usuarios WHERE cpf = '{$this->cpf}' and usuario_id !='{$this->id}' ") or print mysql_error();

                if(mysqli_num_rows($sql2)>0) {
                        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Este CPF já está sendo usado por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'edit.php?id=$this->id'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    
                    return 1;

                }
                else { 
                   // echo "CPF VALIDO";
                }
                return 0;
}

function VerificarRepeticao3 (){
    $existe = 0; 
    $link = $this->Conecta();
    mysqli_select_db($link, 'tcc');

     $this->nome = mysqli_real_escape_string($link,($this->nome));
     $this->sobrenome = mysqli_real_escape_string($link,($this->sobrenome));
     $this->cpf = mysqli_real_escape_string($link,($this->cpf));
     $this->matricula = mysqli_real_escape_string($link,($this->matricula));
     $this->email = mysqli_real_escape_string($link,($this->email));
     $this->tipo = mysqli_real_escape_string($link,($this->tipo_usuario));
     
                
                // Verificar se o email já existe no Banco de Dados

                $sql = mysqli_query($link, "SELECT * FROM usuarios WHERE email = '{$this->email}' and usuario_id != '{$this->id}' ") or print mysql_error();

                if(mysqli_num_rows($sql)>0) {
                        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Este email já está sendo usado por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'editar_usuario.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    
                    return 1;

                }
                else { 
                    //echo "EMAIL VALIDO";
                }

                // Vericiar se a matrícula já existe no Banco de Dados 

                $sql1 = mysqli_query($link, "SELECT * FROM usuarios WHERE matricula = '{$this->matricula}' and usuario_id != '{$this->id}' ") or print mysql_error();

                if(mysqli_num_rows($sql1)>0) {
                        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Esta matricula já está sendo usada por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'editar_usuario.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    
                    return 1;

                }
                else { 
                    // echo "MATRICULA VALIDA";
                }

                // Vericiar se o CPF já existe no Banco de Dados 
                
                $sql2 = mysqli_query($link, "SELECT * FROM usuarios WHERE cpf = '{$this->cpf}' and usuario_id !='{$this->id}' ") or print mysql_error();

                if(mysqli_num_rows($sql2)>0) {
                        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Este CPF já está sendo usado por outro usuário!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'editar_usuario.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    
                    return 1;

                }
                else { 
                   // echo "CPF VALIDO";
                }
                return 0;
}

function makeRandomPassword() {

            $salt = "abchefghjkmnpqrstuvwxyz0123456789";
            srand((double) microtime() * 1000000);
            $i = 0;
            $pass='';
            while ($i <= 7) {

                $num = rand() % 33;
                $tmp = substr($salt, $num, 1);
                $pass = $pass . $tmp;
                $i++;
            }

            return $pass;
        }

function atualiza (){
    $link = $this->Conecta();

     

    $sql = "UPDATE usuarios SET nome='$this->nome', sobrenome='$this->sobrenome', cpf='$this->cpf', matricula='$this->matricula', 

    email='$this->email' ,tipo='$this->tipo_usuario'  WHERE usuario_id='$this->id'";

    if ($link->query($sql) == TRUE) {

         if ($this->matricula != $_SESSION["mat"]){
            $mat = $_SESSION["mat"];
           
                 $sql = "UPDATE reservas SET matricula='$this->matricula' WHERE matricula='$mat'";

                 if ($link->query($sql) == TRUE) {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Alteração realizada com sucesso!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'view-paginated.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                        
    } else {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao alterar usuario',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'view-paginated.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
        
    }
         }

 
     else {

        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Alteração realizada com sucesso!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'view-paginated.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                        
     }    
           
          
    } else {
           echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao atualizar os dados',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'view-paginated.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

    }

     // once saved, redirect back to the view page
     //header("Location: view.php"); 

}

function atualizaperfil (){
    $link = $this->Conecta();

    $sql = "UPDATE usuarios SET nome='$this->nome', sobrenome='$this->sobrenome', cpf='$this->cpf', email='$this->email'  WHERE usuario_id='$this->id'";

    if ($link->query($sql) == TRUE) {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Seu perfil foi atualizado!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'editar_usuario.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

    } else {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Ops, houve um erro ao alterar os dados, tente novamente!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'editar_usuario.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 

    }

     // once saved, redirect back to the view page
     //header("Location: view.php"); 

}


function Reg (){
    
    $link = $this->Conecta();

        $senhaok = $this->makeRandomPassword();
        $senha_randomica = $senhaok;
        
        $this->senha = $senha_randomica;
        
        $codifica = hash('sha512',$this->senha);
     
        
        $this->senha = $codifica;

		// Inserindo os dados no banco de dados
        $con=mysqli_connect("localhost","u749688875_ceep","1234554123","u749688875_ceep");
                // VERIFICA A LIGAÇÃO NÃO TEM ERROS
                        if (mysqli_connect_errno())
                        {
                            // CASO TENHA ERROS MOSTRA O ERRO DE LIGAÇÃO À BASE DE DADOS
                            echo "Failed to connect to MySQL: " . mysqli_connect_error();
                        }
                $sql = "INSERT INTO usuarios (nome, sobrenome, cpf, matricula, email, tipo, senha, data_cadastro, data_ultimo_login,
                leitura, mensagem)

                VALUES ('$this->nome', '$this->sobrenome', '$this->cpf', '$this->matricula', '$this->email', '$this->tipo_usuario', 
                '$this->senha', now(), now(), '0', ' ')";
                if (!mysqli_query($con,$sql))
                        {
                            die('Error: ' . mysqli_error($con));
                        }

                mysqli_close($con);
        if (!$sql) {
            echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao criar a conta!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'formulario_cadastro.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    include "formulario_cadastro.php";
        }
        else {

            $usuario_id = mysqli_insert_id($link);
// Enviar um email ao usuário para confirmação e ativar o cadastro!

            $headers = "MIME-Version: 1.0\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\n";
            $headers .= "From: Reserva de recursos - CEEP ANGELINA MENDES BRAGA - <ceepreservas.site >";
            $subject = "Confirmação de cadastro - ceepreservas.site";
            $mensagem = "Prezado {$this->nome} ,<br />
                sua conta foi cadastrada com sucesso, efetue o seu login com os seguintes dados abaixo:.<br /> <br />

                <br /> <br />

                <strong>Matricula</strong>: {$this->matricula}<br />
                <strong>Senha</strong>: {$senha_randomica}<br /> <br />

                                <a href='https://ceepreservas.shop/ceep'>

                http://www.ceepreservas.site/ceep

                </a>

                <br /><br />
                Obrigado!<br /> <br />

				Webmaster<br /> <br /> <br />
				Esta é uma mensagem automática, por favor não responda!";

                $this->EnviarEmail($this->email,$subject,$mensagem,$headers);
            }

    }

function EnviarEmail ($email,$subject,$mensagem,$headers){

        ini_set('display_errors', 1);

        error_reporting(E_ALL);

        $from = "";

        $to = $email;

        $subject = $subject;

        $mensagem = $mensagem;

        $headers = $headers;

        mail($to, $subject, $mensagem, $headers);
       
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Cadastro realizado com sucesso, sua senha foi enviada ao seu e-mail!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'formulario_cadastro.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                    include "formulario_cadastro.php";
            
        }   

}

?>


