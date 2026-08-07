<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
$idproducto = $_POST["idproducto"];
$idsucursal = $_SESSION["idsucx9284hqmzt7"];
if(mysqli_fetch_row(mysqli_query($con, "select cantidad from trcuentaproductostmp where idtmp = '$idproducto' and idsucursal = '$idsucursal'"))[0]>1){
	mysqli_query($con, "update trcuentaproductostmp set cantidad = cantidad - 1 where idtmp = '$idproducto' and idsucursal = '$idsucursal'");
}else{
	mysqli_query($con, "delete from trcuentaproductostmp where idtmp = '$idproducto' and idsucursal = '$idsucursal'");
}
?>