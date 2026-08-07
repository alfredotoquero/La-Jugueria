<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
	$idsucursal = $_SESSION["idsucx9284hqmzt7"];
	if($_POST["enviar"]==1 && $_POST["m3849ux3289n"]==$_SESSION["authToken"]){
		unset($_SESSION["authToken"]);
		include("num2letras.php");
		$total = $_POST["total"];
		$efectivo = $_POST["txtEfectivo"];

		$corte = mysqli_fetch_assoc(mysqli_query($con, "select * from tcortes where status = 0 and idsucursal = '" . $idsucursal . "' order by idcorte desc limit 1"));

		mysqli_query($con, "update tfolios set ultimofolio = LAST_INSERT_ID(ultimofolio + 1) where idsucursal = '$idsucursal'");
		$folio = mysqli_insert_id($con);

		mysqli_query($con, "insert into tcuentas (idcuenta,idcorte,idsucursal,folio,total,cambio,fecha,hora) values(null,'".$corte["idcorte"]."','".$idsucursal."','".$folio."','".$total."','".($efectivo-$total)."','".date("Y-m-d")."','".date("H:i:s")."')");
		$idcuenta = mysqli_insert_id($con);

		include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/escpos.php");

		$infoticket = mysqli_fetch_assoc(mysqli_query($con, "select ticket_negocio as negocio, ticket_calle as calle, ticket_numero as numero, ticket_colonia as colonia, ticket_codigopostal as codigopostal, ticket_ciudad as ciudad, ticket_nombre as nombre, ticket_rfc as rfc, ticket_regimen as regimen, ticket_nombreimpresora as nombreimpresora from tsucursales where idsucursal = '$idsucursal'"));

		$anchoTicket = 48;

		$idticket = "";
		for($i=strlen($folio);$i<7;$i++){
			$idticket .= "0";
		}
		$idticket = $idticket.$folio;
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

		$escpos .= escposFila(array(
			array("CANT.", 5, "left"),
			array("PRODUCTO", 19, "left"),
			array("PRECIO", 10, "right"),
			array("IMPORTE", 14, "right")
		));

		$articulos = 0;
		$productos = mysqli_query($con, "select * from trcuentaproductostmp where idsucursal = '$idsucursal' order by idtmp");
		while($producto = mysqli_fetch_assoc($productos)){
			mysqli_query($con, "update tproductosucursales tps join tproductos tp on tp.idproducto = tps.idproducto set tps.unidades = tps.unidades - ".$producto["cantidad"]." where tps.idproducto = '".$producto["idproducto"]."' and tps.idsucursal = '$idsucursal' and tp.servicio = 0");
			$articulos += $producto["cantidad"];
			$numLinea = 1;
			$cantidad = $producto["cantidad"];
			$nombre = mysqli_fetch_row(mysqli_query($con, "select nombre from tproductos where idproducto = '".$producto["idproducto"]."'"))[0];
			$precio = "$".number_format($producto["precio"],2);
			$importe = "$".number_format($producto["precio"]*$producto["cantidad"],2);
			$lineas = dividirTexto($nombre,19);
			foreach($lineas as $linea){
				$escpos .= escposFila(array(
					array($numLinea==1 ? $cantidad : "", 5, "left"),
					array($linea, 19, "left"),
					array($numLinea==1 ? $precio : "", 10, "right"),
					array($numLinea==1 ? $importe : "", 14, "right")
				));
				$numLinea++;
			}
		}

		$escpos .= escposLinea(str_repeat("=", $anchoTicket));
		$escpos .= escposFila(array(array("TOTAL", 34, "left"), array("$".number_format($total,2), 14, "right")));
		$escpos .= escposFila(array(array("EFECTIVO", 34, "left"), array("$".number_format($efectivo,2), 14, "right")));
		$escpos .= escposFila(array(array("CAMBIO", 34, "left"), array("$".number_format($efectivo-$total,2), 14, "right")));

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

		mysqli_query($con, "insert into trcuentaproductos (idcuenta,idproducto,cantidad,precio) select $idcuenta,idproducto,cantidad,precio from trcuentaproductostmp where idsucursal = '$idsucursal'");
		mysqli_query($con, "delete from trcuentaproductostmp where idsucursal = '$idsucursal'");
		?>

		<script>
		parent.imprimirTicket(<? echo json_encode($infoticket["nombreimpresora"]);?>, '<? echo base64_encode($escpos);?>').then(function(){
			parent.eliminarTodo();
			parent.fancy(500,250,'modulos/gracias.php?idcuenta=<? echo $idcuenta;?>&cambio=<? echo ($efectivo-$total);?>');
		});
		</script>
    <?
	}else{
		$total = mysqli_fetch_row(mysqli_query($con, "select sum(precio*cantidad) from trcuentaproductostmp where idsucursal = '$idsucursal'"))[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
<script src="../assets/js/jquery.js"></script>
<script>
var total = <? echo $total;?>;
$(document).ready(function(){
	$("#txtEfectivo").focus();
	$(document).keydown(manejarEventos);
});

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code>=112 && code<=123){
		evento.preventDefault();
	}
	if(code==27){
		evento.preventDefault();
		parent.$.fancybox.close();
	}
	if(code==13){
		evento.preventDefault();
		mostrarSubtotales();
	}
}

function mostrarSubtotales(){
	var subtotal = Number(total)-Number($("#txtEfectivo").val());
	if(subtotal<=0){
		subtotal = 0;
		$("#subtotal").val(subtotal*(-1));
		$("#formCobrar").submit();
	}else{
		pagado = false;
	}
	$("#tSubTotal").html("$" + subtotal.toFixed(2));
}
</script>
<style>
body{
	margin:0px;
}
.campo{
	border:0px;
	outline:0px;
	background:none;
}
</style>
</head>

<body onLoad="txtEfectivo.focus();">
<?
unset($_SESSION["authToken"]);
$_SESSION["authToken"]=sha1(uniqid(microtime(), true));
?>
<form name="formCobrar" id="formCobrar" action="cobrar.php" method="post" onKeyDown="if(event.keyCode == 13){ event.cancelBubble = true; event.returnValue = false; }">
<input type="hidden" name="enviar" value="1" />
<input type="hidden" name="total" value="<? echo $total;?>" />
<input type="hidden" name="subtotal" id="subtotal" value="" />
<input type="hidden" name="m3849ux3289n" value="<? echo $_SESSION["authToken"];?>" />
<div style="position:relative;">
	<div style="position:absolute; width:300px; height:238px; top:0px; left:0px;"><img src="../assets/images/pantallaCobro.png" width="300" height="238" usemap="#Map" border="0" />
      <map name="Map" id="Map">
        <area shape="rect" coords="14,379,286,55" href="javascript:;" onClick="mostrarSubtotales();" />
      </map>
	</div>
    <div style="position:absolute; width:121px; height:29px; top:69px; left:165px;">
    	<table width="132" border="0" cellpadding="0" cellspacing="0">
        	<tr><td style="font-size:24px; font-weight:bold; color:#FFF;" height="28" align="left" valign="middle">$<? echo number_format($total,2);?></td></tr>
        </table>
    </div>
    <div style="position:absolute; width:140px; height:20px; top:111px; left:138px; border:0px;"><input name="txtEfectivo" type="text" id="txtEfectivo" class="campo" style="border:0px; width:100%; height:100%;" onKeyUp="manejarEventos(event);" /></div>
    <div style="position:absolute; width:147px; height:29px; top:148px; left:145px;">
    	<table width="142" border="0" cellpadding="0" cellspacing="0">
        	<tr><td style="font-size:18px; font-weight:bold; color:#FFF;" height="20" align="left" valign="middle" id="tSubTotal">$<? echo number_format($total,2);?></td></tr>
        </table>
    </div>
</div>
</form>
</body>
</html>
<?
	}
?>
