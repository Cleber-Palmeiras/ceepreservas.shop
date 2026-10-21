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


require('conecta.php');

class recursos {

	private $id;
  private $nome_recurso;
	private $status;

function Conecta(){
	$link= DB_Connect();
    return $link;
}

function Cadastro($categoria){
        $link = $this->Conecta();
        $this->status = "Disponivel";
        $categoria = $categoria;
 $sql = "INSERT INTO recursos (nome_recurso, status, categoria)

                VALUES ('$this->nome_recurso', '$this->status', '$categoria')";

                if (!$link->query($sql)) {
                        echo ("<b><font face=\"arial,verdana\" size=\"-1\"> ERRO </i><br> ERRO MySQL:</b> " . $link->error());
                        exit();
                    }

        if (!$sql) {
                echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Ops, ocorreu um erro ao cadastrar o recurso!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
        }
        else  {
                          echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Recurso cadastrado!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
          
        }
}

function editar($id,$nome_recurso,$status,$categoria){

    $this->id = $id;
    $this->nome_recurso = $nome_recurso;
    $this->status = $status;
    $this->categoria = $categoria;
    
}

function nome($nome){
    $this->nome_recurso = $nome;
}
function VerificarRepeticao2 (){
    $existe = 0; 
    $link= DB_Connect();
    mysqli_select_db($link, 'tcc');

     $this->nome_recurso = mysqli_real_escape_string($link,($this->nome_recurso));
     $this->status = mysqli_real_escape_string($link,($this->status));
     
               // Verificar se o email já existe no Banco de Dados

                $sql = mysqli_query($link, "SELECT * FROM recursos WHERE nome_recurso = '{$this->nome_recurso}' and cod_recurso != '{$this->id}' ") or print mysql_error();

                if(mysqli_num_rows($sql)>0) {
                    echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Já possui um recurso com este mesmo nome!',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                        return 1;
                }
                else { 
                    //echo "EMAIL VALIDO";
                }
                return 0;

}

function atualiza (){
    $link = $this->Conecta();

    $sql = "UPDATE recursos SET nome_recurso='$this->nome_recurso', status='$this->status', categoria='$this->categoria' WHERE cod_recurso='$this->id'";


    if ($link->query($sql) == TRUE) {

     if ($this->status=="Indisponivel"){

            $sql = "UPDATE reservas SET disponivel=1 WHERE cod_recurso='$this->id'";

     if ($link->query($sql) == TRUE) {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Alteração realizada com sucesso!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'gerencia_recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                        
    } else {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao alterar recurso',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'gerencia_recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
        
    }


       }

     if ($this->status=="Disponivel"){

            $sql = "UPDATE reservas SET disponivel=0 WHERE cod_recurso='$this->id' AND disponivel=1";

     if ($link->query($sql) == TRUE) {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Alteração realizada com sucesso!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'gerencia_recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
                        
    } else {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao alterar recurso',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'gerencia_recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
        
    }


       }
}

else {
        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                        swal({
                                title:'',
                                text:'Erro ao alterar recurso',
                                type:'error'
                                },
                                function(){
                                  top.location.href = 'gerencia_recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
        
    }
     // once saved, redirect back to the view page
     //header("Location: view.php"); 

}

function codigo ($cod_recurso){
    $this->id = $cod_recurso;
}

function excluir (){

        $link = $this->Conecta();
        $sql = "DELETE FROM recursos WHERE cod_recurso=$this->id";
        $query = $link->query($sql);

        $sql1 = "DELETE FROM reservas WHERE cod_recurso=$this->id";
        $query = $link->query($sql1);

        if ($link->query($sql) == TRUE) {
             echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
                       swal ({
                                title:'Aviso',
                                text:'Exclusão realizada com sucesso!',
                                type:'success'
                                },
                                function(){
                                  top.location.href = 'gerencia_recurso.php'
                                }
                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
                                return;
                    exit();

        } else {
            echo "Não foi-se";
        }

}

}


?>

