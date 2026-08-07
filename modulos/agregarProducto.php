<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/validarAcceso.php");
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/con.php");
$idproducto = $_POST["idproducto"];
$precio = $_POST["precio"];
$idsucursal = $_SESSION["idsucx9284hqmzt7"];
if(mysqli_num_rows(mysqli_query($con, "select * from trcuentaproductostmp where idproducto = '$idproducto' and idsucursal = '$idsucursal'"))==0){
	mysqli_query($con, "insert into trcuentaproductostmp (idtmp,idsucursal,idproducto,cantidad,precio) values(null,'$idsucursal','$idproducto','1','$precio')");
}else{
	mysqli_query($con, "update trcuentaproductostmp set cantidad = cantidad + 1,precio = '$precio' where idproducto = '$idproducto' and idsucursal = '$idsucursal'");
}
?>