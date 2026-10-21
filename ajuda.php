<?php 
	if (!isset($_SESSION)) {
    session_start();
   }
	if (empty($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }
?>
<html>
	<head>
		<title>Ajuda</title>
		<meta charset='utf-8' >
	</head>
	
	<body bgcolor = "yellow">
		<h1>Opções pesquisa</h1>
		<ul>
			<li> <a href = "#logica"> Adicionar reserva </a> </li>
			<li> <a href = "#web"> Desenvolvimento Web </a> </li>
			<li> <a href = "#sql"> SQL e Modelo Relacional </a> </li>
		</ul>
		
		<h3 id="logica"> Adicionar reserva </h3>
		Caro usuário, para adicionar uma reserva, você deve simplesmente selecionar o recurso desejado, selecionar a hora e posteriormente clicar em reservar. 
		Você pode reservar apenas de no máximo uma semana para a outra. Você possui o limite de apenas três reservas semanais. 
		Os datashows 1 e 2 nas sextas-feiras e aos sábado são reservados para EAD.
		<center><img src="ajuda/reserva.png" height="50%" alt="Imagem Logo" ></center>  

		<h3 id="web"> Desenvolvimento Web </h3>
		Desenvolvimento web é o termo utilizado para descrever o desenvolvimento de sites, na Internet ou numa intranet. Este é o profissional que trabalha desenvolvendo websites, podendo ser um Web Designer (Desenvolvedor do Layout), ou Web Developer(Desenvolvedor de sistemas). O desenvolvimento refere-se a um processo de construção e testes do software especifico para a web, com a finalidade de se obter uma conjunto de programas, que satisfazem as funções pretendidas, quer em termos de usabilidade dos usuários ou compatibilidade com outros programas existentes. O desenvolvimento web pode variar desde simples páginas estáticas a aplicações ricas, comércios eletrônicos ou redes sociais. O desenvolvimento web leva em consideração fatores como a verificação de erros na entrada de dados através formulários, assim como a filtragem e a normalização dos dados que são alimentados por esses meios. Falhas na segurança como o SQL injection devem ser testadas, e o uso de scripts pode ajudar a encontrar outras falhas de segurança mais técnicas. As peculiaridades de cada sistema dependem das tecnologias usadas, e muitas vezes não é função do desenvolvedor manter o nível de segurança, mas sim da plataforma, por exemplo o Apache server, ou o php.
		<center> <img src="ajuda/cancelar.png" height="50%" alt="Imagem Logo" > </center> 		
		<h3 id="sql"> SQL e Modelo Relacional </h3>
		O modelo relacional é um modelo de dados, adequado a ser o modelo subjacente de um Sistema Gerenciador de Banco de Dados (SGBD), que se baseia no princípio em que todos os dados estão guardados em tabelas (ou, matematicamente falando, relações). Toda sua definição é teórica e baseada na lógica de predicados e na teoria dos conjuntos. O conceito foi criado por Edgar Frank Codd em 1970, sendo descrito no artigo "Relational Model of Data for Large Shared Data Banks". Na verdade, o modelo relacional foi o primeiro modelo de dados descrito teoricamente, os bancos de dados já existentes passaram então a ser conhecidos como (modelo hierárquico, modelo em rede ou Codasyl e modelo de listas invertidas). O modelo relacional para gerência de bases de dados (SGBD) é um modelo de dados baseado em lógica e na teoria de conjuntos. Em definição simplificada, o modelo baseia-se em dois conceitos: conceito de entidade e relação - Uma entidade é um elemento caracterizado pelos dados que são recolhidos na sua identificação vulgarmente designado por tabela. Na construção da tabela identificam-se os dados da entidade. A atribuição de valores a uma entidade constrói um registro da tabela. A relação determina o modo como cada registro de cada tabela se associa a registros de outras tabelas. Historicamente ele é o sucessor do modelo hierárquico e do modelo em rede. Estas arquiteturas antigas são até hoje utilizadas em alguns data centers com alto volume de dados, onde a migração é inviabilizada pelo custo que ela demandaria; existem ainda os novos modelos baseados em orientação ao objeto, que na maior parte das vezes são encontrados como kits em linguagem formal. O modelo relacional foi inventado pelo Frank Codd e subsequentemente mantido e aprimorado por Chris Date e Hugh Darwen como um modelo geral de dados. No Terceiro Manifesto (1995) eles mostraram como o modelo relacional pode ser estendido com características de orientação a objeto sem comprometer os seus princípios fundamentais. A linguagem padrão para os bancos de dados relacionais, SQL, é apenas vagamente remanescente do modelo matemático. Atualmente ela é adotada, apesar de suas restrições, porque ela é antiga e muito mais popular que qualquer outra linguagem de banco de dados.
		
	</body>
	
</html>