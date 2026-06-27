<?
	ini_set("session.gc_maxlifetime","43200");
	session_name("2q093ex8uq2ewun");
	session_start();
	date_default_timezone_set('America/Los_Angeles');
	include("../002wf3f3kgdvr/983y4rhouCon.php");
	include("../002wf3f3kgdvr/983y4rhou.php");
	include("num2letras.php");

	$idcorte = mysql_result(mysql_query("select max(idcorte) from tcortes where status = 0"),0);
	$fondo = mysql_result(mysql_query("select fondoinicial from tcortes where idcorte = $idcorte"),0);
	$ventas = mysql_result(mysql_query("select sum(total) from tcuentas where idcorte = $idcorte"),0);
	$retiros = mysql_result(mysql_query("select sum(monto) from tretiros where idcorte = $idcorte"),0);
	$fondoFinal = ((float)$fondo+(float)$ventas)-(float)$retiros;

	if($_GET['idcorte']){
		$idcorte = $_GET['idcorte'];
		$fondo = mysql_result(mysql_query("select fondoinicial from tcortes where idcorte = $idcorte"),0);
		$ventas = mysql_result(mysql_query("select sum(total) from tcuentas where idcorte = $idcorte"),0);
		$retiros = mysql_result(mysql_query("select sum(monto) from tretiros where idcorte = $idcorte"),0);
		$fondoFinal = ((float)$fondo+(float)$ventas)-(float)$retiros;
		$folioinicial = mysql_result(mysql_query("select min(idcuenta) from tcuentas where idcorte = $idcorte"),0);
		$foliofinal = mysql_result(mysql_query("select max(idcuenta) from tcuentas where idcorte = $idcorte"),0);

		mysql_query("update tcortes set
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
		$corte = mysql_fetch_assoc(mysql_query("select * from tcortes where idcorte = $idcorte"));
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

		$printer = "BIXOLON SRP-330II";
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
		for($i=strlen($idcorte);$i<7;$i++){
			$idticket .= "0";
		}
		$idticket = $idticket.$idcorte;
		$ticket .= date("d/m/Y",strtotime($corte["fechafinal"]))." ".date("H:i:s A",strtotime($corte["horafinal"]))." ".$idticket;


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

		printer_draw_text($enlace,str_pad("CORTE DE CAJA".$articulos,((48-strlen("CORTE DE CAJA".$articulos))*2)+(countCaracteres("CORTE DE CAJA".$articulos)*2)+strlen("CORTE DE CAJA".$articulos), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[2];

		printer_draw_text($enlace,"==============================================",5,$coordActual);
		$coordActual += $saltoLinea[2];

		printer_draw_text($enlace,"FONDO FINAL",5,$coordActual);
		printer_draw_text($enlace,'$'.number_format($corte['fondofinal'],2),250,$coordActual);
		$coordActual += $saltoLinea[2];
		printer_draw_text($enlace,"DESGLOSE:",5,$coordActual);
		$coordActual += $saltoLinea[2];
		printer_draw_text($enlace,"FONDO INICIAL (MXN)",5,$coordActual);
		printer_draw_text($enlace,'$'.number_format($corte['fondoinicial'],2),250,$coordActual);
		$coordActual += $saltoLinea[2];
		if($corte['ventas']>0){
			printer_draw_text($enlace,"EFECTIVO (MXN)",5,$coordActual);
			printer_draw_text($enlace,'$'.number_format($corte['ventas'],2),250,$coordActual);
			$coordActual += $saltoLinea[2];
		}
		printer_draw_text($enlace,"TOTAL DE GASTOS",5,$coordActual);
		printer_draw_text($enlace,'$'.number_format($corte['gastos'],2),250,$coordActual);
		$coordActual += $saltoLinea[2];
		printer_draw_text($enlace,"FOLIO INICIAL DEL CORTE",5,$coordActual);
		printer_draw_text($enlace,$corte['folioinicial'],250,$coordActual);
		$coordActual += $saltoLinea[2];
		printer_draw_text($enlace,"FOLIO FINAL DEL CORTE",5,$coordActual);
		printer_draw_text($enlace,$corte['foliofinal'],250,$coordActual);
		$coordActual += $saltoLinea[4];

		$numLinea=1;
		$descripcion = strtoupper(num2letras(number_format($corte['fondofinal'],2,'.','')));
		$lineas = dividirTexto($descripcion,$caracteresCol[4]);

		foreach($lineas as $linea){
			printer_draw_text($enlace,$linea,$coordCol[0],$coordActual);
			$numLinea++;
			$coordActual += $saltoLinea[2];
		}

		printer_draw_text($enlace,"==============================================",5,$coordActual);
		$coordActual += $saltoLinea[2];

		printer_draw_text($enlace,str_pad("FIRMAS",((48-strlen("FIRMAS"))*2)+(countCaracteres("FIRMAS")*2)+strlen("FIRMAS"), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[3];

		printer_draw_text($enlace,"==============================================",5,$coordActual);
		$coordActual += $saltoLinea[2];

		printer_draw_text($enlace,str_pad("CORTE DE CAJA".$articulos,((48-strlen("CORTE DE CAJA".$articulos))*2)+(countCaracteres("CORTE DE CAJA".$articulos)*2)+strlen("CORTE DE CAJA".$articulos), " ", STR_PAD_BOTH),5,$coordActual);
		$coordActual += $saltoLinea[4];

		printer_draw_text($enlace," ",5,$coordActual);

		printer_write($enlace,chr(27).chr(112).chr(0).chr(100).chr(250));

		printer_delete_font($fontL);
		printer_delete_font($fontM);
		printer_delete_font($fontS);
		printer_end_page($enlace);
		printer_end_doc($enlace);
		printer_close($enlace);

		?>
		<script>
		parent.location.href="../salir.php";
		parent.$.fancybox.close();
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
