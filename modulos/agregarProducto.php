<?
ini_set("session.gc_maxlifetime","43200");
session_name("2q093ex8uq2ewun");
session_start();
date_default_timezone_set('America/Los_Angeles');
include("../002wf3f3kgdvr/983y4rhouCon.php");
include("../002wf3f3kgdvr/983y4rhou.php");
$idproducto = $_POST["idproducto"];
$precio = $_POST["precio"];
if(mysql_num_rows(mysql_query("select * from trcuentaproductostmp where idproducto = '$idproducto'"))==0){
	mysql_query("insert into trcuentaproductostmp values(null,'$idproducto','1','$precio')");
}else{
	mysql_query("update trcuentaproductostmp set cantidad = cantidad + 1,precio = '$precio' where idproducto = '$idproducto'");
}
?>