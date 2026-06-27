<?
ini_set("session.gc_maxlifetime","43200");  
session_name("gt4e57i6rhdrg");
session_start();
$idcliente = $_SESSION['idcliente'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../js/jquery.js"></script>
<script>
$(document).ready(function(){
	document.getElementById('txtFocus').focus();
	$(document).keydown(manejarEventos);
});

function manejarEventos(evento){
	evento.preventDefault();
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==112){
		parent.fancy(306,130,'modulos/ordenar/nombreCuenta.php?idcliente=<? echo $idcliente;?>');
	}
	if(code==113){
		<?
		if($idcliente>0){
		?>
		parent.fancy(306,130,'modulos/ordenar/domicilio.php');
		<?
		}else{
		?>
		alert("Debes seleccionar un cliente primero.");
		parent.$.fancybox.close();
		<?	
		}
		?>
	}
	if(code==27){
		parent.$.fancybox.close();
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
<div style="height:0px; width:0px;"><input type="text" id="txtFocus" name="txtFocus" /></div>
<div style="position:relative;" id="divContenido">
	<div style="position:absolute; width:450px; height:170px; top:0px; left:0px;"><img src="../images/opcionesOrdenar.jpg" width="450" height="170" usemap="#Map" border="0" /></div>
</div>
<map name="Map" id="Map">
	<area shape="rect" coords="30,69,210,149" href="#" onclick="parent.fancy(306,130,'modulos/ordenar/nombreCuenta.php');" />
    <area shape="rect" coords="240,69,420,149" href="#" />
</map>
</body>
</html>