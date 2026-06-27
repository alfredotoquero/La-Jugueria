<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../../002wf3f3kgdvr/983y4rhouConRem.php");
	$idcliente = $_POST['idcliente'];
	$_SESSION['idcliente'] = $idcliente;
	echo strtoupper(mysql_result(mysql_query("select nombre from tclientes where idcliente = $idcliente"),0));
?>