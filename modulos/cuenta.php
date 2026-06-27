<?
ini_set("session.gc_maxlifetime","43200");
session_name("2q093ex8uq2ewun");
session_start();
date_default_timezone_set('America/Los_Angeles');
include("../002wf3f3kgdvr/983y4rhouCon.php");
include("../002wf3f3kgdvr/983y4rhou.php");
?>
<table width="930" border="0" cellpadding="3" cellspacing="0" align="center" style="font-size:20px;">
    <thead>
        <tr height="30">
            <td width="80" align="center"><b>CANT.</b></td>
            <td width="39"></td>
            <td width="466" align="left"><b>CONCEPTO</b></td>
            <td width="115" align="left"><b>P.U.</b></td>
            <td width="150" align="left"><b>TOTAL</b></td>
            <td width="90"></td>
        </tr>
    </thead>
    <tbody>
    <?
	$total = 0;
	$num = 0;
    $productos = mysql_query("select * from trcuentaproductostmp order by idtmp");
    while($producto = mysql_fetch_assoc($productos)){
		$precio = $producto["precio"];
		$subtotal = (float)($precio*$producto["cantidad"]);
		$total += $subtotal;
		$num++;
    	?>
        <tr height="30">
            <td align="center"><? echo $producto["cantidad"];?></td>
            <td></td>
            <td><? echo mysql_result(mysql_query("select nombre from tproductos where idproducto = '".$producto["idproducto"]."'"),0); ?></td>
            <td align="left">$<? echo number_format($precio,2);?></td>
            <td align="left">$<? echo number_format($subtotal,2);?></td>
            <td align="center"><a href="javascript:;" onclick="agregarProducto('<? echo $producto["idtmp"];?>');"><img src="images/iconoMas.png" /></a> <a href="javascript:;" onclick="eliminarProducto('<? echo $producto["idtmp"];?>');"><img src="images/iconoMenos.png" /></a></td>
        </tr>
    	<?
    }
    ?>
    </tbody>
</table>
<script>
mostrarTotal('<? echo number_format($total,2);?>','<? echo $num;?>');
</script>