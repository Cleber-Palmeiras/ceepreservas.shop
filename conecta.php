<?php
	include('conf.inc.php');
/*	$db=mysql_connect ($servidor, $usuario, $senhadb)
	or die ('Impossível conectar no bando de dados: ' . mysql_error());
	mysql_select_db($banco);
*/
	function DB_Connect(){
	$link = @mysqli_connect (DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE ) or die (@mysqli_connect_error('Erro!'));
	
	@mysqli_set_charset ($link, DB_CHARSET) or die (@mysqli_error($link));

	return $link;
}

# ECHA CONEXÃO 

	function DB_Close ($link){
		@mysqli_close ($link) or die (mysqli_error($link)); 
	}


?>
