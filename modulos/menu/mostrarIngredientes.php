<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	if($_POST["txtBusqueda"]!=""){
		$where = " and producto like '".$_POST["txtBusqueda"]."%'";
	}
?>
<table width="540" align="center" cellpadding="3" cellspacing="0" border="0" class="navigateable">
    <thead>
        <tr>
            <td align="center"><b>INGREDIENTE</b></td>
        </tr>
    </thead>
    <tbody>
    <?
    $ingredientes = mysql_query("select * from tinventario where ingrediente = '1'".$where." and idproducto in (select idingrediente from trplatilloingredientes where idplatillo = '".$_POST["idmenu"]."') order by producto");
    while($ingrediente = mysql_fetch_assoc($ingredientes)){
    ?>
        <tr onclick="fancy(306,130,'modulos/menu/cantidad.php?idingrediente=<? echo $ingrediente["idproducto"];?>&idmenu=<? echo $_GET["idmenu"];?>&tipo=0&precio=0');">
            <td align="left"><? echo $ingrediente["producto"];?></td>
        </tr>
    <?	
    }
    ?>
    </tbody>
</table>
<script>
$.tableNavigation();
</script>