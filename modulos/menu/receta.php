<?
ini_set("session.gc_maxlifetime","43200");  
session_name("gt4e57i6rhdrg");
session_start();
include("../../002wf3f3kgdvr/983y4rhouConRem.php");
include("../../002wf3f3kgdvr/983y4rhou.php");
$idplatillo = $_GET["idplatillo"];
$preparacion = mysql_fetch_assoc(mysql_query("select * from trecetas where idplatillo = '$idplatillo'"));
$platillo = mysql_fetch_assoc(mysql_query("select * from tmenu where idmenu = '$idplatillo'"));
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
	document.getElementById('txtFocus').focus();
	$(document).keydown(manejarEventos);
});

function manejarEventos(evento){
	evento.preventDefault();
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==27){
		parent.fancy(600,410,'modulos/menu.php');
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
	<div style="position:absolute; width:700px; height:400px; top:0px; left:0px;">
    	<img src="../../images/pantallaReceta.jpg" width="700" height="400" border="0" />
    	<div style="position:absolute; width:364px; height:309px; top:71px; left:19px; overflow:auto;">
        	<table width="350" align="center" border="0" cellpadding="5" cellspacing="0">
            	<tr><td><strong><? echo $platillo["nombre"];?></strong></td></tr>
                <tr><td><? echo $platillo["descripcion"];?></td></tr>
                <tr><td><strong>Ingredientes:</strong></td></tr>
                <tr><td>
                <?
				$ingredientes = mysql_query("select * from trplatilloingredientes where idplatillo = '$idplatillo'");
				while($ingrediente = mysql_fetch_assoc($ingredientes)){
					$unidad = "";
					$idunidad = mysql_result(mysql_query("select idunidad_platillo from tinventario where idproducto = '".$ingrediente['idingrediente']."'"),0);
            		if($idunidad!=0){ $unidad =  mysql_result(mysql_query("select unidad from tcatunidades where idunidad = '".$idunidad."'"),0); }
					echo " - ".$ingrediente["cantidad"]." ".$unidad." ".mysql_result(mysql_query("select producto from tinventario where idproducto = '".$ingrediente["idingrediente"]."'"),0)."<br>";
				}
				?>
                </td></tr>
            </table>
        </div>
        <div style="position:absolute; width:268px; height:309px; top:71px; left:406px; overflow:auto;">
        	<table width="260" align="center" border="0" cellpadding="5" cellspacing="0">
            	<tr><td><strong>Preparaci&oacute;n:</strong></td></tr>
                <tr><td>
				<? if($preparacion["paso1"]!=""){echo "1.- ".$preparacion["paso1"]."<br>";}?>
                <? if($preparacion["paso2"]!=""){echo "2.- ".$preparacion["paso2"]."<br>";}?>
                <? if($preparacion["paso3"]!=""){echo "3.- ".$preparacion["paso3"]."<br>";}?>
                <? if($preparacion["paso4"]!=""){echo "4.- ".$preparacion["paso4"]."<br>";}?>
                <? if($preparacion["paso5"]!=""){echo "5.- ".$preparacion["paso5"]."<br>";}?>
                <? if($preparacion["paso6"]!=""){echo "6.- ".$preparacion["paso6"]."<br>";}?>
                <? if($preparacion["paso7"]!=""){echo "7.- ".$preparacion["paso7"]."<br>";}?>
                <? if($preparacion["paso8"]!=""){echo "8.- ".$preparacion["paso8"]."<br>";}?>
                <? if($preparacion["paso9"]!=""){echo "9.- ".$preparacion["paso9"]."<br>";}?>
                <? if($preparacion["paso10"]!=""){echo "10.- ".$preparacion["paso10"]."<br>";}?>
                </td></tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>