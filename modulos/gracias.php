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

	$infoticket = mysqli_fetch_assoc(mysqli_query($con, "select ticket_negocio as negocio, ticket_calle as calle, ticket_numero as numero, ticket_colonia as colonia, ticket_codigopostal as codigopostal, ticket_ciudad as ciudad, ticket_nombre as nombre, ticket_rfc as rfc, ticket_regimen as regimen, ticket_nombreimpresora as nombreimpresora from tsucursales where idsucursal = '$idsucursal'"));

	$anchoTicket = ANCHO_TICKET;

	$idticket = "";
	for($i=strlen($cuenta["folio"]);$i<7;$i++){
		$idticket .= "0";
	}
	$idticket = $idticket.$cuenta["folio"];
	$ticket = date("d/m/Y")." ".date("H:i:s a")." ".$idticket;

	$escpos = escposInit();
	$escpos .= escposAlign("center");
	$escpos .= escposBold(true).escposTamano(true);
	$escpos .= escposLinea($infoticket["negocio"]);
	$escpos .= escposTamano(false).escposBold(false);
	$escpos .= escposLinea($infoticket["calle"]." No. ".$infoticket["numero"]);
	$escpos .= escposLinea($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"]);
	$escpos .= escposLinea($infoticket["ciudad"]);
	$escpos .= escposLinea($infoticket["nombre"]);
	$escpos .= escposLinea($infoticket["rfc"]);
	$escpos .= escposLinea($infoticket["regimen"]);
	$escpos .= escposLinea($ticket);
	$escpos .= escposAlign("left");
	$escpos .= escposLinea(str_repeat("=", $anchoTicket));

	// 5 + 17 + 9 + 11 = ANCHO_TICKET
	$escpos .= escposFila(array(
		array("CANT", 5, "left"),
		array("PRODUCTO", 17, "left"),
		array("PRECIO", 9, "right"),
		array("IMPORTE", 11, "right")
	));

	$articulos = 0;
	$productos = mysqli_query($con, "select * from trcuentaproductos where idcuenta = ".$idcuenta." order by idcuentaproducto");
	while($producto = mysqli_fetch_assoc($productos)){
		// El stock ya se descuenta una sola vez en cobrar.php al confirmar la venta;
		// esta pantalla solo imprime, y puede volver a ejecutarse (reimpresion).
		$articulos += $producto["cantidad"];
		$numLinea = 1;
		$cantidad = $producto["cantidad"];
		$nombre = mysqli_fetch_row(mysqli_query($con, "select nombre from tproductos where idproducto = '".$producto["idproducto"]."'"))[0];
		$precio = "$".number_format($producto["precio"],2);
		$importe = "$".number_format($producto["precio"]*$producto["cantidad"],2);
		$lineas = dividirTexto($nombre,17);
		foreach($lineas as $linea){
			$escpos .= escposFila(array(
				array($numLinea==1 ? $cantidad : "", 5, "left"),
				array($linea, 17, "left"),
				array($numLinea==1 ? $precio : "", 9, "right"),
				array($numLinea==1 ? $importe : "", 11, "right")
			));
			$numLinea++;
		}
	}

	$escpos .= escposLinea(str_repeat("=", $anchoTicket));
	$escpos .= escposFila(array(array("TOTAL", 31, "left"), array("$".number_format($total,2), 11, "right")));
	$escpos .= escposFila(array(array("EFECTIVO", 31, "left"), array("$".number_format($efectivo,2), 11, "right")));
	$escpos .= escposFila(array(array("CAMBIO", 31, "left"), array("$".number_format($efectivo-$total,2), 11, "right")));

	$descripcion = strtoupper(num2letras(number_format($total,2,'.','')));
	$lineas = dividirTexto($descripcion,$anchoTicket);
	foreach($lineas as $linea){
		$escpos .= escposLinea($linea);
	}

	$escpos .= escposLinea(str_repeat("=", $anchoTicket));
	$escpos .= escposAlign("center");
	$escpos .= escposLinea("ARTICULOS: ".$articulos);
	$escpos .= escposLinea(str_repeat("=", $anchoTicket));
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
