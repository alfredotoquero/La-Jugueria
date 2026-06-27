<?
    ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	if($_GET["enviar"]==1){
		include("../num2letras.php");
		$idcliente = $_GET["idcliente"];
		$idcuenta = $_GET["idcuenta"];
		$tipocambio = $_GET["tipocambio"];
		$total = $_GET["total"];
		$tmxn = $_GET["txtEfectivo"];
		$tusd = $_GET["txtEfectivoDLLS"];
		$ttarjeta = $_GET["txtTarjeta"];
		
		$corte = mysql_fetch_assoc(mysql_query("select * from tcortes order by idcorte desc limit 1"));
		$idsucursal = $corte["idsucursal"];
		
		$nombreCliente = "PUBLICO EN GENERAL";
		if($idcliente>0){
			$nombreCliente = mysql_result(mysql_query("select nombre from tclientes where idcliente = '$idcliente'"),0);
		}
		
		$nombreCuenta = mysql_result(mysql_query("select nombre from tcuentas where idcuenta = '$idcuenta'"),0);
		
		$nombreCajero = mysql_result(mysql_query("select concat(nombre,' ',apellido) from tusuarios where idusuario = '".$corte["idcajero"]."'"),0);
		
		$sucursal = mysql_fetch_assoc(mysql_query("select * from tcatsucursales where idsucursal = '".$idsucursal."'"));
		$corte = mysql_fetch_assoc(mysql_query("select * from tcortes order by idcorte desc limit 1"));
		$ticket .= "
		<html>
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
		<body>
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
		CAJERO: ".$nombreCajero."<br>
		CUENTA: ".$nombreCuenta."
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
			}
		}
		$ticket .= "
		</table>
		</td>
		</tr>
		<tr><td align=\"center\">==========================================</td></tr>
		<tr><td>
		<table width=\"600px\" cellspacing=\"0\" cellpadding=\"0\" class=\"impresion\">
		<tr><td width=\"30%\"></td><td width=\"30%\">TOTAL</td><td width=\"30%\" align=\"left\">$".number_format($total,2)."</td></tr>
		<tr><td width=\"30%\"></td><td width=\"30%\">EFECTIVO</td><td width=\"30%\" align=\"left\">$".number_format($tmxn,2)."</td></tr>";
		if($tusd>0){
			$ticket .= "<tr><td width=\"30%\" align=\"center\">".number_format($tusd,2)."</td><td width=\"30%\">DOLARES</td><td width=\"30%\" align=\"left\">$".number_format($tusd*$tipocambio,2)."</td></tr>";
		}
		if($ttarjeta>0){
			$ticket .= "<tr><td width=\"30%\" align=\"center\"></td><td width=\"30%\">TARJETA</td><td width=\"30%\" align=\"left\">$".number_format($ttarjeta,2)."</td></tr>";
		}
		if($tusd>0 && $tmxn>0){
			$ticket .= "<tr><td width=\"30%\"></td><td width=\"30%\">SALDO</td><td width=\"30%\" align=\"left\">$".number_format(($tmxn+($tusd*$tipocambio)+$ttarjeta),2)."</td></tr>";
		}
		$pt = (int)$total;
		$centavos = (int)((($total)-((float)$pt))*100);
		$ticket .= "<tr><td width=\"30%\"></td><td width=\"30%\">CAMBIO</td><td width=\"30%\" align=\"left\">$".number_format((($tmxn+($tusd*$tipocambio)+$ttarjeta))-$total,2)."</td></tr>
		</table>
		</td></tr>
		<tr><td align=\"center\">".num2letras(number_format($total,2,'.',''))."</td></tr>
		<tr><td align=\"center\">==========================================</td></tr>
		<tr><td align=\"center\">ARTICULOS ".$articulos."</td></tr>
		<tr><td align=\"center\">==========================================</td></tr>
		<tr><td align=\"center\"><b>GRACIAS POR SU COMPRA<br>
		WWW.HIJOSDELSUSHI.COM</b></td></tr>
		</table></body></html>";
		echo $ticket;
		?>
        <script>
		window.print();
		parent.eliminarTodo();
		parent.fancy(500,250,'modulos/gracias.php?subtotal=<? echo (($tmxn+($tusd*$tipocambio)+$ttarjeta)-$total);?>');
		</script>
        <?
	}
	?>