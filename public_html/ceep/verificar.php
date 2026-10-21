<?php 
  if (!isset($_SESSION)) {
    session_start();
   }

class verificar{


function verifica ($codigo_sala){
	$link= DB_Connect();
	$codigo_sala = $codigo_sala;
    $sql1 = "SELECT * FROM recursos WHERE cod_recurso='$codigo_sala'";
	$query = $link->query($sql1);
	$resultado1 = $link->query($sql1) or die($link->error());
	$linha1 = $query->fetch_array();
	$nome = $linha1["nome_recurso"];
	return $nome;
}

function verificausuario ($matricula){
	$link= DB_Connect();
	$matricula = $matricula;
    $sql1 = "SELECT * FROM usuarios WHERE matricula='$matricula'";
	$query = $link->query($sql1);
	$resultado1 = $link->query($sql1) or die($link->error());
	$linha1 = $query->fetch_array();
	$nome = $linha1["nome"] ." ". $linha1["sobrenome"];
	return $nome;
}

}
?>