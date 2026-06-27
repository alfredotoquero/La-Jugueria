<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../002wf3f3kgdvr/983y4rhou.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js"></script>

<script type="text/javascript" src="../js/jquery.quicksearch.js"></script>

<script type="text/javascript" src="../js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="../js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="../js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script>
$(document).ready(function(){
	$("#txtBusqueda").focus();
	$(document).keydown(manejarEventos);
	// Initialise Plugin
	$('input#txtBusqueda').quicksearch('table#tablaMenu tbody tr', {
		selector: 'th'
	});
});

function buscarPlatillos(){
	$.ajax({  
		type: "POST",  
		url: "menu/mostrarMenu.php",  
		data: "txtBusqueda=" + $("#txtBusqueda").val(),  
		success: function(data) {
			$("#divMenu").html(data);
		}
	});
}

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code>=112 && code <= 114){
		evento.preventDefault();
	}
	if(code==27){
		evento.preventDefault();
		parent.$.fancybox.close();
	}
	if(code==113){
		parent.fancy(600,410,'modulos/menu/cqsr.php');
	}
	if(code==114){
		parent.fancy(600,410,'modulos/menu/ittakates.php');
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
	<div style="position:absolute; width:600px; height:410px; top:0px; left:0px;"><img src="../images/menu.jpg" width="600" height="410" usemap="#Map" border="0" /></div>
    <div style="position:absolute; width:552px; height:30px; top:69px; left:22px;"><input type="text" style="width:100%; height:100%; border:0px;" name="txtBusqueda" id="txtBusqueda" /></div>
    <div id="divMenu" style="position:absolute; width:560px; height:212px; top:125px; left:19px; overflow:auto;">
    	<table width="540" align="center" cellpadding="3" cellspacing="0" border="0" id="tablaMenu">
        	<thead>
            	<tr>
                	<th align="center" width="50"><b>COD.</b></th>
                    <th align="center"><b>PLATILLO</b></th>
                    <th align="center" width="100"><b>PRECIO</b></th>
                    <th align="center" width="20"></th>
                </tr>
            </thead>
            <tbody>
            <?
			$platillos = mysql_query("select idmenu,nombre,precio,'0' as tipo,codigo from tmenu union select t2.idingrediente as idmenu,t1.producto as nombre,t3.precio,'1' as tipo,'0' as codigo from tinventario as t1,tcatingredientescqsr as t2,tcatcategoriascqsr as t3 where t1.idproducto = t2.idingrediente and t2.idcategoria = t3.idcategoria order by nombre");
			while($platillo = mysql_fetch_assoc($platillos)){
			?>
            	<tr onclick="parent.fancy(306,130,'modulos/menu/cantidad.php?idmenu=<? echo $platillo["idmenu"];?>&idingrediente=0&tipo=<? echo $platillo["tipo"];?>&precio=<? echo $platillo["precio"];?>');" class="activation">
                	<td align="center"><? echo $platillo["codigo"];?></td>
                    <th align="left"><? echo $platillo["nombre"];?></th>
                    <td align="right">$<? echo number_format($platillo["precio"],2);?></td>
                    <td>
                    <?
					if(mysql_num_rows(mysql_query("select * from trecetas where idplatillo = '".$platillo["idmenu"]."'"))>0){
					?>
                    <a href="javascript:;" onclick="parent.fancy(700,400,'modulos/menu/receta.php?idplatillo=<? echo $platillo["idmenu"];?>');"><img src="../images/imgReceta.png" /></a>
                    <?	
					}
					?>
                    </td>
                </tr>
            <?	
			}
			?>
            </tbody>
        </table>
    </div>
</div>
</body>
<map name="Map" id="Map">
<area shape="rect" coords="210,357,380,397" href="javascript:;" onclick="parent.fancy(600,410,'modulos/menu/cqsr.php');" />
<area shape="rect" coords="414,357,586,397" href="javascript:;" onclick="parent.fancy(600,410,'modulos/menu/ittakates.php');" />
</map>
</html>