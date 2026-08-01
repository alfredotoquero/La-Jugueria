<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/sesion.php");

if (isset($_SESSION["idusrx3209exum0q3em"])) {
    $fechaGuardada = $_SESSION["456udhsere"];
    $ahora = date("Y-n-j H:i:s");
    $tiempoTranscurrido = strtotime($ahora) - strtotime($fechaGuardada);

    if ($tiempoTranscurrido >= 43200) {
        unset($_SESSION["idusrx3209exum0q3em"]);
        unset($_SESSION["456udhsere"]);
        session_destroy();
        header("location: /index.php");
        exit;
    }

    $_SESSION["456udhsere"] = $ahora;
} else {
    header("location: /index.php");
    exit;
}
