<?php
/**
 * Helpers para armar tickets como comandos ESC/POS crudos.
 * El resultado (un string de bytes) se manda tal cual, en base64, a QZ Tray
 * desde el navegador (ver admin.php / imprimirTicket), que lo reenvía a la
 * impresora térmica física de la sucursal.
 */

/**
 * Columnas de texto que caben en una linea de la impresora termica en Font A.
 * Las impresoras de 80mm de la sucursal imprimen 42 caracteres por linea
 * (no 48, que fue lo que se asumio al migrar a QZ Tray y hacia que cada
 * separador y cada fila se cortaran arrastrando 6 caracteres al renglon
 * siguiente). Todas las tablas del ticket deben sumar este ancho.
 */
if(!defined("ANCHO_TICKET")){
	define("ANCHO_TICKET", 42);
}

function escposInit(){
	return chr(27).chr(64); // ESC @ - inicializa la impresora
}

function escposAlign($align){
	$valores = array("left" => 0, "center" => 1, "right" => 2);
	$valor = isset($valores[$align]) ? $valores[$align] : 0;
	return chr(27).chr(97).chr($valor); // ESC a n
}

function escposBold($on){
	return chr(27).chr(69).chr($on ? 1 : 0); // ESC E n
}

function escposTamano($doble){
	return chr(29).chr(33).chr($doble ? 0x11 : 0x00); // GS ! n (doble alto+ancho o normal)
}

function escposLinea($texto = ""){
	return $texto."\n";
}

/**
 * Arma una fila con varias columnas de ancho fijo (para tablas tipo
 * "CANT. | PRODUCTO | PRECIO | IMPORTE" o "TOTAL ... $123.45").
 *
 * @param array $columnas cada elemento es [texto, ancho, alinear] con alinear = 'left'|'right'
 * @return string
 */
function escposFila($columnas){
	$linea = "";
	foreach($columnas as $columna){
		list($texto, $ancho, $align) = $columna;
		$linea .= $align === "right"
			? str_pad($texto, $ancho, " ", STR_PAD_LEFT)
			: str_pad($texto, $ancho, " ", STR_PAD_RIGHT);
	}
	return rtrim($linea)."\n";
}

/**
 * Cierra el ticket: avanza el papel y lo corta.
 *
 * Es indispensable emitirlo. Con la extension printer_* esto lo hacia solo el
 * driver GDI de Windows al cerrar el documento (printer_end_doc); mandando
 * bytes crudos por QZ Tray el driver ya no interviene, y sin esto las ultimas
 * lineas se quedan dentro de la impresora (hay que darle FEED a mano).
 *
 * El avance previo existe porque el cabezal termico esta ~2cm antes de la
 * cuchilla: lo ya impreso en ese tramo debe salir antes de cortar.
 */
function escposCorte($lineas = 4){
	return str_repeat("\n", $lineas).chr(29).chr(86).chr(66).chr(0); // GS V B n - corte parcial
}

function escposAbrirCajon(){
	// Mismo pulso que ya se mandaba con printer_write() en el código anterior.
	return chr(27).chr(112).chr(0).chr(100).chr(250);
}

/**
 * Parte un texto largo en varias líneas de máximo $length caracteres,
 * cortando por palabra completa (para nombres de producto largos).
 */
function dividirTexto($cadena, $length){
	$palabras = explode(" ", $cadena);
	$texto = "";
	$lineas = array();
	foreach($palabras as $palabra){
		if((strlen($texto) + strlen($palabra)) <= $length){
			$texto .= $palabra." ";
		}else{
			if(strlen($texto) > 0){
				$texto = substr($texto, 0, -1);
			}
			$lineas[] = $texto;
			$texto = $palabra." ";
		}
	}
	if(strlen($texto) > 0){
		$texto = substr($texto, 0, -1);
		$lineas[] = $texto;
	}
	return $lineas;
}
?>
