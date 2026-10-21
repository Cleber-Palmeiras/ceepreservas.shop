<?php 


class verifica_acesso {
	

function checar_se_foi_logado (){
	include "functions.php"; // arquivo de fun��es.	
	if ($_SESSION["tipo"] != "p") {
    	if ($_SESSION["tipo"] != "a") {
        	echo("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ alert('Voc� n�o tem permiss�o para acessar essa p�gina!.'); window.navigate('index.php'); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        	exit();
    	}
	}

}

function destruir () {

if ($destruir = isset($_GET["destruir"])) {
    if ($destruir) {
        session_destroy();
        echo("<SCRIPT LANGUAGE=\"JavaScript\"> function tela(){ /* window.navigate('index.php'); */ window.close(); } </SCRIPT> <BODY OnLoad=\"javascript:tela();\" > </body>");
        exit();
    	}
	}

}

}

?> 