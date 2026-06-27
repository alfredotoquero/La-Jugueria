<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	$idcuenta = $_SESSION["idcuenta"];
	if($_POST["enviar"]==1){
		$descuento = $_POST["txtDescuento"];
		mysql_query("update trcuentamenutmp set descuento = '$descuento'");
		?>
        <script>
			parent.recargarCuenta('');
			parent.$.fancybox.close();
		</script>
        <?
	}
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
	$("#txtDescuento").focus();
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
<form name="formCantidad" id="formCantidad" method="post" action="">
<input type="hidden" name="enviar" value="1" />
<div style="position:relative;">
	<div style="position:absolute; width:306px; height:130px; top:0px; left:0px;"><img src="../../images/pantallaDescuento.jpg" width="306" height="130" border="0" /></div>
    <div style="position:absolute; width:163px; height:19px; top:72px; left:121px;"><input type="text" name="txtDescuento" id="txtDescuento" style="border:0px; width:100%; height:100%;" /></div>
</div>
</form>
</body>
</html>