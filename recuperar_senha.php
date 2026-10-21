<html >
<script src="sweetalert-dev.min.js"/> </script>
<script src="stylesheet" href="sweetalert.css" /> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />
 <meta charset="utf-8" />
<?php


require_once('conecta.php');

class recuperar_senha {

	private $email;
	private $senha;
    private $senha1;


function Conecta(){
	$link= DB_Connect();
    return $link;
}

function email ($email){
    $this->email = $email;   
}


function VerificarEmail (){
    $existe = 0; 
    $link = $this->Conecta();
    mysqli_select_db($link, 'tcc');
    

     $this->email = mysqli_real_escape_string($link,($this->email));
     
                $cont=0;
                // Verificar se o email já existe no Banco de Dados

                $sql = mysqli_query($link, "SELECT * FROM usuarios WHERE email = '{$this->email}' ") or print mysql_error();

                if(mysqli_num_rows($sql)>0) {
                    $cont++;
                }
                else { 
                    echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Este email não está cadastrado no nosso site!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'formulario_senha_perdida.html'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                                include "formulario_senha_perdida.html";
                }

                return $cont;
               
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

function Reg (){

        $link = $this->Conecta();

        /* Se passarmos por esta verificação ilesos é hora de
          finalmente cadastrar os dados. Vamos utilizar uma função para gerar a senha de
          forma randômica */

        $senhaok = $this->makeRandomPassword();
        $senha_randomica = $senhaok;
        $this->senha1 = $senha_randomica;
        

        // VEJA QUE PRIMEIRO EU VOU GERAR UM SALT JÁ ENCRIPTADO EM MD5

        //PRIMEIRA ENCRIPTAÇÃO ENCRIPTANDO COM crypt
        //$codifica = crypt($this->senha,$senha_randomica);
     
        // SEGUNDA ENCRIPTAÇÃO COM sha512 (128 bits)
        $codifica = hash('sha512',$this->senha1);
     
        //AGORA RETORNO O VALOR FINAL ENCRIPTADO
        

        $this->senha = $codifica;
		// Inserindo os dados no banco de dados

		mysqli_select_db($link, 'tcc');

         $sql = "UPDATE usuarios SET senha='$this->senha' WHERE email='$this->email'";

				if (!$link->query($sql)) {
                        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Ops... Ocorreu um erro, tente novamente!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'formulario_senha_perdida.html'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                                include "formulario_senha_perdida.html";
                        exit();
                    }

        if (!$sql) {
            echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Ops... Ocorreu um erro, tente novamente!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'formulario_senha_perdida.html'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                                include "formulario_senha_perdida.html";
        }
        else {

            $usuario_id = mysqli_insert_id($link);
// Enviar um email ao usuário para confirmação e ativar o cadastro!

            $headers = "MIME-Version: 1.0\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\n";
            $headers .= "From: CEEP ANGELINA MENDES BRAGA <ceepreservas.site >";
            $subject = "Reenvio de senha";
            $mensagem = "

				<strong>Esta é sua nova Senha</strong>: {$this->senha1}<br /> <br />";

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
                                text:'A sua nova senha foi enviada ao seu email!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'index.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                                include "index.php";
        
        }   

}


?>

