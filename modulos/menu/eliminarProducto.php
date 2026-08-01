<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
$idproducto = $_POST["idproducto"];
if(mysqli_fetch_row(mysqli_query($con, "select cantidad from trcuentaproductostmp where idtmp = '$idproducto'"))[0]>1){
	mysqli_query($con, "update trcuentaproductostmp set cantidad = cantidad - 1 where idtmp = '$idproducto'");
}else{
	mysqli_query($con, "delete from trcuentaproductostmp where idtmp = '$idproducto'");
}
?>