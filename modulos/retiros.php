<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");

if($_POST['enviar']==1){
	$idsucursal = $_SESSION["idsucx9284hqmzt7"];
	$monto = $_POST['txtMonto'];
	$descripcion = strtoupper($_POST['txtDescripcion']);
	$idcorte = mysqli_fetch_row(mysqli_query($con, "select MAX(idcorte) from tcortes where status = 0 and idsucursal = '" . $idsucursal . "'"))[0];
	$fecha = date('Y-m-d');
	$hora = date('H:i:s');
	mysqli_query($con, "insert into tretiros values(NULL,'$idcorte','$monto','$descripcion','$fecha','$hora')");
	$idretiro = mysqli_insert_id($con);
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/escpos.php");

	$infoticket = mysqli_fetch_assoc(mysqli_query($con, "select ticket_negocio as negocio, ticket_calle as calle, ticket_numero as numero, ticket_colonia as colonia, ticket_codigopostal as codigopostal, ticket_ciudad as ciudad, ticket_nombre as nombre, ticket_rfc as rfc, ticket_regimen as regimen, ticket_nombreimpresora as nombreimpresora from tsucursales where idsucursal = '$idsucursal'"));

			$anchoTicket = 32;

			$idticket = "";
			for($i=strlen($idretiro);$i<7;$i++){
				$idticket .= "0";
			}
			$idticket = $idticket.$idretiro;
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

			$escpos .= escposAlign("center");
			$escpos .= escposLinea("RETIRO DE EFECTIVO");
			$escpos .= escposAlign("left");
			$escpos .= escposLinea(str_repeat("=", $anchoTicket));

			$escpos .= escposFila(array(array("RETIRO DE EFECTIVO:", 20, "left"), array("$".number_format($monto,2), 12, "right")));
			$escpos .= escposLinea("DESCRIPCION:");
			$lineas = dividirTexto($descripcion,$anchoTicket);
			foreach($lineas as $linea){
				$escpos .= escposLinea($linea);
			}
			$escpos .= escposLinea("FECHA Y HORA:");
			$escpos .= escposLinea($fecha." A LAS ".$hora);

			$escpos .= escposLinea(str_repeat("=", $anchoTicket));
			$escpos .= escposAlign("center");
			$escpos .= escposLinea("FIRMAS");
			$escpos .= escposAlign("left");
			$escpos .= escposLinea(str_repeat("=", $anchoTicket));
			$escpos .= escposAlign("center");
			$escpos .= escposLinea("RETIRO DE EFECTIVO");
			$escpos .= escposAbrirCajon();

			?>
			<script>
			parent.imprimirTicket(<? echo json_encode($infoticket["nombreimpresora"]);?>, '<? echo base64_encode($escpos);?>').then(function(){
				parent.$.fancybox.close();
			});
			</script>
		<?
	}
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../assets/js/jquery.js"></script>
<script>

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(caode==13){
		evento.preventDefault();
	}
	if(code==13){
		enviarForm();
	}
}

$(document).ready(function(){
	document.getElementById('txtMonto').focus();

  $('form').keypress(function(e){
    if(e == 13){
      enviarForm();
    }
  });

  $('input').keypress(function(e){
    if(e.which == 13){
      return false;
    }
  });

});

function enviarForm(){
	var monto = document.getElementById('txtMonto').value;
	var descripcion = document.getElementById('txtDescripcion').value;
	if(monto=="" || monto==0 || descripcion==""){
		alert("ATENCION: Tiene que ingresar MONTO y DESCRIPCION.");
		return(false);
	}
	document.getElementById('formRetiroEfectivo').submit();
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

<body>
<form id="formRetiroEfectivo" name="formRetiroEfectivo" method="post" action="">
<input type="hidden" name="enviar" value="1" />
<div style="position:relative;" id="div">
  <div style="position:absolute; width:300px; height:360px; top:0px; left:0px; z-index:1;"><img src="../assets/images/pantallaRetiroEfectivo.png" width="300" height="360" usemap="#Map" border="0" />
    <map name="Map" id="Map">
      <area shape="rect" coords="14,300,286,342" href="javascript:;" onclick="enviarForm();"  />
    </map>
  </div>
  <div style="position: absolute; width: 125px; height: 23px; top: 76px; left: 155px; z-index: 2;">
    <input type="text" name="txtMonto" id="txtMonto" class="campo" style="width: 130px; height: 23px;" />
  </div>
   <div style="position: absolute; width: 259px; height: 130px; top: 147px; left: 21px; z-index: 2;">
     <textarea name="txtDescripcion" class="campo" id="txtDescripcion" style="width:259px; height:130px;"></textarea>
   </div>
</div>
</form>
</body>
</html>
