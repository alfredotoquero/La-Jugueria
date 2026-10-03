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

		// El folio del corte se consume hasta el cierre, no en la apertura: asi
		// un corte abandonado no quema folio. Mismo patron atomico que usa
		// cobrar.php para el folio de la venta.
		$foliocorte = mysqli_fetch_row(mysqli_query($con, "select folio from tcortes where idcorte = $idcorte"))[0];
		if($foliocorte<=0){
			mysqli_query($con, "update tfolios set ultimofolio_corte = LAST_INSERT_ID(ultimofolio_corte + 1) where idsucursal = '$idsucursal'");
			$foliocorte = mysqli_insert_id($con);
		}

		mysqli_query($con, "update tcortes set
					folio = '$foliocorte',
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
		$infoticket = infoTicketSucursal($con, $idsucursal);

		$anchoTicket = anchoTicket($infoticket["tamanoimpresion"]);

		$idticket = "";
		for($i=strlen($corte["folio"]);$i<7;$i++){
			$idticket .= "0";
		}
		$idticket = $idticket.$corte["folio"];
		$ticket = date("d/m/Y",strtotime($corte["fechafinal"]))." ".date("H:i:s A",strtotime($corte["horafinal"]))." ".$idticket;

		$escpos = escposInit();
		$escpos .= escposEncabezado($infoticket, $ticket, $anchoTicket);

		$escpos .= escposAlign("center");
		$escpos .= escposLinea("CORTE DE CAJA");
		$escpos .= escposAlign("left");
		$escpos .= escposSeparador($anchoTicket);

		$escpos .= escposFilaMonto("FONDO FINAL", '$'.number_format($corte['fondofinal'],2), $anchoTicket);
		$escpos .= escposLinea("DESGLOSE:");
		$escpos .= escposFilaMonto("FONDO INICIAL (MXN)", '$'.number_format($corte['fondoinicial'],2), $anchoTicket);
		if($corte['ventas']>0){
			$escpos .= escposFilaMonto("EFECTIVO (MXN)", '$'.number_format($corte['ventas'],2), $anchoTicket);
		}
		$escpos .= escposFilaMonto("TOTAL DE GASTOS", '$'.number_format($corte['gastos'],2), $anchoTicket);
		$escpos .= escposFilaMonto("FOLIO INICIAL DEL CORTE", $corte['folioinicial'], $anchoTicket);
		$escpos .= escposFilaMonto("FOLIO FINAL DEL CORTE", $corte['foliofinal'], $anchoTicket);

		$descripcion = strtoupper(num2letras(number_format($corte['fondofinal'],2,'.','')));
		$escpos .= escposParrafo($descripcion, $anchoTicket);

		$escpos .= escposSeparador($anchoTicket);
		$escpos .= escposAlign("center");
		$escpos .= escposLinea("FIRMAS");
		$escpos .= escposAlign("left");
		$escpos .= escposSeparador($anchoTicket);
		$escpos .= escposAlign("center");
		$escpos .= escposLinea("CORTE DE CAJA");
		$escpos .= escposAbrirCajon();
		$escpos .= escposCorte();

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
