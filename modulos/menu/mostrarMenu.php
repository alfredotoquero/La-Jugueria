<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	if($_POST["txtBusqueda"]!=""){
		$where = " where nombre like '".$_POST["txtBusqueda"]."%' or codigo = '".$_POST["txtBusqueda"]."'";
		$where2 = " and t1.producto like '".$_POST["txtBusqueda"]."%'";
	}
?>
<table width="540" align="center" cellpadding="3" cellspacing="0" border="0" class="navigateable">
    <thead>
        <tr>
            <td align="center" width="50"><b>COD.</b></td>
            <td><b>PLATILLO</b></td>
            <td align="center" width="100"><b>PRECIO</b></td>
        </tr>
    </thead>
    <tbody>
    <?
    $platillos = mysql_query("select idmenu,idcategoria,nombre,precio,'0' as tipo,codigo from tmenu".$where." union select t2.idingrediente,t2.idcategoria,t1.producto as nombre,t3.precio,'1' as tipo,'0' as codigo from tinventario as t1,tcatingredientescqsr as t2,tcatcategoriascqsr as t3 where t1.idproducto = t2.idingrediente and t2.idcategoria = t3.idcategoria".$where2." order by nombre");
    while($platillo = mysql_fetch_assoc($platillos)){
    ?>
        <tr onclick="parent.fancy(306,130,'modulos/menu/<? if($platillo["precio"]>0){?>cantidad<? }else{ ?>precio<? } ?>.php?idmenu=<? echo $platillo["idmenu"];?>&idingrediente=0&tipo=<? echo $platillo["tipo"];?>&precio=<? echo $platillo["precio"];?>');">
            <td align="center"><? echo $platillo["codigo"];?></td>
            <td align="left"><? echo $platillo["nombre"];?></td>
            <td align="right">$<? echo number_format($platillo["precio"],2);?></td>
        </tr>
    <?	
    }
    ?>
    </tbody>
</table>
<script>
$.tableNavigation();
</script>