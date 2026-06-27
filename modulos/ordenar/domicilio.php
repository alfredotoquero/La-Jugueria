<?
ini_set("session.gc_maxlifetime","43200");  
session_name("gt4e57i6rhdrg");
session_start();
include("../../002wf3f3kgdvr/983y4rhouConRem.php");
include("../../002wf3f3kgdvr/983y4rhou.php");
include("../num2letras.php");
$idcliente = $_SESSION['idcliente'];
$total = mysql_result(mysql_query("select sum((precio*((100-descuento)/100))*cantidad) from trcuentamenutmp where idingrediente = 0"),0);
$tipocambio = mysql_result(mysql_query("select parametro from tparametros where idparametro = 'tipoCambio'"),0);
if($_GET["enviar"]==1){
	$totalapagar = $_GET["txtCambio"];
	$corte = mysql_fetch_assoc(mysql_query("select * from tcortes order by idcorte desc limit 1"));
	$idsucursal = $corte["idsucursal"];
	
	$nombreCliente = mysql_result(mysql_query("select nombre from tclientes where idcliente = '$idcliente'"),0);
	$direccionCliente = mysql_result(mysql_query("select direccion from tclientes where idcliente = '$idcliente'"),0);
	
	$nombreCuenta = "";
	mysql_query("insert into tcuentas values(null,'".$corte["idcorte"]."','".$corte["idsucursal"]."','".$corte["idcaja"]."','".$corte["idcajero"]."','$idcliente','$nombreCliente','0','0','".$total."','2','A','".date("Y-m-d-")."','".date("H:i:s")."','0000-00-00')");
	$idcuenta = mysql_insert_id();
	
	$nombreCajero = mysql_result(mysql_query("select concat(nombre,' ',apellido) from tusuarios where idusuario = '".$corte["idcajero"]."'"),0);
	
	$sucursal = mysql_fetch_assoc(mysql_query("select * from tcatsucursales where idsucursal = '".$idsucursal."'"));
	$ticket .= "
	<table width=\"600px\"  cellspacing=\"0\" cellpadding=\"0\" align=\"left\" class=\"impresion\">
	<tr><td align=\"center\" >
	<span class=\"titImpresion\"><b>HIJOS DEL SUSHI</b></span><br>".
	$sucursal["calle"]." ".$sucursal["numero"]."<br>".
	$sucursal["colonia"]."<br>
	CP ".$sucursal["codigopostal"]." ".$sucursal["ciudad"]."<br>
	RFC ".$sucursal["rfc"]."<br>
	Sucursal ".$sucursal["sucursal"]."<br>";
	$idticket = "";
	for($i=strlen($idcuenta);$i<7;$i++){
		$idticket .= "0";
	}
	$idticket = $idticket.$idcuenta;
	$ticket .= date("d/m/Y")." ".date("H:i:s a")." ".$idticket."
	</td>
	</tr>
	<tr>
	<td align=\"center\">==========================================</td>
	</tr>
	<tr>
	<td>CLIENTE: ".$nombreCliente."<br>
	DOMICILIO: ".$direccionCliente."<br>
	CAJERO: ".$nombreCajero."
	</td>
	</tr>
	<tr>
	<td align=\"center\">==========================================</td>
	</tr>
	<tr>
	<td>
	<table width=\"600px\" cellspacing=\"0\" cellpadding=\"2\" class=\"impresion\">
	<tr>
	<td width=\"10%\" align=\"right\">CANT.</td>
	<td width=\"60%\" align=\"left\">PLATILLO</td>
	<td width=\"15%\" align=\"center\">PRECIO</td>
	<td width=\"15%\" align=\"center\">IMPORTE</td>
	</tr>";
	$articulos = 0;
	$platillos = mysql_query("select * from trcuentamenutmp where idingrediente = 0");
	while($platillo = mysql_fetch_assoc($platillos)){
		if($platillo["tipo"]==0 && $platillo["cqsr"]==0 && $platillo["itta"]==0){ $nombrePlatillo =  mysql_result(mysql_query("select nombre from tmenu where idmenu = '".$platillo["idmenu"]."'"),0); }else if($platillo["tipo"]==1){ $nombrePlatillo = mysql_result(mysql_query("select producto from tinventario where idproducto = '".$platillo["idmenu"]."'"),0); }else if($platillo["cqsr"]==1){ $nombrePlatillo =  "Cada quien su rollo"; }else{ $nombrePlatillo =  "Itta-kate"; }
		$articulos += $platillo["cantidad"];
		$ticket .= "<tr><td width=\"10%\" align=\"center\">".$platillo["cantidad"]."</td><td width=\"60%\" align=\"left\">".$nombrePlatillo."</td><td width=\"15%\" align=\"center\">$".number_format($platillo["precio"]*((100-$platillo["descuento"])/100),2)."</td><td width=\"15%\" align=\"center\">$".number_format(($platillo["precio"]*((100-$platillo["descuento"])/100))*$platillo["cantidad"],2)."</td></tr>";
		if($platillo["cqsr"]==0 && $platillo["itta"]==0){
			$ingredientes = mysql_query("select * from trcuentamenutmp where idingrediente > 0 and idmenu = '".$platillo["idmenu"]."'");
			while($ingrediente = mysql_fetch_assoc($ingredientes)){
				$ticket .= "<tr><td width=\"10%\" align=\"right\"></td><td width=\"60%\" align=\"left\">* ".$ingrediente["cantidad"]." SIN ".mysql_result(mysql_query("select producto from tinventario where idproducto = '".$ingrediente["idingrediente"]."'"),0)."</td><td width=\"15%\" align=\"center\"></td><td width=\"15%\" align=\"center\"></td></tr>";		
			}
		}/*else{
			if($platillo["cqsr"]>0){
				$ingredientes = mysql_query("select * from trcuentamenucqsrtmp where idcqsr = '".$platillo["cqsr"]."'");
				while($ingrediente = mysql_fetch_assoc($ingredientes)){
					$ticket .= "<tr><td width=\"10%\" align=\"right\"></td><td width=\"60%\" align=\"left\">  ".mysql_result(mysql_query("select producto from tinventario where idproducto = '".$ingrediente["idingrediente"]."'"),0)." (".mysql_result(mysql_query("select nombre from tcatcategoriascqsr where idcategoria = '".$ingrediente["idcategoria"]."'"),0).")"."</td><td width=\"15%\" align=\"center\"></td><td width=\"15%\" align=\"center\"></td></tr>";		
				}
			}
			if($platillo["itta"]>0){
				$platillositta = mysql_query("select * from trcuentamenuittatmp where idittakate = '".$platillo["itta"]."'");
				while($platilloitta = mysql_fetch_assoc($platillositta)){
					$ticket .= "<tr><td width=\"10%\" align=\"right\"></td><td width=\"60%\" align=\"left\">  ".mysql_result(mysql_query("select platillo from tplatillosittakate where idplatillo = '".$platilloitta["idplatillo"]."'"),0)." (Categoria ".$platilloitta["idcategoria"].")"."</td><td width=\"15%\" align=\"center\"></td><td width=\"15%\" align=\"center\"></td></tr>";		
				}
			}
		}*/
	}
	$ticket .= "
	</table>
	</td>
	</tr>
	<tr><td align=\"center\">==========================================</td></tr>
	<tr><td>
	<table width=\"600px\" cellspacing=\"0\" cellpadding=\"0\" class=\"impresion\">
	<tr><td width=\"30%\"></td><td width=\"30%\">TOTAL</td><td width=\"30%\" align=\"left\">$".number_format($total,2)."</td></tr>
	<tr><td width=\"30%\"></td><td width=\"30%\">EFECTIVO</td><td width=\"30%\" align=\"left\">$".number_format($totalapagar,2)."</td></tr>";
	$pt = (int)$total;
	$centavos = (int)((($total)-((float)$pt))*100);
	$ticket .= "<tr><td width=\"30%\"></td><td width=\"30%\">CAMBIO</td><td width=\"30%\" align=\"left\">$".number_format($totalapagar-$total,2)."</td></tr>
	</table>
	</td></tr>
	<tr><td align=\"center\">".num2letras(number_format($total,2,'.',''))."</td></tr>
	<tr><td align=\"center\">==========================================</td></tr>
	<tr><td align=\"center\">ARTICULOS ".$articulos."</td></tr>
	<tr><td align=\"center\">==========================================</td></tr>
	<tr><td align=\"center\"><b>GRACIAS POR SU COMPRA<br>
	WWW.HIJOSDELSUSHI.COM</b></td></tr>
	<tr><td align=\"center\"><br><br><br><br><br><br><br></td></tr>
	</table>";
	echo "<html>
	<head>
	<style>
	.impresion{
		font:20pt sans-serif;
	}
	.titImpresion{
		font:26pt sans-serif;
	}
	</style>		
	</head>
	<body>".$ticket."<br>".$ticket."</body></html>";
	mysql_query("insert into trcuentatickets values('".$idcuenta."','".$total."','".($totalapagar-$total)."','".$ticket."','".date("Y-m-d")."','".date("H:i:s")."')");
	mysql_query("insert into trcuentamenu select $idcuenta,idmenu,idingrediente,cantidad,tipo,cqsr,itta,precio,descuento from trcuentamenutmp");
	mysql_query("insert into trcuentamenucqsr select $idcuenta,idcqsr,idcategoria,idingrediente from trcuentamenucqsrtmp");
	mysql_query("insert into trcuentamenuitta select $idcuenta,idittakate,idcategoria,idplatillo from trcuentamenuittatmp");
	mysql_query("truncate trcuentamenutmp");
	mysql_query("truncate trcuentamenucqsrtmp");
	mysql_query("truncate trcuentamenuittatmp");
	?>
	<script>
	window.print();
	parent.eliminarTodo();
	parent.fancy(500,250,'modulos/gracias.php?subtotal=<? echo ($totalapagar-$total);?>');
    </script>
	<?
}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../../js/jquery.js"></script>
<script>
var total = '<? echo $total;?>';
$(document).ready(function(){
	$("#txtCambio").focus();
	$("#txtCambio").val(total);
	$(document).keydown(manejarEventos);
});

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==27){
		evento.preventDefault();
		parent.$.fancybox.close();
	}
	if(code==13){
		evento.preventDefault();
		if($("#txtCambio").val()>=total){
			location.href = 'domicilio.php?enviar=1&txtCambio=' + $("#txtCambio").val();
		}else{
			alert("ERROR: La cantidad es menor al total de la cuenta.");
		}
	}
}
</script>
<style>
body{
	margin:0px;
}
</style>
</head>

<body>
<div style="position:relative;" id="divContenido">
	<div style="position:absolute; width:306px; height:130px; top:0px; left:0px;">
    	<img src="../../images/pantallaCambioDe.jpg" width="306" height="130" border="0" />
    	<div style="position:absolute; width:195px; height:24px; top:75px; left:95px;"><input type="text" name="txtCambio" id="txtCambio" value="" style="border:0px; width:100%; height:100%;" /></div>
    </div>
</div>
</body>
</html>
<?
}
?>