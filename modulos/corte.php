<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
	include("num2letras.php");

	$idsucursal = $_SESSION["idsucx9284hqmzt7"];
	$idcorte = mysqli_fetch_row(mysqli_query($con, "select max(idcorte) from tcortes where status = 0 and idsucursal = '" . $idsucursal . "'"))[0];
	$fondo = mysqli_fetch_row(mysqli_query($con, "select fondoinicial from tcortes where idcorte = $idcorte"))[0];
	$ventas = mysqli_fetch_row(mysqli_query($con, "select sum(total) from tcuentas where idcorte = $idcorte"))[0];
	$retiros = mysqli_fetch_row(mysqli_query($con, "select sum(monto) from tretiros where idcorte = $idcorte"))[0];
	$fondoFinal = ((float)$fondo+(float)$ventas)-(float)$retiros;

	if($_GET['idcorte']){
		$idcorte = $_GET['idcorte'];
		$fondo = mysqli_fetch_row(mysqli_query($con, "select fondoinicial from tcortes where idcorte = $idcorte"))[0];
		$ventas = mysqli_fetch_row(mysqli_query($con, "select sum(total) from tcuentas where idcorte = $idcorte"))[0];
		$retiros = mysqli_fetch_row(mysqli_query($con, "select sum(monto) from tretiros where idcorte = $idcorte"))[0];
		$fondoFinal = ((float)$fondo+(float)$ventas)-(float)$retiros;
		$folioinicial = mysqli_fetch_row(mysqli_query($con, "select min(folio) from tcuentas where idcorte = $idcorte"))[0];
		$foliofinal = mysqli_fetch_row(mysqli_query($con, "select max(folio) from tcuentas where idcorte = $idcorte"))[0];

		mysqli_query($con, "update tcortes set
					fechafinal = '".date("Y-m-d")."',
					horafinal = '".date("H:i:s")."',
					gastos = '$retiros',
					ventas = '$ventas',
					fondofinal = '$fondoFinal',
					folioinicial = '$folioinicial',
					foliofinal = '$foliofinal',
					status = 1
					where idcorte = '$idcorte'");

		//impresion del ticket
		include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/escpos.php");

		$corte = mysqli_fetch_assoc(mysqli_query($con, "select * from tcortes where idcorte = $idcorte"));
		$infoticket = mysqli_fetch_assoc(mysqli_query($con, "select ticket_negocio as negocio, ticket_calle as calle, ticket_numero as numero, ticket_colonia as colonia, ticket_codigopostal as codigopostal, ticket_ciudad as ciudad, ticket_nombre as nombre, ticket_rfc as rfc, ticket_regimen as regimen, ticket_nombreimpresora as nombreimpresora from tsucursales where idsucursal = '$idsucursal'"));

		$anchoTicket = 48;

		$idticket = "";
		for($i=strlen($idcorte);$i<7;$i++){
			$idticket .= "0";
		}
		$idticket = $idticket.$idcorte;
		$ticket = date("d/m/Y",strtotime($corte["fechafinal"]))." ".date("H:i:s A",strtotime($corte["horafinal"]))." ".$idticket;

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

		$escpos .= escposAlign("center");
		$escpos .= escposLinea("CORTE DE CAJA");
		$escpos .= escposAlign("left");
		$escpos .= escposLinea(str_repeat("=", $anchoTicket));

		$escpos .= escposFila(array(array("FONDO FINAL", 34, "left"), array('$'.number_format($corte['fondofinal'],2), 14, "right")));
		$escpos .= escposLinea("DESGLOSE:");
		$escpos .= escposFila(array(array("FONDO INICIAL (MXN)", 34, "left"), array('$'.number_format($corte['fondoinicial'],2), 14, "right")));
		if($corte['ventas']>0){
			$escpos .= escposFila(array(array("EFECTIVO (MXN)", 34, "left"), array('$'.number_format($corte['ventas'],2), 14, "right")));
		}
		$escpos .= escposFila(array(array("TOTAL DE GASTOS", 34, "left"), array('$'.number_format($corte['gastos'],2), 14, "right")));
		$escpos .= escposFila(array(array("FOLIO INICIAL DEL CORTE", 34, "left"), array($corte['folioinicial'], 14, "right")));
		$escpos .= escposFila(array(array("FOLIO FINAL DEL CORTE", 34, "left"), array($corte['foliofinal'], 14, "right")));

		$descripcion = strtoupper(num2letras(number_format($corte['fondofinal'],2,'.','')));
		$lineas = dividirTexto($descripcion,$anchoTicket);
		foreach($lineas as $linea){
			$escpos .= escposLinea($linea);
		}

		$escpos .= escposLinea(str_repeat("=", $anchoTicket));
		$escpos .= escposAlign("center");
		$escpos .= escposLinea("FIRMAS");
		$escpos .= escposAlign("left");
		$escpos .= escposLinea(str_repeat("=", $anchoTicket));
		$escpos .= escposAlign("center");
		$escpos .= escposLinea("CORTE DE CAJA");
		$escpos .= escposAbrirCajon();

		?>
		<script>
		parent.imprimirTicket(<? echo json_encode($infoticket["nombreimpresora"]);?>, '<? echo base64_encode($escpos);?>').then(function(){
			parent.location.href="../salir.php";
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
<title>Menu</title>
<script src="../assets/js/jquery.js"></script>
<style>
body{
	margin:0px;
}
</style>
</head>

<body>
<div style="position:relative;">
	<div style="position:absolute; width:300px; height:250px; top:0px; left:0px;"><img src="../assets/images/pantallaCorte.png" width="300" height="250" usemap="#Map" border="0" />
      <map name="Map" id="Map">
        <area shape="rect" coords="15,189,286,230" href="corte.php?idcorte=<? echo $idcorte; ?>" />
      </map>
  </div>

 <div style="position: absolute; width: 152px; height: 27px; top: 62px; left: 135px; z-index: 2; font-family: Arial, Helvetica, sans-serif; color: #FFF; font-size: 18px;">$<? echo number_format($fondo,2); ?></div>
 <div style="position: absolute; width: 152px; height: 27px; top: 101px; left: 85px; z-index: 2; font-family:Arial, Helvetica, sans-serif; color:#FFF; font-size:18px;">$<? echo number_format($retiros,2); ?></div>
 <div style="position: absolute; width: 118px; height: 27px; top: 144px; left: 173px; z-index: 2; font-family:Arial, Helvetica, sans-serif; color:#FFF; font-size:21px;">$<? echo number_format($ventas,2); ?></div>
</div>
</body>
</html>
<?
}
?>
