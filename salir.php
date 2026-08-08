<?
	ini_set("session.gc_maxlifetime","7200");
	session_name("2q093ex8uq2ewun");
	session_start();
	
	$sucursal = isset($_SESSION["sucslgx7742kdnq1"]) ? strtolower($_SESSION["sucslgx7742kdnq1"]) : "";

	unset($_SESSION["idusrx3209exum0q3em"]);
	unset($_SESSION["456udhsere"]);

	session_destroy();

	if(preg_match("/^[a-z0-9-]+$/", $sucursal) === 1){
		header("location: index.php?sucursal=" . rawurlencode($sucursal));
	}else{
		header("location: index.php");
	}
?> 