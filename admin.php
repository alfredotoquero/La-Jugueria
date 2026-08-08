<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
$idsucursal = $_SESSION["idsucx9284hqmzt7"];
$corte = mysqli_fetch_assoc(mysqli_query($con, "select * from tcortes where idsucursal = '" . $idsucursal . "' order by idcorte desc limit 1"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Jugos Sonora</title>

<link href="assets/css/style.css" rel="stylesheet" type="text/css" />

<script language="javascript" type="text/javascript" src="assets/js/jquery.js"></script>

<script type="text/javascript" src="assets/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="assets/js/fancybox/jquery.fancybox-1.3.4.js"></script>
<link rel="stylesheet" type="text/css" href="assets/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<link href="assets/css/blitzer/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/js/qz-tray.js"></script>

<script>
var total = 0;
var productos = 0;

$(document).ready(function(){
	$(document).keydown(manejarEventos);
	
	$(document).click(function(){
		$("#txtBusqueda").focus();
	});
	
	var productos = [
		<?
		$productos = mysqli_query($con, "select tp.idproducto, tp.nombre, coalesce(tps.precio, tp.precio) as precio
				from tproductos tp
				left join tproductosucursales tps on tps.idproducto = tp.idproducto and tps.idsucursal = '" . $idsucursal . "'
				where tp.status = 1
				and (tps.idproductosucursal is null or tps.status = 1)
				order by tp.nombre");
		while($producto = mysqli_fetch_assoc($productos)){
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
			$.ajax({
				type:"POST",
				url:"modulos/agregarProducto.php",
				data:"idproducto=" + producto[0] + "&precio=" + producto[1],
				success: function(data){
					recargarCuenta();
				}
			});
		}
	});
	$('#txtBusqueda').focus();
});

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==27 || (code>=112 && code<=123)){
		evento.preventDefault();
	}
	if(code==115){
		fancy(300,360,'modulos/retiros.php');
	}
	if(code==120){
		cobrar();
	}
	if(code==121){
		fancy(300,250,'modulos/corte.php');
	}
	if(code==123){
		if(total>0){
			if(confirm('Deseas limpiar la pantalla?')){
				eliminarTodo();
			}
		}
	}
}

function cobrar(){
	if(total>0){
		fancy(300,238,'modulos/cobrar.php');
	}else{
		alert("La cuenta esta vacia.");
	}
}

function recargarCuenta(){
	$("#divCuenta").load("modulos/cuenta.php");
	$('#txtBusqueda').val("");
	$('#txtBusqueda').focus();
}

function mostrarTotal(total,productos){
	this.total = total;
	this.productos = productos;
	$("#tTotal").html(total);
}

function agregarProducto(idproducto){
	$.ajax({  
		type: "POST",  
		url: "modulos/menu/agregarProducto.php",
		data: "idproducto=" + idproducto,
		success: function(data){
			recargarCuenta();
		}  
	});
}

function eliminarProducto(idproducto){
	$.ajax({  
		type: "POST",  
		url: "modulos/menu/eliminarProducto.php",
		data: "idproducto=" + idproducto,
		success: function(data){
			recargarCuenta();
		}  
	});
}

function eliminarTodo(){
	total = 0;
	$("#tTotal").html("$ 0.00");
	$.ajax({  
		type: "POST",  
		url: "modulos/limpiarPantalla.php",
		success: function(data){
			recargarCuenta(0);
		}  
	});
}

function fancy(ancho,alto,url){
	$.fancybox({
		'type'				:	'iframe',
		'speedIn'			:	600, 
		'speedOut'			:	400,
		'overlayShow'		: 	true,
		'overlayOpacity'	:	0.3,
		'overlayColor'		:	'#000',
		'hideOnOverlayClick':	false,
		'centerOnScroll'	:	true,
		'autoDimensions'	: 	false,
		'width'         	: 	ancho,
		'height'        	: 	alto,
		'href'				:	url,
		'enableEscapeButton':	true,
		'onClosed'			:	function(){recargarCuenta();}
	});
}

var QZ_TRAY_DOWNLOAD_URL = 'https://qz.io/download/';

/**
 * Firma de las peticiones a QZ Tray.
 *
 * Sin esto, QZ Tray deja deshabilitada la casilla "Remember this decision" y pide
 * autorizacion en CADA impresion. Firmando, el cajero autoriza UNA sola vez por
 * computadora y QZ Tray lo recuerda (lo guarda en %APPDATA%\qz\allowed.dat).
 *
 * Se registra al cargar la pantalla, antes de cualquier conexion: QZ Tray exige que
 * estos manejadores existan antes de qz.websocket.connect().
 */
// rejectOnFailure es indispensable: por defecto, si no se logra traer el certificado,
// QZ Tray continua con un certificado en blanco -es decir, sin firma- y lo unico que deja
// es un aviso en la consola. El sintoma seria "todo sigue igual que antes" sin ningun
// error visible, que es justo lo dificil de diagnosticar.
qz.security.setCertificatePromise(function(resolve, reject){
	$.ajax({ url: 'assets/php/otros/qz-certificado.php', cache: false, dataType: 'text' })
		.done(function(certificado){
			// Si la sesion expiro, validarAcceso.php redirige al login y aqui llegaria el
			// HTML de esa pantalla en vez del certificado. Se detecta para no dejar que
			// falle mas adelante con un error incomprensible.
			if(certificado.indexOf('BEGIN CERTIFICATE') === -1){
				console.error('QZ Tray: la respuesta del certificado no es un certificado. Revisa qz-diagnostico.php');
				reject('No se obtuvo el certificado de firma (la sesion pudo haber expirado).');
				return;
			}
			resolve(certificado);
		})
		.fail(function(xhr){
			console.error('QZ Tray: fallo al pedir el certificado (HTTP ' + xhr.status + '). Revisa qz-diagnostico.php');
			reject(xhr);
		});
}, { rejectOnFailure: true });

// SHA512 es lo que espera QZ Tray desde la version 2.1; aqui se usa la 2.2.6.
qz.security.setSignatureAlgorithm('SHA512');

qz.security.setSignaturePromise(function(porFirmar){
	return function(resolve, reject){
		$.ajax({ url: 'assets/php/otros/qz-firma.php', data: { request: porFirmar }, cache: false, dataType: 'text' })
			.done(resolve)
			.fail(function(xhr){
				console.error('QZ Tray: fallo al firmar (HTTP ' + xhr.status + '). Revisa qz-diagnostico.php');
				reject(xhr);
			});
	};
});

function imprimirTicket(printerName, datosBase64){
	var yaAvisado = false;
	var conexion = qz.websocket.isActive() ? Promise.resolve() : qz.websocket.connect();

	return conexion
		.catch(function(error){
			yaAvisado = true;
			console.error('QZ Tray no disponible:', error);
			if(confirm('No se detecto QZ Tray en esta computadora (o no esta abierto).\n\nSi ya lo instalaste, abrelo e intenta imprimir de nuevo.\n\nSi no esta instalado, presiona Aceptar para descargarlo.')){
				window.open(QZ_TRAY_DOWNLOAD_URL, '_blank');
			}
			return Promise.reject(error);
		})
		.then(function(){
			return qz.printers.find(printerName);
		})
		.then(function(printer){
			var config = qz.configs.create(printer);
			var data = [{ type: 'raw', format: 'command', flavor: 'base64', data: datosBase64 }];
			return qz.print(config, data);
		})
		.catch(function(error){
			if(!yaAvisado){
				console.error('Error de impresion QZ Tray:', error);
				alert('No se pudo imprimir el ticket. Revisa el nombre de la impresora configurado para esta sucursal.');
			}
			// No se relanza el error: la venta/corte/retiro ya quedo guardado en la base de
			// datos antes de llegar aqui, asi que un fallo de impresion no debe bloquear que
			// se limpie el carrito o se avance de pantalla.
		});
}
</script>

</head>
<body bgcolor="#679A35" onload="recargarCuenta();">
<table width="1000" height="720" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
    	<td align="center">
        	<div style="position:relative; width:1000px; height:720px;">
            	<div style="position:absolute; top:0px; left:0px; width:1000px; height:720px;"><img src="assets/images/caja.png" width="1000" height="720" border="0" usemap="#mapaCaja" /></div>
                <div style="position:absolute; top:135px; left:30px; width:941px; height:30px;">
                <input name="txtBusqueda" id="txtBusqueda" style="border:0px; outline:0px; background:none; width:100%; height:100%;">
                </div>
           	  	<div id="divCuenta" style="position:absolute; top:189px; left:30px; width:941px; height:390px; overflow:auto;"></div>
            	<div id="divTotales" style="position:absolute; top:617px; left:657px; width:306px; height:70px; overflow:auto;">
                	<table width="306" border="0" cellpadding="0" cellspacing="0">
                    	<tr><td style="font-size:60px; color:#000;" height="70" align="right" valign="middle" id="tTotal">0.00</td></tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>
<map name="MapaCaja" id="MapaCaja">
  <area shape="rect" coords="381,611,581,694" href="javascript:;" onclick="cobrar();" />
  <area shape="rect" coords="26,611,356,646" href="javascript:;" onclick="fancy(300,360,'modulos/retiros.php');" />
  <area shape="rect" coords="26,658,356,693" href="javascript:;" onclick="fancy(300,400,'modulos/corte.php');" />
</map>
</body>
</html>
