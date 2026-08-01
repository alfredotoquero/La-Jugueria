<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
$corte = mysqli_fetch_assoc(mysqli_query($con, "select * from tcortes where idsucursal = '" . $_SESSION["idsucx9284hqmzt7"] . "' order by idcorte desc limit 1"));
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
		$productos = mysqli_query($con, "select * from tproductos order by nombre");
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
