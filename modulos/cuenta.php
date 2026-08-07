<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
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
	$idsucursal = $_SESSION["idsucx9284hqmzt7"];
    $productos = mysqli_query($con, "select * from trcuentaproductostmp where idsucursal = '$idsucursal' order by idtmp");
    while($producto = mysqli_fetch_assoc($productos)){
		$precio = $producto["precio"];
		$subtotal = (float)($precio*$producto["cantidad"]);
		$total += $subtotal;
		$num++;
    	?>
        <tr height="30">
            <td align="center"><? echo $producto["cantidad"];?></td>
            <td></td>
            <td><? echo mysqli_fetch_row(mysqli_query($con, "select nombre from tproductos where idproducto = '".$producto["idproducto"]."'"))[0]; ?></td>
            <td align="left">$<? echo number_format($precio,2);?></td>
            <td align="left">$<? echo number_format($subtotal,2);?></td>
            <td align="center"><a href="javascript:;" onclick="agregarProducto('<? echo $producto["idtmp"];?>');"><img src="assets/images/iconoMas.png" /></a> <a href="javascript:;" onclick="eliminarProducto('<? echo $producto["idtmp"];?>');"><img src="assets/images/iconoMenos.png" /></a></td>
        </tr>
    	<?
    }
    ?>
    </tbody>
</table>
<script>
mostrarTotal('<? echo number_format($total,2);?>','<? echo $num;?>');
</script>