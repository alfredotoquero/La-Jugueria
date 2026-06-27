<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<script src="../../js/jquery.js"></script>

<script type="text/javascript" src="../../js/jquery.quicksearch.js"></script>

<script type="text/javascript" src="../../js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="../../js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="../../js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script src="../../js/tablenavigation.js"></script>
<script src="../../js/funciones.js"></script>
<script>
$(document).ready(function(){
	$("#txtBusqueda").focus();
	$(document).keydown(manejarEventos);
	
	$('input#txtBusqueda').quicksearch('table tbody tr');
});

function buscarIngredientes(){
	$.ajax({  
		type: "POST",  
		url: "menu/mostrarIngredientes.php",  
		data: "txtBusqueda=" + $("#txtBusqueda").val() + "&idmenu=<? echo $_GET["idmenu"];?>",  
		success: function(data) {
			$("#divMenu").html(data);
		}  
	});
}

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code>=112 && code<=122){
		evento.preventDefault();
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
<div style="position:relative;">
	<div style="position:absolute; width:600px; height:410px; top:0px; left:0px;"><img src="../../images/pantallaIngredientes.jpg" width="600" height="410" border="0" /></div>
    <div style="position:absolute; width:552px; height:30px; top:69px; left:22px;"><input type="text" style="width:100%; height:100%; border:0px;" name="txtBusqueda" id="txtBusqueda" /></div>
    <div id="divMenu" style="position:absolute; width:560px; height:212px; top:125px; left:19px; overflow:auto;">
    	<table width="540" align="center" cellpadding="3" cellspacing="0" border="0" class="navigateable">
        	<thead>
            	<tr>
                	<td align="center"><b>INGREDIENTE</b></td>
                </tr>
            </thead>
            <tbody>
            <?
			$ingredientes = mysql_query("select * from tinventario where ingrediente = '1' and idproducto in (select idingrediente from trplatilloingredientes where idplatillo = '".$_GET["idmenu"]."') order by producto");
			while($ingrediente = mysql_fetch_assoc($ingredientes)){
			?>
            	<tr onclick="parent.fancy(306,130,'modulos/menu/cantidad.php?idingrediente=<? echo $ingrediente["idproducto"];?>&idmenu=<? echo $_GET["idmenu"];?>&tipo=0&precio=0');">
                	<td align="left"><? echo $ingrediente["producto"];?></td>
                </tr>
            <?	
			}
			?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>