<?
	ini_set("session.gc_maxlifetime","43200");
	session_name("2q093ex8uq2ewun");
	session_start();
	date_default_timezone_set('America/Los_Angeles');
	include("002wf3f3kgdvr/983y4rhouCon.php");
	include("002wf3f3kgdvr/983y4rhou.php");
	$corte = mysql_fetch_assoc(mysql_query("select * from tcortes order by idcorte desc limit 1"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Deposito &quot;Las Lomitas&quot;</title>

<link href="css/style.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="js/jquery.js"></script>

<link href="css/blitzer/jquery-ui-1.9.2.custom.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.9.2.custom.js"></script>

<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script language="javascript" type="text/javascript" src="js/autocompletar/jquery-ui-autocomplete.js"></script>

<script>
$(document).ready(function(){
	//$(document).keydown(manejarEventos);
	
	var productos = [
		<?
		$productos = mysql_query("select * from tproductos order by nombre");
		while($producto = mysql_fetch_assoc($productos)){
		?>
		{
			value: "<? echo $producto["nombre"];?>",
			id: "<? echo $producto["idproducto"]."-".$producto["precio"];?>"
		},
		<?
		}
		?>
	];
	$("#txtBusqueda").autocomplete({
		source: productos,
		select: function(event,ui){
			var producto = ui.item.id;
			producto = producto.split("-");
			$("#txtBusqueda").val("");
			fancy(306,130,'modulos/cantidad.php?idproducto=' + producto[0] + '&precio=' + platillo[1]);
		}
	});
	$('#txtBusqueda').focus();
});
</script>

</head>
<?
switch(date('N')){
	case 1: $diaSemana = "LUNES"; break;
	case 2: $diaSemana = "MARTES"; break;
	case 3: $diaSemana = "MIERCOLES"; break;
	case 4: $diaSemana = "JUEVES"; break;
	case 5: $diaSemana = "VIERNES"; break;
	case 6: $diaSemana = "SABADO"; break;
	case 7: $diaSemana = "DOMINGO"; break;
}
switch(date('n')){
	case 1: $mes = "ENERO"; break;
	case 2: $mes = "FEBRERO"; break;
	case 3: $mes = "MARZO"; break;
	case 4: $mes = "ABRIL"; break;
	case 5: $mes = "MAYO"; break;
	case 6: $mes = "JUNIO"; break;
	case 7: $mes = "JULIO"; break;
	case 8: $mes = "AGOSTO"; break;
	case 9: $mes = "SEPTIEMBRE"; break;
	case 10: $mes = "OCTUBRE"; break;
	case 11: $mes = "NOVIEMBRE"; break;
	case 12: $mes = "DICIEMBRE"; break;
}
?>
<body bgcolor="#363435">
<table width="1000" height="720" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
    	<td align="center">
        	<div style="position:relative; width:1000px; height:720px;">
            	<div style="position:absolute; top:0px; left:0px; width:1000px; height:720px;"><img src="images/caja.jpg" width="1000" height="720" border="0" usemap="#mapaCaja" /></div>
                <div style="position:absolute; top:187px; left:31px; width:712px; height:30px;">
                <input name="txtBusqueda" id="txtBusqueda" style="border:0px; outline:0px; background:none; width:100%; height:100%;">
                </div>
            	<div id="divCuenta" style="position:absolute; top:233px; left:31px; width:712px; height:308px; overflow:auto;">
                
                </div>
            	<div id="divTotales" style="position:absolute; top:571px; left:607px; width:363px; height:75px; overflow:auto;">
                	<table width="363" border="0" cellpadding="0" cellspacing="0">
                    	<tr><td style="font-size:40px; font-weight:bold; color:#000;" height="55" align="right" valign="middle" id="tTotal">$0.00</td></tr>
                        <tr><td align="right" style="font-size:12px; font-weight:bold; color:#000;" height="20" id="tTotalDolares">(1 x 12.5) $0.00 DLLS.</td></tr>
                    </table>
                </div>
                <div style="position: absolute; top: 109px; left: 122px; width: 350px; height: 23px;">
                	<table width="424" border="0" cellpadding="0" cellspacing="0">
                    	<tr><td style="font-size:18px; color:#FFF;" align="left" valign="middle"><? echo strtoupper(mysql_result(mysql_query("select concat(nombre,' ',apaterno,' ',amaterno) from tusuarios where idusuario = '".$corte["idusuario"]."'"),0));?></td></tr>
                    </table>
                </div>
                <div style="position: absolute; top: 133px; left: 122px; width: 350px; height: 23px;">
                	<table width="424" border="0" cellpadding="0" cellspacing="0">
                    	<tr><td style="font-size:18px; color:#FFF;" align="left" valign="middle" id="txtNombreCliente"></td></tr>
                  </table>
              </div>
                <div style="position: absolute; top: 157px; left: 122px; width: 350px; height: 23px;">
                	<table width="424" border="0" cellpadding="0" cellspacing="0">
                    	<tr><td style="font-size:18px; color:#FFF;" align="left" valign="middle" id="txtNombreCuenta"></td></tr>
                    </table>
                </div>
              <div style="position: absolute; top: 109px; left: 480px; width: 490px; height: 23px;">
                	<table width="490" border="0" cellpadding="0" cellspacing="0">
                    	<tr><td style="font-size:17px; color:#FFF;" align="left" valign="middle"><? echo $diaSemana." ".date('d')." DE ".$mes." DEL ".date('Y')." - ";?><span id="divHora"></span></td></tr>
                    </table>
                </div>
                <div style="position: absolute; top: 133px; left: 480px; width: 490px; height: 23px;">
                	<table width="490" border="0" cellpadding="0" cellspacing="0">
                    	<tr><td style="font-size:17px; color:#FFF;" align="left" valign="middle" id="txtNombreCliente"><div id="divMensajes"></div></td></tr>
                  </table>
              </div>
              <div id="divImagenMenu" style="position:absolute; top:246px; left:765px; width:212px; height:132px;">
              	<img src="" id="imgMenu" width="212" height="132" />
              </div>
              <div id="divDescripcionMenu" style="position:absolute; top:407px; left:765px; width:212px; height:91px;"></div>
            </div>
        </td>
    </tr>
</table>
<map name="MapaCaja" id="MapaCaja">
  <area shape="rect" coords="25,16,205,97" href="javascript:;" onclick="fancy(600,410,'modulos/clientes.php');" />
  <area shape="rect" coords="218,17,398,98" href="javascript:;" onclick="fancy(800,500,'modulos/cuentas.php');" />
  <area shape="rect" coords="410,17,590,98" href="javascript:;" onclick="fancy(800,500,'modulos/pedidos.php');" />
  <area shape="rect" coords="602,17,782,98" href="javascript:;" onclick="fancy(450,170,'modulos/salidas.php');" />
  <area shape="rect" coords="796,17,976,98" href="javascript:;" onclick="fancy('90%','90%','http://www.hijosdelsushi.interface.mx/reportes/index.php?entrarSistema=1&idusuario=<? echo $corte["idcajero"]; ?>&idsucursal=<? echo $_SESSION["idsucursal"]; ?>');" />
  <area shape="rect" coords="766,188,976,229" href="#" />
  <area shape="rect" coords="766,506,976,547" href="#" />
  <area shape="rect" coords="350,566,581,647" href="#" />
  <area shape="rect" coords="351,659,582,700" href="#" />
  <area shape="rect" coords="805,660,977,700" href="#" />
</map>
</body>
</html>
