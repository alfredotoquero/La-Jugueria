<? 
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../../js/jquery.js"></script>
<script>

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==112 || code==113){
		evento.preventDefault();
	}
	if(code==27){
		parent.$.fancybox.close();
	}
}

$(document).ready(function(){
	$(document).keydown(manejarEventos);
	document.getElementById('txtNombre').focus();
});

function enviarForm(){
	document.getElementById('formRetiroEfectivo').submit();
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
  <div style="position:absolute; width:700px; height:530px; top:0px; left:0px;"><!--<img src="../images/clientes.jpg" width="600" height="410" border="0" usemap="#Map" />--><img src="http://hijosdelsushi.interface.mx/sistema/images/cliente.jpg" width="700" height="530" border="0" usemap="#Map" />
    <map name="Map" id="Map">
      <area shape="rect" coords="14,471,225,511" href="javascript:;" onclick="enviarForm();" />
      <area shape="rect" coords="244,470,455,512" href="#" />
      <area shape="rect" coords="472,472,682,512" href="#" />
    </map>
  </div>
  <div style="position: absolute; width: 330px; height: 32px; top: 84px; left: 16px; z-index: 2;">
    <input type="text" name="txtNombre" id="txtNombre" class="campo" style="width: 330px; height: 32px;" value="<? echo strtoupper($cliente['nombre']); ?>" />
  </div>
  <div style="position: absolute; width: 330px; height: 32px; top: 143px; left: 16px; z-index: 2;">
    <input type="text" name="txtCorreo" id="txtCorreo" class="campo" style="width: 330px; height: 32px;" value="<? echo strtoupper($cliente['correo']); ?>" />
  </div>
  <div style="position: absolute; width: 330px; height: 32px; top: 201px; left: 15px; z-index: 2;">
    <input type="text" name="txtTelefono" id="txtTelefono" class="campo" style="width: 330px; height: 32px;" value="<? echo strtoupper($cliente['telefono']); ?>" />
  </div>
<div style="position: absolute; width: 330px; height: 32px; top: 259px; left: 16px; z-index: 2;">
    <input type="text" name="txtDireccion" id="txtDireccion" class="campo" style="width: 330px; height: 32px;" value="<? echo strtoupper($cliente['direccion']); ?>" />
  </div>
   <div style="position: absolute; width: 335px; height: 130px; top: 325px; left: 14px; z-index: 2;">
     <textarea name="txtDescipcion" rows="5" class="campo" id="txtDescipcion" style="width: 330px; height: 130px;"><? echo strtoupper($cliente['referencia']); ?></textarea>
   </div>
   
</div>
</form>
</body>
</html>