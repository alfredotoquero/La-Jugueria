<?
ini_set("session.gc_maxlifetime","43200");
session_name("2q093ex8uq2ewun");
session_start();
date_default_timezone_set('America/Los_Angeles');
include("../002wf3f3kgdvr/983y4rhouCon.php");
include("../002wf3f3kgdvr/983y4rhou.php");

if($_POST['enviar']==1){
	$monto = $_POST['txtMonto'];
	$descripcion = strtoupper($_POST['txtDescripcion']);
	$idcorte = mysql_result(mysql_query("select MAX(idcorte) from tcortes where status = 0"),0);
	$fecha = date('Y-m-d');
	$hora = date('H:i:s');
	mysql_query("insert into tretiros values(NULL,'$idcorte','$monto','$descripcion','$fecha','$hora')");
	$idretiro = mysql_insert_id();
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
			printer_set_option($enlace,PRINTER_PAPER_WIDTH,58);

			printer_start_doc($enlace, "Ticket");
			printer_start_page($enlace);

			$fontL = printer_create_font("Arial", 24, 10, PRINTER_FW_BOLD, false, false, false, 0);
			$fontM = printer_create_font("Arial", 21, 8, PRINTER_FW_NORMAL, false, false, false, 0);
			$fontS = printer_create_font("Arial", 21, 8, PRINTER_FW_LIGHT, false, false, false, 0);

			$caracteresCol = array(5,12,7,7,30);
			$coordCol = array(5,60,220,300);
			$saltoLinea = array(24,22,22,196);
			$coordActual = 10;

			$idticket = "";
			for($i=strlen($idretiro);$i<7;$i++){
				$idticket .= "0";
			}
			$idticket = $idticket.$idretiro;
			$ticket .= date("d/m/Y")." ".date("H:i:s a")." ".$idticket;

			printer_select_font($enlace, $fontL);
			printer_draw_text($enlace,str_pad($infoticket["negocio"],((30-strlen($infoticket["negocio"]))*2)+(countCaracteres($infoticket["negocio"])*2)+strlen($infoticket["negocio"]), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[0];
			printer_select_font($enlace, $fontM);
			printer_draw_text($enlace,str_pad($infoticket["calle"]." No. ".$infoticket["numero"],((34-strlen($infoticket["calle"]." No. ".$infoticket["numero"]))*2)+(countCaracteres($infoticket["calle"]." No. ".$infoticket["numero"])*2)+strlen($infoticket["calle"]." No. ".$infoticket["numero"]), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[1];
			printer_draw_text($enlace,str_pad($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"],((34-strlen($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"]))*2)+(countCaracteres($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"])*2)+strlen($infoticket["colonia"]." C.P. ".$infoticket["codigopostal"]), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[1];
			printer_draw_text($enlace,str_pad($infoticket["ciudad"],((34-strlen($infoticket["ciudad"]))*2)+(countCaracteres($infoticket["ciudad"])*2)+strlen($infoticket["ciudad"]), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[1];
			printer_draw_text($enlace,str_pad($infoticket["nombre"],((32-strlen($infoticket["nombre"]))*2)+(countCaracteres($infoticket["nombre"])*2)+strlen($infoticket["nombre"]), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[1];
			printer_draw_text($enlace,str_pad($infoticket["rfc"],((34-strlen($infoticket["rfc"]))*2)+(countCaracteres($infoticket["rfc"])*2)+strlen($infoticket["rfc"]), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[1];
			printer_draw_text($enlace,str_pad($infoticket["regimen"],((34-strlen($infoticket["regimen"]))*2)+(countCaracteres($infoticket["regimen"])*2)+strlen($infoticket["regimen"]), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[1];
			printer_draw_text($enlace,str_pad($ticket,((38-strlen($ticket))*2)+(countCaracteres($ticket)*2)+strlen($ticket), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[1];
			printer_draw_text($enlace,"======================================",5,$coordActual);
			$coordActual += $saltoLinea[2];

			printer_draw_text($enlace,str_pad("RETIRO DE EFECTIVO",((36-strlen("RETIRO DE EFECTIVO"))*2)+(countCaracteres("RETIRO DE EFECTIVO")*2)+strlen("RETIRO DE EFECTIVO"), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[2];

			printer_draw_text($enlace,"======================================",5,$coordActual);
			$coordActual += $saltoLinea[2];

			printer_draw_text($enlace,"RETIRO DE EFECTIVO:",5,$coordActual);
			printer_draw_text($enlace,"$".number_format($retiro['monto'],2),215,$coordActual);
			$coordActual += $saltoLinea[2];
			printer_draw_text($enlace,"DESCRIPCION:",5,$coordActual);
			$coordActual += $saltoLinea[2];
			$numLinea=1;
			$descripcion = strtoupper($retiro['descripcion']);
			$lineas = dividirTexto($descripcion,$caracteresCol[4]);

			foreach($lineas as $linea){
		    printer_draw_text($enlace,$linea,$coordCol[0],$coordActual);
		    $numLinea++;
				$coordActual += $saltoLinea[2];
			}

			printer_draw_text($enlace,"FECHA Y HORA:",5,$coordActual);
			printer_draw_text($enlace,$retiro['fecha']." A LAS ".$retiro['hora'],160,$coordActual);
			$coordActual += $saltoLinea[2];

			printer_draw_text($enlace,"======================================",5,$coordActual);
			$coordActual += $saltoLinea[2];

			printer_draw_text($enlace,str_pad("FIRMAS",((36-strlen("FIRMAS"))*2)+(countCaracteres("FIRMAS")*2)+strlen("FIRMAS"), " ", STR_PAD_BOTH),5,$coordActual);
			$coordActual += $saltoLinea[3];

			printer_draw_text($enlace,"======================================",5,$coordActual);
			$coordActual += $saltoLinea[2];

			printer_draw_text($enlace,str_pad("RETIRO DE EFECTIVO",((36-strlen("RETIRO DE EFECTIVO"))*2)+(countCaracteres("RETIRO DE EFECTIVO")*2)+strlen("RETIRO DE EFECTIVO"), " ", STR_PAD_BOTH),5,$coordActual);
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
			parent.$.fancybox.close();
			</script>
		<?
	}
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../js/jquery.js"></script>
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
  <div style="position:absolute; width:300px; height:360px; top:0px; left:0px; z-index:1;"><img src="../images/pantallaRetiroEfectivo.png" width="300" height="360" usemap="#Map" border="0" />
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
