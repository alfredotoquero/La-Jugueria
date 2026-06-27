<?
ini_set("session.gc_maxlifetime","43200");
session_name("2q093ex8uq2ewun");
session_start();
include("../../002wf3f3kgdvr/983y4rhouCon.php");
include("../../002wf3f3kgdvr/983y4rhou.php");
$idproducto = $_POST["idproducto"];
mysql_query("update trcuentaproductostmp set cantidad = cantidad + 1 where idtmp = '$idproducto'");
?>