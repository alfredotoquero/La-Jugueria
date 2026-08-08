<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/sesion.php");

/**
 * Regresar al login conservando la sucursal en la URL (el login solo se
 * muestra cuando recibe un slug de sucursal válido).
 *
 * @param string $sucursal
 */
function redirigirALogin($sucursal)
{
    $sucursal = strtolower(trim($sucursal));

    if (preg_match("/^[a-z0-9-]+$/", $sucursal) === 1) {
        header("location: /index.php?sucursal=" . rawurlencode($sucursal));
    } else {
        header("location: /index.php");
    }

    exit;
}

$sucursal = isset($_SESSION["sucslgx7742kdnq1"]) ? $_SESSION["sucslgx7742kdnq1"] : "";

if (isset($_SESSION["idusrx3209exum0q3em"])) {
    $fechaGuardada = $_SESSION["456udhsere"];
    $ahora = date("Y-n-j H:i:s");
    $tiempoTranscurrido = strtotime($ahora) - strtotime($fechaGuardada);

    if ($tiempoTranscurrido >= 43200) {
        unset($_SESSION["idusrx3209exum0q3em"]);
        unset($_SESSION["456udhsere"]);
        session_destroy();
        redirigirALogin($sucursal);
    }

    $_SESSION["456udhsere"] = $ahora;
} else {
    redirigirALogin($sucursal);
}
