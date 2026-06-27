<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	$idcuenta = $_SESSION["idcuenta"];
	$_SESSION["idcliente"]=0;
	if($idcuenta>0){
		mysql_query("delete from tcuentas where idcuenta = '$idcuenta'");
		mysql_query("delete from trcuentamenu where idcuenta = '$idcuenta'");
		mysql_query("delete from trcuentamenucqsr where idcuenta = '$idcuenta'");
		mysql_query("delete from trcuentamenuitta where idcuenta = '$idcuenta'");
	}
	mysql_query("truncate trcuentamenutmp");
	mysql_query("truncate trcuentamenucqsrtmp");
	mysql_query("truncate trcuentamenuittatmp");
?>