<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
	if($_POST["enviar"]==1 && $_POST["m3849ux3289n"]==$_SESSION["authToken"]){
		unset($_SESSION["authToken"]);
		include("num2letras.php");
		$total = $_POST["total"];
		$efectivo = $_POST["txtEfectivo"];

		$corte = mysqli_fetch_assoc(mysqli_query($con, "select * from tcortes where status = 0 order by idcorte desc limit 1"));

		mysqli_query($con, "insert into tcuentas values(null,'".$corte["idcorte"]."','".$total."','".($efectivo-$total)."','".date("Y-m-d")."','".date("H:i:s")."')");
		$idcuenta = mysqli_insert_id($con);

		$infoticket = mysqli_fetch_assoc(mysqli_query($con, "select * from tinfoticket where id = '1'"));

		function countCaracteres($string){
			$caracteres = "., ";
			$count=0;
			for($i=0;$i < strlen($string);$i++){
				if (strpos($caracteres, $string[$i]) !== false) {
						$count++;
				}
			}
			return $count;
		}

		function dividirTexto($cadena,$length){
			$palabras = explode(" ",$cadena);
			$texto = "";
			$lineas = array();
			foreach($palabras as $palabra){
				if((strlen($texto) + strlen($palabra))<=$length){
					$texto .= $palabra." ";
				}else{
					if(strlen($texto)>0){
						$texto = substr($texto,0,-1);
					}
					$lineas[] = $texto;
					$texto = $palabra." ";
				}
			}
			if(strlen($texto)>0){
				$texto = substr($texto,0,-1);
				$lineas[] = $texto;
			}
			return $lineas;
		}

		$printer = $infoticket["nombreimpresora"];
		$enlace=printer_open($printer);

		$margenes = 5;

		printer_set_option($enlace, PRINTER_MODE, "RAW");
		printer_set_option($enlace, PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM);
		printer_set_option($enlace,PRINTER_PAPER_WIDTH,80);

		printer_start_doc($enlace, "Ticket");
		printer_start_page($enlace);

		$fontL = printer_create_font("Arial", 24, 10, PRINTER_FW_BOLD, false, false, false, 0);
		$fontM = printer_create_font("Arial", 21, 8, PRINTER_FW_NORMAL, false, false, false, 0);
		$fontS = printer_create_font("Arial", 21, 8, PRINTER_FW_LIGHT, false, false, false, 0);

		$caracteresCol = array(5,17,8,8,48);
		$coordCol = array(5,90,300,400);
		$saltoLinea = array(24,22,22,196,66);
		$coordActual = 10;

		$idticket = "";
		for($i=strlen($idcuenta);$i<7;$i++){
			$idticket .= "0";
		}
		$idticket = $idticket.$idcuenta;
		$ticket .= date("d/m/Y")." ".date("H:i:s a")." ".$idticket;

		printer_select_font($enlace, $fontL);
		printer_draw_text($enlace,str_pad($infoticket["negocio"],((40-strlen($infoticket["negocio"]))*2)+(countCaracteres($infoticket["negocio"])*2)+strlen($infoticket["negocio"]), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[0];
		printer_select_font($enlace, $fontM);
		printer_draw_text($enlace,str_pad($infoticket["calle"]." No. ".$infoticket["numero"],((46-strlen($infoticket["calle"]." No. ".$infoticket["numero"]))*2)+(countCaracteres($infoticket["calle"]." No. ".$infoticket["numero"])*2)+strlen($infoticket["calle"]." No. ".$infoticket["numero"]), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[1];
		printer_draw_text($enlace,str_pad($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"],((46-strlen($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"]))*2)+(countCaracteres($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"])*2)+strlen($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"]), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[1];
		printer_draw_text($enlace,str_pad($infoticket["ciudad"],((44-strlen($infoticket["ciudad"]))*2)+(countCaracteres($infoticket["ciudad"])*2)+strlen($infoticket["ciudad"]), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[1];
		printer_draw_text($enlace,str_pad($infoticket["nombre"],((44-strlen($infoticket["nombre"]))*2)+(countCaracteres($infoticket["nombre"])*2)+strlen($infoticket["nombre"]), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[1];
		printer_draw_text($enlace,str_pad($infoticket["rfc"],((46-strlen($infoticket["rfc"]))*2)+(countCaracteres($infoticket["rfc"])*2)+strlen($infoticket["rfc"]), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[1];
		printer_draw_text($enlace,str_pad($infoticket["regimen"],((46-strlen($infoticket["regimen"]))*2)+(countCaracteres($infoticket["regimen"])*2)+strlen($infoticket["regimen"]), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[1];
		printer_draw_text($enlace,str_pad($ticket,((48-strlen($ticket))*2)+(countCaracteres($ticket)*2)+strlen($ticket), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[1];
		printer_draw_text($enlace,"==============================================",5,$coordActual);
		$coordActual += $saltoLinea[1];

		printer_draw_text($enlace,"CANT.",$coordCol[0],$coordActual);
		printer_draw_text($enlace,"PRODUCTO",$coordCol[1],$coordActual);
		printer_draw_text($enlace,"PRECIO",$coordCol[2],$coordActual);
		printer_draw_text($enlace,"IMPORTE",$coordCol[3],$coordActual);
		$coordActual += $saltoLinea[1];

		printer_select_font($enlace,$fontS);

		$articulos = 0;
		$productos = mysqli_query($con, "select * from trcuentaproductostmp order by idtmp");
		while($producto = mysqli_fetch_assoc($productos)){
			mysqli_query($con, "update tproductos set unidades = unidades - ".$producto["cantidad"]." where idproducto = '".$producto["idproducto"]."' and servicio = 0");
			$articulos += $producto["cantidad"];
			$numLinea = 1;
			$cantidad = $producto["cantidad"];
			$nombre = mysqli_fetch_row(mysqli_query($con, "select nombre from tproductos where idproducto = '".$producto["idproducto"]."'"))[0];
			$precio = "$".number_format($producto["precio"],2);
			$importe = "$".number_format($producto["precio"]*$producto["cantidad"],2);
			$lineas = dividirTexto($nombre,$caracteresCol[1]);
			foreach($lineas as $linea){
				if($numLinea>1){
					$cantidad = "";
					$precio = "";
					$importe = "";
				}
				printer_draw_text($enlace,$cantidad,$coordCol[0],$coordActual);
				printer_draw_text($enlace,$linea,$coordCol[1],$coordActual);
				printer_draw_text($enlace,$precio,$coordCol[2],$coordActual);
				printer_draw_text($enlace,$importe,$coordCol[3],$coordActual);
				$numLinea++;
				$coordActual += $saltoLinea[2];
			}
		}

		printer_draw_text($enlace,"==============================================",5,$coordActual);
		$coordActual += $saltoLinea[2];

		printer_draw_text($enlace,"TOTAL",180,$coordActual);
		printer_draw_text($enlace,"$".number_format($total,2),290,$coordActual);
		$coordActual += $saltoLinea[2];
		printer_draw_text($enlace,"EFECTIVO",180,$coordActual);
		printer_draw_text($enlace,"$".number_format($efectivo,2),290,$coordActual);
		$coordActual += $saltoLinea[2];
		printer_draw_text($enlace,"CAMBIO",180,$coordActual);
		printer_draw_text($enlace,"$".number_format($efectivo-$total,2),290,$coordActual);
		$coordActual += $saltoLinea[4];

		$numLinea=1;
		$descripcion = strtoupper(num2letras(number_format($total,2,'.','')));
		$lineas = dividirTexto($descripcion,$caracteresCol[4]);

		foreach($lineas as $linea){
			printer_draw_text($enlace,$linea,$coordCol[0],$coordActual);
			$numLinea++;
			$coordActual += $saltoLinea[2];
		}

		printer_draw_text($enlace,"==============================================",5,$coordActual);
		$coordActual += $saltoLinea[2];

		printer_draw_text($enlace,str_pad("ARTICULOS: ".$articulos,((48-strlen("ARTICULOS: ".$articulos))*2)+(countCaracteres("ARTICULOS: ".$articulos)*2)+strlen("ARTICULOS: ".$articulos), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[2];

		printer_draw_text($enlace,"==============================================",5,$coordActual);
		$coordActual += $saltoLinea[2];

		printer_draw_text($enlace,str_pad("GRACIAS POR SU COMPRA",((46-strlen("GRACIAS POR SU COMPRA"))*2)+(countCaracteres("GRACIAS POR SU COMPRA")*2)+strlen("GRACIAS POR SU COMPRA"), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[4];

		printer_draw_text($enlace," ",5,$coordActual);

		printer_write($enlace,chr(27).chr(112).chr(0).chr(100).chr(250));

		printer_delete_font($fontL);
		printer_end_page($enlace);
		printer_end_doc($enlace);
		printer_close($enlace);

		mysqli_query($con, "insert into trcuentaproductos (idcuenta,idproducto,cantidad,precio) select $idcuenta,idproducto,cantidad,precio from trcuentaproductostmp");
		mysqli_query($con, "truncate trcuentaproductostmp");
		?>

		<script>
		parent.eliminarTodo();
		parent.fancy(500,250,'modulos/gracias.php?idcuenta=<? echo $idcuenta;?>&cambio=<? echo ($efectivo-$total);?>');
		</script>
    <?
	}else{
		$total = mysqli_fetch_row(mysqli_query($con, "select sum(precio*cantidad) from trcuentaproductostmp"))[0];
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
