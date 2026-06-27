<?
include("../002wf3f3kgdvr/983y4rhouConRem.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../js/jquery.js"></script>
<script>

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==27 || code==112){
		evento.preventDefault();
	}
	if(code==27){
		parent.$.fancybox.close();
	}
	if(code==112){
		parent.fancy(700,530,'modulos/clientes/nuevoCliente.php');
	}
}

$(document).ready(function(){
	$(document).keydown(manejarEventos);
	document.getElementById('txtCliente').focus();
});

function enviarForm(){
	document.getElementById('formRetiroEfectivo').submit();
}

function consultarClientes(){
	var filtro = document.getElementById('txtCliente').value;
	$.ajax({  
		type: "POST",  
		url: "clientes/ajax/consultarClientes.php",  
		data: "filtro=" + filtro,  
		success: function(data) {
			$("#divClientes").html(data);
		}  
	});
}

function seleccionaCliente(idcliente){
	parent.fancy(700,530,'modulos/clientes/cliente.php?idcliente=' + idcliente);
}

function agregarCliente(){
	parent.fancy(700,530,'modulos/clientes/agregarCliente.php');
}

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
<form id="formRetiroEfectivo" name="formRetiroEfectivo" method="post" action="">
<input type="hidden" name="enviar" value="1" />
<div style="position:relative;" id="div">
  <div style="position:absolute; width:600px; height:410px; top:0px; left:0px;"><img src="../images/clientes.jpg" width="600" height="410" border="0" usemap="#Map" /><!--<img src="http://hijosdelsushi.interface.mx/sistema/images/clientes.jpg" width="600" height="410" border="0" usemap="#Map" />-->
    <map name="Map" id="Map">
      <area shape="rect" coords="437,8,582,42" href="javascript:;" onclick="agregarCliente();" />
    </map>
  </div>
  <div style="position: absolute; width: 550px; height: 35px; top: 66px; left: 15px; z-index: 2;">
    <input type="text" name="txtCliente" id="txtCliente" class="campo" style="width: 550px; height: 35px;" onkeypress="consultarClientes();" />
  </div>
   <div style="position: absolute; width:570px; height: 275px; top: 122px; left: 15px; z-index: 2; overflow:auto;" id="divClientes">
   </div>
</div>
</form>
<script>consultarClientes();</script> 
</body>
</html>