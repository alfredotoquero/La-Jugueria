<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	$_SESSION["idcuenta"] = $_POST["idcuenta"];
	if($_POST["idcuenta"]<1){
		$_SESSION["idcliente"]=0;
	}
?>