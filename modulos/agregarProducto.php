<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
$idproducto = $_POST["idproducto"];
$precio = $_POST["precio"];
if(mysqli_num_rows(mysqli_query($con, "select * from trcuentaproductostmp where idproducto = '$idproducto'"))==0){
	mysqli_query($con, "insert into trcuentaproductostmp values(null,'$idproducto','1','$precio')");
}else{
	mysqli_query($con, "update trcuentaproductostmp set cantidad = cantidad + 1,precio = '$precio' where idproducto = '$idproducto'");
}
?>