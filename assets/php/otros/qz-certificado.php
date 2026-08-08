<?php
/**
 * Entrega el certificado publico con el que QZ Tray verifica las firmas que genera
 * qz-firma.php. El certificado NO es secreto (es la contraparte publica de la llave),
 * pero se pide sesion igual para no exponer datos del negocio sin necesidad.
 */
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");

define("QZ_ACCESO_INTERNO", true);
include($_SERVER["DOCUMENT_ROOT"] . "/config/qz-llaves.php");

header("Content-Type: text/plain");
header("Cache-Control: no-store, no-cache, must-revalidate");

echo $QZ_CERTIFICADO;
?>
