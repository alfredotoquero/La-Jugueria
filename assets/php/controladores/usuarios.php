<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/sesion.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/clases/Usuarios.php");

$respuesta = array("success" => false, "message" => "Acción no permitida.");

try {
    $claseUsuarios = new Usuarios();

    switch ($_POST["accion"]) {
        case "login":
            if ($_POST["authToken"] == $_SESSION["authToken"]) {
                $respuesta = $claseUsuarios->iniciarSesion($_POST);
                if ($respuesta["success"]) {
                    $_SESSION["idusrx3209exum0q3em"] = $respuesta["idusuario"];
                    $_SESSION["idsucx9284hqmzt7"] = $respuesta["idsucursal"];
                    $_SESSION["sucslgx7742kdnq1"] = $respuesta["slugsucursal"];
                    $_SESSION["456udhsere"] = date("Y-n-j H:i:s");
                    unset($_SESSION["authToken"]);
                }
            }
        break;
        default:
            $respuesta = array("success" => false, "message" => "Acción no permitida.");
        break;
    }
} catch (Exception $e) {
    $respuesta = array("success" => false, "message" => "Ocurrió un error inesperado. Intenta de nuevo.");
} finally {
    echo json_encode($respuesta);
}
