<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
if($_GET["imprimir"]==1){
	include("num2letras.php");

	$corte = mysqli_fetch_assoc(mysqli_query($con, "select * from tcortes where status = 0 and idsucursal = '" . $_SESSION["idsucx9284hqmzt7"] . "' order by idcorte desc limit 1"));

	$idcuenta = $_GET["idcuenta"];
	$cuenta = mysqli_fetch_assoc(mysqli_query($con, "select * from tcuentas where idcuenta = '$idcuenta'"));
	$idsucursal = $cuenta["idsucursal"];
	$total = $cuenta["total"];
	$efectivo = (float)$cuenta["total"] + (float)$cuenta["cambio"];
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/escpos.php");

	$infoticket = infoTicketSucursal($con, $idsucursal);

	$anchoTicket = anchoTicket($infoticket["tamanoimpresion"]);

	$idticket = "";
	for($i=strlen($cuenta["folio"]);$i<7;$i++){
		$idticket .= "0";
	}
	$idticket = $idticket.$cuenta["folio"];
	$ticket = date("d/m/Y")." ".date("H:i:s a")." ".$idticket;

	$escpos = escposInit();
	$escpos .= escposEncabezado($infoticket, $ticket, $anchoTicket);

	$escpos .= escposEncabezadoProductos($anchoTicket);

	$articulos = 0;
	$productos = mysqli_query($con, "select * from trcuentaproductos where idcuenta = ".$idcuenta." order by idcuentaproducto");
	while($producto = mysqli_fetch_assoc($productos)){
		// El stock ya se descuenta una sola vez en cobrar.php al confirmar la venta;
		// esta pantalla solo imprime, y puede volver a ejecutarse (reimpresion).
		$articulos += $producto["cantidad"];
		$nombre = mysqli_fetch_row(mysqli_query($con, "select nombre from tproductos where idproducto = '".$producto["idproducto"]."'"))[0];
		$precio = "$".number_format($producto["precio"],2);
		$importe = "$".number_format($producto["precio"]*$producto["cantidad"],2);
		$escpos .= escposFilaProducto($producto["cantidad"], $nombre, $precio, $importe, $anchoTicket);
	}

	$escpos .= escposSeparador($anchoTicket);
	$escpos .= escposFilaMonto("TOTAL", "$".number_format($total,2), $anchoTicket);
	$escpos .= escposFilaMonto("EFECTIVO", "$".number_format($efectivo,2), $anchoTicket);
	$escpos .= escposFilaMonto("CAMBIO", "$".number_format($efectivo-$total,2), $anchoTicket);

	$descripcion = strtoupper(num2letras(number_format($total,2,'.','')));
	$escpos .= escposParrafo($descripcion, $anchoTicket);

	$escpos .= escposSeparador($anchoTicket);
	$escpos .= escposAlign("center");
	$escpos .= escposLinea("ARTICULOS: ".$articulos);
	$escpos .= escposSeparador($anchoTicket);
	$escpos .= escposLinea("GRACIAS POR SU COMPRA");
	$escpos .= escposAbrirCajon();
	$escpos .= escposCorte();
	?>
    <script>
		parent.imprimirTicket(<? echo json_encode($infoticket["nombreimpresora"]);?>, '<? echo base64_encode($escpos);?>').then(function(){
			parent.$.fancybox.close();
		});
    </script>
    <?
}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gracias</title>
<script src="../assets/js/jquery.js"></script>
<script>

function manejarEventos(evento){
	evento.preventDefault();
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==13){
		location.href="gracias.php?imprimir=1&idcuenta=<? echo $_GET["idcuenta"];?>&cambio=<? echo $_GET["cambio"];?>";
	}
}
$(document).ready(function(){
	document.getElementById('txtFocus').focus();
	$(document).keydown(manejarEventos);
});
</script>
<style>
body{
	margin:0px;
}
</style>
</head>

<body>
<div style="position:relative;">
	<div style="height:0px; width:0px;"><input type="text" id="txtFocus" name="txtFocus" /></div>
	<div style="position:absolute; width:500px; height:250px; top:0px; left:0px;">
    <img src="../assets/images/pantallaGracias.png" width="500" height="250" usemap="#Map" border="0" />
    </div>
    <div style="position:absolute; width:500px; height:56px; top:104px; left:0px;">
    	<table width="500" border="0" cellspacing="0" cellpadding="0">
        	<tr height="56">
            	<td style="font-size:50px; color:#FFF; font-family:Arial, Helvetica, sans-serif;" align="center">$<? echo number_format($_GET["cambio"],2);?> MXN.</td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
<?
}
?>
