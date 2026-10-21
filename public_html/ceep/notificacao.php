<html >
<script src="sweetalert-dev.min.js"/> </script>
<script src="stylesheet" href="sweetalert.css" /> </script>
<link rel="stylesheet" href="sweetalert.min.css" type="text/css" />
 <meta charset="utf-8" />
<?php


require_once('conecta.php');

class Notificacao {

private $matricula;
private $cod_recurso; 
private $data;
private $adm; 
private $email; 
private $nome;
private $recurso;
private $mensagem;
private $seuemail;

public function NotificarUsuario ($matricula, $cod_recurso, $data, $adm, $email){

$this->matricula = $matricula;
$this->cod_recurso = $cod_recurso;
$this->data = $data;
$this->adm = $adm;
$this->email = $email;

}	

public function CriarMensagem(){

	$this->mensagem = $this->nome. ", o administrador(a) ". $this->adm. " excluiu a sua reserva do recurso: ".$this->recurso." para o dia: "
	.$this->data. ". Para saber os motivos entre em contato com o email do mesmo(a): ".$this->email. "." ;
	
}

function Conecta(){
	$link= DB_Connect();
    return $link;
}

public function VerificarUsuario(){
	$link = $this->Conecta();
	$sql1 = "SELECT * FROM usuarios WHERE matricula='$this->matricula'";
	$query = $link->query($sql1);
	$resultado1 = $link->query($sql1) or die($link->error());
	$linha1 = $query->fetch_array();
	$this->nome =  $linha1["nome"]. " ". $linha1["sobrenome"];
	$this->seuemail = $linha1["email"];
}

public function VerificarRecurso(){
	$link = $this->Conecta();
	$sql1 = "SELECT * FROM recursos WHERE cod_recurso='$this->cod_recurso'";
	$query = $link->query($sql1);
	$resultado1 = $link->query($sql1) or die($link->error());
	$linha1 = $query->fetch_array();
	$this->recurso =  $linha1["nome_recurso"];
}


public	function Mensagem(){



		    $headers = "From: CEEP - ANGELINA MENDES BRAGA - CANCELAMENTO<ceepreservas.site >";
            $subject = "Cancelamento de reserva";
           
            ini_set('display_errors', 1);

	        error_reporting(E_ALL);

	        $from = "";

	        $to = $this->seuemail;

	        $subject = $subject;

	        $mensagem = $this->mensagem;

	        $headers = $headers;

	        mail($to, $subject, $mensagem, $headers);
	       
	        echo ("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){  
	                        swal({
	                                title:'',
	                                text:'Reserva excluida!',
	                                type:'success'
	                                },
	                                function(){
	                                  top.location.href = 'excluir_reservas.php'
	                                }
	                              ); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>"); 
	                                include "excluir_reservas.php";
	       
	         
	}
         
}

?>