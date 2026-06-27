<?
ini_set("session.gc_maxlifetime","43200");
session_name("2q093ex8uq2ewun");
session_start();
date_default_timezone_set('America/Los_Angeles');
include("../002wf3f3kgdvr/983y4rhouCon.php");
include("../002wf3f3kgdvr/983y4rhou.php");
if($_GET["imprimir"]==1){
	include("num2letras.php");

	$corte = mysql_fetch_assoc(mysql_query("select * from tcortes where status = 0 order by idcorte desc limit 1"));

	$idcuenta = $_GET["idcuenta"];
	$cuenta = mysql_fetch_assoc(mysql_query("select * from tcuentas where idcuenta = '$idcuenta'"));
	$total = $cuenta["total"];
	$efectivo = (float)$cuenta["total"] + (float)$cuenta["cambio"];
	$infoticket = mysql_fetch_assoc(mysql_query("select * from tinfoticket where id = '1'"));

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
	$productos = mysql_query("select * from trcuentaproductos where idcuenta = ".$idcuenta." order by idcuentaproducto");
	while($producto = mysql_fetch_assoc($productos)){
		mysql_query("update tproductos set unidades = unidades - ".$producto["cantidad"]." where idproducto = '".$producto["idproducto"]."' and servicio = 0");
		$articulos += $producto["cantidad"];
		$numLinea = 1;
		$cantidad = $producto["cantidad"];
		$nombre = mysql_result(mysql_query("select nombre from tproductos where idproducto = '".$producto["idproducto"]."'"),0);
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
	?>
    <script>
		parent.$.fancybox.close();
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
