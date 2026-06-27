<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../js/jquery.js"></script>
<script>

function manejarEventos(evento){
	evento.preventDefault();
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==27){
		parent.$.fancybox.close();
	}
	if(code==112){
		parent.fancy(300,360,'modulos/salidas/traspaso.php');
	}
	if(code==113){
		parent.fancy(300,360,'modulos/salidas/efectivo.php');
	}
}
$(document).ready(function(){
	$(document).keydown(manejarEventos);
	document.getElementById('txtFocus').focus();
});
</script>
<style>
body{
	margin:0px;
}
.campo{
	border:0px;
	outline:0px;
	background:none;
}
</style>
</head>

<body>
<div style="height:0px; width:0px;"><input type="text" id="txtFocus" name="txtFocus" /></div>
<div style="position:relative;" id="contenido">
	<div style="position:absolute; width:450px; height:170px; top:0px; left:0px;">
    <img src="../images/opcionesSalida.jpg" width="450" height="170" usemap="#Map" border="0" />
      <map name="Map" id="Map">
        <area shape="rect" coords="29,67,211,150" id="btnFocus" href="javascript:;" onclick="parent.fancy(300,360,'modulos/salidas/traspaso.php');" class="campo" />
        <area shape="rect" coords="240,68,420,150" href="javascript:;" onclick="parent.fancy(300,360,'modulos/salidas/efectivo.php');" class="campo" />
      </map>    
    </div>
</div>

</body>
</html>