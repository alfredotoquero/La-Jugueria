<?php
/**
 * Pagina de diagnostico de la firma de QZ Tray.
 *
 * Existe porque los fallos de firma son silenciosos: si el certificado no se puede
 * obtener, QZ Tray sigue conectando SIN firma y solo deja un aviso en la consola, asi
 * que el sintoma es "todo sigue igual que antes" sin ningun error visible.
 *
 * Vive en la raiz, igual que admin.php, para que las rutas relativas a los endpoints
 * sean exactamente las mismas que usa el sistema en produccion.
 *
 * Se puede borrar una vez que la impresion quede funcionando.
 */
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");

// --- Pruebas del lado del servidor -----------------------------------------
$pruebas = array();

// Comprobacion facil de pasar por alto: que el admin.php que corre en ESTE servidor sea el
// que ya trae la firma. Si se subieron las llaves pero no el codigo actualizado, todo lo
// demas da OK y la impresion sigue pidiendo permiso en cada ticket.
$rutaAdmin = $_SERVER["DOCUMENT_ROOT"] . "/admin.php";
$admin = file_exists($rutaAdmin) ? file_get_contents($rutaAdmin) : "";
if(strpos($admin, "setSignaturePromise") !== false){
	$pruebas[] = array(true, "admin.php actualizado", "Contiene la configuracion de firma.");
}else{
	$pruebas[] = array(false, "admin.php actualizado", "El admin.php de este servidor NO tiene la firma: es la version anterior. Falta desplegar el codigo.");
}

$rutaLlaves = $_SERVER["DOCUMENT_ROOT"] . "/config/qz-llaves.php";
if(!file_exists($rutaLlaves)){
	$pruebas[] = array(false, "Archivo de llaves", "NO EXISTE en " . $rutaLlaves . " - hay que subirlo al servidor (no viaja con git).");
}else{
	$pruebas[] = array(true, "Archivo de llaves", "Encontrado.");

	define("QZ_ACCESO_INTERNO", true);
	include($rutaLlaves);

	$llave = isset($QZ_LLAVE_PRIVADA) ? openssl_pkey_get_private($QZ_LLAVE_PRIVADA) : false;
	if($llave === false){
		$pruebas[] = array(false, "Llave privada", "No se pudo leer. Puede estar incompleta o corrupta al subirla.");
	}else{
		$pruebas[] = array(true, "Llave privada", "Valida.");

		$firma = "";
		if(openssl_sign("prueba", $firma, $llave, "sha512")){
			$pruebas[] = array(true, "Firma SHA512", "Se genero correctamente (" . strlen(base64_encode($firma)) . " caracteres).");

			$pub = isset($QZ_CERTIFICADO) ? openssl_pkey_get_public($QZ_CERTIFICADO) : false;
			$valida = ($pub !== false) && (openssl_verify("prueba", $firma, $pub, "sha512") === 1);
			$pruebas[] = $valida
				? array(true, "Par llave/certificado", "La firma verifica contra el certificado: son pareja.")
				: array(false, "Par llave/certificado", "La firma NO verifica: la llave y el certificado no corresponden entre si.");
		}else{
			$pruebas[] = array(false, "Firma SHA512", "openssl_sign fallo. Revisa que la extension openssl este activa.");
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Diagnostico QZ Tray</title>
<script src="assets/js/jquery.js"></script>
<script src="assets/js/qz-tray.js"></script>
<style>
body{ font-family:Arial, Helvetica, sans-serif; margin:24px; background:#f4f4f4; color:#222; }
h1{ font-size:20px; } h2{ font-size:15px; margin-top:24px; }
.p{ background:#fff; border-left:5px solid #999; padding:10px 14px; margin:6px 0; }
.ok{ border-color:#3c9a3c; } .mal{ border-color:#c33; }
.p b{ display:block; } .p span{ color:#555; font-size:13px; }
#resumen{ padding:14px; margin-top:20px; font-weight:bold; background:#fff; border:2px solid #999; }
</style>
</head>
<body>
<h1>Diagnostico de firma de QZ Tray</h1>

<h2>1. Servidor (llaves y firma)</h2>
<? foreach($pruebas as $p){ ?>
	<div class="p <? echo $p[0] ? "ok" : "mal";?>">
		<b><? echo ($p[0] ? "OK - " : "FALLA - ").$p[1];?></b>
		<span><? echo htmlspecialchars($p[2]);?></span>
	</div>
<? } ?>

<h2>2. Endpoints (tal como los pide el navegador)</h2>
<div id="endpoints"><div class="p"><b>Probando...</b></div></div>

<h2>3. QZ Tray (aplicacion instalada)</h2>
<div id="qz"><div class="p"><b>Conectando...</b></div></div>

<div id="resumen">Ejecutando pruebas...</div>

<script>
function pinta(destino, ok, titulo, detalle){
	$(destino).append('<div class="p ' + (ok ? 'ok' : 'mal') + '"><b>' + (ok ? 'OK - ' : 'FALLA - ') + titulo + '</b><span>' + detalle + '</span></div>');
}

var problemas = [];

// --- 2. Endpoints ---
$('#endpoints').empty();
var pruebaCert = $.ajax({ url: 'assets/php/otros/qz-certificado.php', cache: false, dataType: 'text' })
	.done(function(txt){
		if(txt.indexOf('BEGIN CERTIFICATE') !== -1){
			pinta('#endpoints', true, 'Certificado', 'El endpoint devuelve un certificado valido.');
		}else{
			problemas.push('El endpoint del certificado no devuelve un certificado. Si devuelve HTML, es que la sesion expiro o la ruta no resuelve.');
			pinta('#endpoints', false, 'Certificado', 'Devolvio algo que NO es un certificado. Primeros 120 caracteres: <br><code>' + $('<div>').text(txt.substring(0,120)).html() + '</code>');
		}
	})
	.fail(function(xhr){
		problemas.push('El endpoint del certificado responde HTTP ' + xhr.status + '.');
		pinta('#endpoints', false, 'Certificado', 'No se pudo pedir: HTTP ' + xhr.status + '.');
	});

var pruebaFirma = $.ajax({ url: 'assets/php/otros/qz-firma.php', data: { request: 'prueba' }, cache: false, dataType: 'text' })
	.done(function(txt){
		if(/^[A-Za-z0-9+\/=\s]+$/.test(txt) && txt.length > 100){
			pinta('#endpoints', true, 'Firma', 'El endpoint devuelve una firma (' + txt.trim().length + ' caracteres).');
		}else{
			problemas.push('El endpoint de firma no devuelve una firma valida.');
			pinta('#endpoints', false, 'Firma', 'Devolvio algo que no parece una firma. Primeros 120 caracteres: <br><code>' + $('<div>').text(txt.substring(0,120)).html() + '</code>');
		}
	})
	.fail(function(xhr){
		problemas.push('El endpoint de firma responde HTTP ' + xhr.status + '.');
		pinta('#endpoints', false, 'Firma', 'No se pudo pedir: HTTP ' + xhr.status + '.');
	});

// --- 3. QZ Tray ---
qz.security.setCertificatePromise(function(resolve, reject){
	$.ajax({ url: 'assets/php/otros/qz-certificado.php', cache: false, dataType: 'text' }).done(resolve).fail(reject);
}, { rejectOnFailure: true });
qz.security.setSignatureAlgorithm('SHA512');
qz.security.setSignaturePromise(function(porFirmar){
	return function(resolve, reject){
		$.ajax({ url: 'assets/php/otros/qz-firma.php', data: { request: porFirmar }, cache: false, dataType: 'text' }).done(resolve).fail(reject);
	};
});

$.when(pruebaCert, pruebaFirma).always(function(){
	$('#qz').empty();
	var conexion = qz.websocket.isActive() ? Promise.resolve() : qz.websocket.connect();

	conexion.then(function(){
		pinta('#qz', true, 'Conexion', 'Conectado a QZ Tray con la firma configurada.');
		return qz.api.getVersion();
	}).then(function(version){
		// SHA512 requiere QZ Tray 2.1 o superior; en 2.0.x la firma se rechaza en silencio.
		var mayor = parseInt(version.split('.')[0], 10);
		var menor = parseInt(version.split('.')[1], 10);
		var sirve = (mayor > 2) || (mayor === 2 && menor >= 1);
		if(!sirve){
			problemas.push('La version instalada de QZ Tray (' + version + ') es anterior a la 2.1 y no soporta firmas SHA512. Hay que actualizarla.');
		}
		pinta('#qz', sirve, 'Version instalada', 'QZ Tray ' + version + (sirve ? ' (soporta SHA512).' : ' - DEMASIADO ANTIGUA, se necesita 2.1 o superior.'));
		terminar();
	}).catch(function(error){
		problemas.push('No se pudo conectar a QZ Tray con firma: ' + error);
		pinta('#qz', false, 'Conexion', 'Fallo: ' + error + '<br>Si QZ Tray esta abierto, esto suele significar que el certificado no se pudo entregar.');
		terminar();
	});
});

function terminar(){
	var servidorMal = <? echo count(array_filter($pruebas, function($p){ return !$p[0]; })) > 0 ? "true" : "false";?>;
	if(problemas.length === 0 && !servidorMal){
		$('#resumen').css({background:'#e7f5e7', borderColor:'#3c9a3c'})
			.html('Todo correcto. La firma esta funcionando: al imprimir, el dialogo debe permitir marcar "Remember this decision".');
	}else{
		var lista = problemas.length ? '<ul><li>' + problemas.join('</li><li>') + '</li></ul>' : '<p>Revisa las fallas marcadas arriba.</p>';
		$('#resumen').css({background:'#fdeaea', borderColor:'#c33'})
			.html('Se encontraron problemas:' + lista);
	}
}
</script>
</body>
</html>
