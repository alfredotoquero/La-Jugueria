<?php
/**
 * Firma digital de las peticiones que el navegador manda a QZ Tray.
 *
 * QZ Tray solo habilita la casilla "Remember this decision" (recordar el permiso)
 * cuando la peticion viene firmada; sin firma, el dialogo de autorizacion vuelve a
 * salir en CADA impresion. Este endpoint recibe el texto que QZ Tray quiere firmar
 * y devuelve la firma hecha con la llave privada del negocio.
 *
 * La llave privada nunca sale del servidor: solo viaja la firma.
 */

// validarAcceso exige sesion iniciada; sin esto cualquiera en internet podria pedir
// firmas y mandar impresiones a las sucursales.
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");

define("QZ_ACCESO_INTERNO", true);
include($_SERVER["DOCUMENT_ROOT"] . "/config/qz-llaves.php");

header("Content-Type: text/plain");
// La firma depende del texto (que lleva marca de tiempo), no debe cachearse nunca.
header("Cache-Control: no-store, no-cache, must-revalidate");

$porFirmar = isset($_GET["request"]) ? $_GET["request"] : "";

$llave = openssl_pkey_get_private($QZ_LLAVE_PRIVADA);
if($llave === false){
	header("HTTP/1.0 500 Internal Server Error");
	exit;
}

$firma = "";
// SHA512 es lo que espera QZ Tray desde la version 2.1 (las 2.0 y anteriores usaban SHA1).
if(openssl_sign($porFirmar, $firma, $llave, "sha512")){
	echo base64_encode($firma);
}else{
	header("HTTP/1.0 500 Internal Server Error");
}
?>
