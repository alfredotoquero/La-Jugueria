<?
	ini_set("session.gc_maxlifetime","43200");
	session_name("2q093ex8uq2ewun");
	session_start();
	date_default_timezone_set('America/Los_Angeles');
	include("../002wf3f3kgdvr/983y4rhouCon.php");
	include("../002wf3f3kgdvr/983y4rhou.php");
	if($_POST["enviar"]==1){
		$idproducto = $_POST["idproducto"];
		$cantidad = $_POST["slcCantidad"];
		$precio = $_POST["precio"];
		if(mysql_num_rows(mysql_query("select * from trcuentaproductostmp where idproducto = '$idproducto'"))==0){
			mysql_query("insert into trcuentaproductostmp values(null,'$idproducto','$cantidad','$precio')");
		}else{
			mysql_query("update trcuentaproductostmp set cantidad = cantidad + '$cantidad',precio = '$precio' where idproducto = '$idproducto'");
		}
	?>
    	<script>
			parent.recargarCuenta('');
			parent.$("#txtBusqueda").val("");
			parent.$("#txtBusqueda").focus();
			parent.$.fancybox.close();
		</script>
    <?
	}
	$idproducto = $_GET["idproducto"];
	$precio = $_GET["precio"];
	if($_POST["precio"]==1){
		$idproducto = $_POST["idproducto"];
		$precio = $_POST["txtPrecio"];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js"></script>
<script>
$(document).ready(function(){
	parent.$.fancybox.resize();
	$("#slcCantidad").focus();
	$(document).keydown(manejarEventos);
});

function enviarFormulario(e) {
  tecla = (document.all) ? e.keyCode :e.which;
  if(tecla==13){
	 formCantidad.submit(); 
  }
} 

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	evento.preventDefault();
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
<form name="formCantidad" id="formCantidad" method="post" action="">
<input type="hidden" name="enviar" value="1" />
<input type="hidden" name="idcuenta" value="<? echo $idcuenta;?>" />
<input type="hidden" name="idproducto" value="<? echo $idproducto;?>" />
<input type="hidden" name="precio" value="<? echo $precio;?>" />
<div style="position:relative;">
	<div style="position:absolute; width:306px; height:130px; top:0px; left:0px;"><img src="../images/pantallaCantidad.jpg" width="306" height="130" border="0" /></div>
    <div style="position:absolute; width:179px; height:27px; top:73px; left:117px;">
    	<select name="slcCantidad" id="slcCantidad" style="width:100%; height:100%;" onKeyUp="enviarFormulario(event);">
        <?
		for($i=1;$i<=20;$i++){
		?>
        	<option value="<? echo $i;?>"><? echo $i;?></option>
        <?
		}
		?>
        </select>
    </div>
</div>
</form>
</body>
</html>