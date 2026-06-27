<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	$idmenu = $_GET["idmenu"];
	$idingrediente = $_GET["idingrediente"];
	$tipo = $_GET["tipo"];
	$precio = $_GET["precio"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<script src="../../js/jquery.js"></script>
<script>
$(document).ready(function(){
	parent.$.fancybox.resize();
	$("#slcCantidad").focus();
});

function enviarFormulario(e) {
  tecla = (document.all) ? e.keyCode :e.which;
  if(tecla==13){
	 formCantidad.submit(); 
  }
} 
</script>
<style>
body{
	margin:0px;
}
</style>
</head>

<body>
<form name="formCantidad" id="formCantidad" method="post" action="cantidad.php">
<input type="hidden" name="precio" value="1" />
<input type="hidden" name="idmenu" value="<? echo $idmenu;?>" />
<input type="hidden" name="idingrediente" value="<? echo $idingrediente;?>" />
<input type="hidden" name="tipo" value="<? echo $tipo;?>" />
<div style="position:relative;">
	<div style="position:absolute; width:306px; height:130px; top:0px; left:0px;"><img src="../../images/pantallaPrecio.jpg" width="306" height="130" border="0" /></div>
    <div style="position:absolute; width:201px; height:25px; top:74px; left:92px;"><input type="text" name="txtPrecio" id="txtPrecio" style="border:0px; width:100%; height:100%;" /></div>
</div>
</form>
</body>
</html>