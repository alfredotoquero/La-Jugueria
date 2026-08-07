<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
	include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
	$idsucursal = $_SESSION["idsucx9284hqmzt7"];
	mysqli_query($con, "delete from trcuentaproductostmp where idsucursal = '$idsucursal'");
?>