<?php
/**
 * Pagina de descarga de QZ Tray para las sucursales.
 *
 * IMPORTANTE: no se debe mandar a la gente a descargar QZ Tray desde qz.io. El instalador
 * oficial NO lleva el certificado de La Jugueria, asi que con el volveria a salir el dialogo
 * de permiso en cada impresion. Aqui se ofrecen los instaladores propios, compilados con el
 * certificado incluido (ver deploy/qz-tray/README.md).
 *
 * Detecta el sistema operativo para ofrecer el instalador correcto, pero deja los demas a la
 * vista por si la deteccion falla o alguien descarga desde otra computadora.
 */
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");

// Al agregar una version nueva basta con subir el archivo a descargas/ y actualizar el nombre
// aqui. Los que no existan en el servidor no se muestran como disponibles.
$instaladores = array(
	"windows" => array(
		"nombre"  => "Windows",
		"archivo" => "qz-tray-2.2.6-x86_64.exe",
		"nota"    => "Para las computadoras de las sucursales."
	),
	"mac" => array(
		"nombre"  => "macOS",
		"archivo" => "qz-tray-2.2.6-arm64.pkg",
		"nota"    => "Para Mac con chip Apple (M1 o superior)."
	),
	"mac-intel" => array(
		"nombre"  => "macOS (Intel)",
		"archivo" => "qz-tray-2.2.6-x86_64.pkg",
		"nota"    => "Para Mac con procesador Intel."
	)
);

foreach($instaladores as $clave => $datos){
	$ruta = $_SERVER["DOCUMENT_ROOT"] . "/descargas/" . $datos["archivo"];
	$instaladores[$clave]["existe"] = file_exists($ruta);
	$instaladores[$clave]["peso"] = $instaladores[$clave]["existe"] ? round(filesize($ruta) / 1048576) . " MB" : "";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instalar QZ Tray - La Jugueria</title>
<script src="assets/js/jquery.js"></script>
<style>
body{ font-family:Arial, Helvetica, sans-serif; background:#679A35; margin:0; padding:30px; color:#222; }
.caja{ background:#fff; max-width:620px; margin:0 auto; padding:26px 30px; border-radius:6px; }
h1{ font-size:21px; margin:0 0 6px; } p.sub{ color:#666; margin:0 0 22px; font-size:14px; }
.op{ border:1px solid #ddd; border-radius:5px; padding:14px 16px; margin-bottom:10px; }
.op.principal{ border-color:#679A35; border-width:2px; background:#f6faf2; }
.op b{ font-size:16px; } .op span{ color:#666; font-size:13px; display:block; margin-top:2px; }
.op a{ display:inline-block; margin-top:10px; background:#679A35; color:#fff; text-decoration:none;
	padding:9px 20px; border-radius:4px; font-weight:bold; }
.op.no a{ background:#bbb; pointer-events:none; }
.etiqueta{ float:right; background:#679A35; color:#fff; font-size:11px; padding:3px 9px; border-radius:10px; }
.aviso{ background:#fff8e1; border-left:4px solid #e6b422; padding:12px 15px; margin-top:22px; font-size:13px; }
.pasos{ font-size:13px; color:#444; margin-top:20px; } .pasos li{ margin-bottom:6px; }
</style>
</head>
<body>
<div class="caja">
	<h1>Instalar QZ Tray</h1>
	<p class="sub">QZ Tray es el programa que conecta el sistema con la impresora de tickets. Se instala una sola vez en cada computadora.</p>

<? foreach($instaladores as $clave => $d){ ?>
	<div class="op <? echo $d["existe"] ? "" : "no";?>" id="op-<? echo $clave;?>">
		<b><? echo $d["nombre"];?></b>
		<span><? echo $d["nota"];?><? echo $d["existe"] ? " (".$d["peso"].")" : " - todavia no disponible";?></span>
		<? if($d["existe"]){ ?>
			<a href="descargas/<? echo rawurlencode($d["archivo"]);?>">Descargar</a>
		<? }else{ ?>
			<a href="#">No disponible</a>
		<? } ?>
	</div>
<? } ?>

	<div class="aviso" id="aviso-windows">
		<b>Al instalar, Windows mostrara una advertencia</b> ("Windows protegio su PC"). Es normal:
		este instalador es el de La Jugueria y no el generico. Presiona <b>Mas informacion</b> y luego
		<b>Ejecutar de todas formas</b>.
	</div>
	<div class="aviso" id="aviso-mac" style="display:none;">
		<b>macOS va a bloquear el instalador la primera vez</b> ("no se pudo abrir porque procede de
		un desarrollador no identificado"). Es normal: este instalador es el de La Jugueria y no el
		generico. Haz <b>clic derecho sobre el archivo</b> y elige <b>Abrir</b>; si aun asi no deja,
		autorizalo en <b>Ajustes del Sistema &gt; Privacidad y seguridad</b>.
	</div>

	<ol class="pasos">
		<li>Descarga el instalador y ejecutalo.</li>
		<li>Si ya tenias QZ Tray instalado, cierralo antes (icono junto al reloj, clic derecho, Exit).</li>
		<li>Al terminar, QZ Tray se abre solo y queda funcionando junto al reloj.</li>
		<li>Vuelve al sistema e imprime. No deberia pedirte ningun permiso.</li>
	</ol>
</div>

<script>
// Resalta el instalador que corresponde a esta computadora.
var ua = navigator.userAgent;
var cual = 'windows';
if(ua.indexOf('Mac') !== -1){
	// Las Mac con chip Apple no se distinguen por userAgent; se usa el numero de nucleos como
	// aproximacion (las Intel de la epoca reportan 4 o menos). Ante la duda se marca Apple y
	// la otra opcion queda visible abajo.
	cual = (navigator.hardwareConcurrency > 4) ? 'mac' : 'mac-intel';
}
$('#op-' + cual).addClass('principal').find('b').after('<span class="etiqueta">Tu computadora</span>');

// Cada sistema tiene su propia advertencia al instalar; se muestra solo la que aplica.
if(cual !== 'windows'){
	$('#aviso-windows').hide();
	$('#aviso-mac').show();
}
</script>
</body>
</html>
