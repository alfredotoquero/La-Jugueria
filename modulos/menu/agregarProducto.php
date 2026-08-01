<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
$idproducto = $_POST["idproducto"];
mysqli_query($con, "update trcuentaproductostmp set cantidad = cantidad + 1 where idtmp = '$idproducto'");
?>