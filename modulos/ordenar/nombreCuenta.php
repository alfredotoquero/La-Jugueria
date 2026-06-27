<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	if($_POST["enviar"]==1){
		$nombre = $_POST["txtNombre"];
		$idcliente = $_POST["idcliente"];
		$corte = mysql_fetch_assoc(mysql_query("select * from tcortes order by idcorte desc limit 1"));
		mysql_query("insert into tcuentas values(null,'".$corte["idcorte"]."','".$corte["idsucursal"]."','".$corte["idcaja"]."','".$corte["idcajero"]."','$idcliente','$nombre','0','0','0','1','A','".date("Y-m-d-")."','".date("H:i:s")."','00:00:00')");
		$idcuenta = mysql_insert_id();
		mysql_query("insert into trcuentamenu select '".$idcuenta."',idmenu,idingrediente,cantidad,tipo,cqsr,itta,precio,descuento from trcuentamenutmp");
		mysql_query("insert into trcuentamenucqsr select '".$idcuenta."',idcqsr,idcategoria,idingrediente from trcuentamenucqsrtmp");
		mysql_query("insert into trcuentamenuitta select '".$idcuenta."',idittakate,idcategoria,idplatillo from trcuentamenuittatmp");
		mysql_query("truncate trcuentamenutmp");
		mysql_query("truncate trcuentamenucqsrtmp");
		mysql_query("truncate trcuentamenuittatmp");
		$_SESSION["idcuenta"]=0;
	?>
    	<script>
			alert("Cuenta guardada satisfactoriamente.");
			parent.idcuenta = 0;
			parent.recargarCuenta(0);
			parent.$.fancybox.close();
		</script>
    <?
	}
	$idcliente = $_GET["idcliente"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../../js/jquery.js"></script>
<script>
$(document).ready(function(){
	$("#txtNombre").focus();
});
</script>
<style>
body{
	margin:0px;
}
</style>
</head>

<body>
<div style="position:relative;">
	<div style="position:absolute; width:306px; height:130px; top:0px; left:0px;"><img src="../../images/pantallaNombreCuenta.jpg" width="306" height="130" border="0" /></div>
    <div style="position:absolute; width:188px; height:27px; top:73px; left:104px;">
    <form name="formNombreCuenta" id="formNombreCuenta" method="post" action="">
    	<input type="hidden" name="idcliente" value="<? echo $idcliente;?>" />
        <input type="hidden" name="enviar" value="1" />
    	<input type="text" name="txtNombre" id="txtNombre" style="border:0px; width:100%; height:100%;" />
    </form>
    </div>
</div>
</body>
</html>