<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	$idcuenta = $_POST["idcuenta"];
	mysql_query("delete from trcuentamenu where idcuenta = '$idcuenta'");
	mysql_query("delete from trcuentamenucqsr where idcuenta = '$idcuenta'");
	mysql_query("delete from trcuentamenuitta where idcuenta = '$idcuenta'");
	mysql_query("insert into trcuentamenu select '$idcuenta',idmenu,idingrediente,cantidad,tipo,cqsr,itta,precio,descuento from trcuentamenutmp");
	mysql_query("insert into trcuentamenucqsr select '$idcuenta',idcqsr,idcategoria,idingrediente from trcuentamenucqsrtmp");
	mysql_query("insert into trcuentamenuitta select '$idcuenta',idittakate,idcategoria,idplatillo from trcuentamenuittatmp");
	mysql_query("truncate trcuentamenutmp");
	mysql_query("truncate trcuentamenucqsrtmp");
	mysql_query("truncate trcuentamenuittatmp");
?>