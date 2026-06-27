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
<title>CQSR</title>
<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<script src="../../js/jquery.js"></script>

<script type="text/javascript" src="../../js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="../../js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="../../js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<style type="text/css">
    table {border-collapse: collapse;}
    th, td {margin: 0; padding: 0.25em 0.5em;}
    /* This "tr.selected" style is the only rule you need for yourself. It highlights the selected table row. */
    tr.selected {background-color: red; color: white;}
    /* Not necessary but makes the links in selected rows white to... */
    tr.selected a {color: white;}
</style>

<script>
$(document).ready(function(){
	$(document).keydown(manejarEventos);
	document.getElementById('txtFocus').focus();
});

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==27){
		evento.preventDefault();
		parent.$.fancybox.close();
	}
}
</script>
<style>
body{
	margin:0px;
	background-color:#333;
}
</style>
</head>

<body>
<div style="position:relative;">
	<div style="height:0px; width:0px;"><input type="text" id="txtFocus" name="txtFocus" /></div>
	<div style="position:absolute; width:600px; height:410px; top:0px; left:0px;"><img src="../../images/ittakates.jpg" width="600" height="410" border="0" /></div>
    <div style="position:absolute; width:580px; height:340px; top:60px; left:10px; overflow:auto;">
    	<form name="formITTA" id="formITTA" method="post" action="">
        <input type="hidden" name="enviar" value="1" />
        <table width="540" align="center" border="0" cellpadding="3" cellspacing="0">
        <?
		$ittakates=mysql_query("select * from tittakates order by nombre");
		while($ittakate = mysql_fetch_assoc($ittakates)){
		?>
        	<tr bgcolor="#FFFFFF" height="30">
            	<td><b><? echo $ittakate["nombre"];?></b> <a href="javascript:;" onclick="parent.fancy(600,410,'modulos/menu/platillosIttakate.php?ittakate=<? echo $ittakate["idittakate"];?>');">Seleccionar</a></td>
            </tr>
         	<?
			$categoria=1;
			while($categoria<6){
				?>
				<tr class="titTabla"><td>Categoria <? echo $categoria; ?></td></tr>
				<?
				$platillos = mysql_query("select * from tplatillosittakate where idcategoria = $categoria and idittakate = '".$ittakate['idittakate']."'");
				while($platillo=mysql_fetch_assoc($platillos)){
				?>
					<tr><td style="color:#FFF;"><? echo $platillo['platillo']; ?></td></tr>
				<?
				}
				$categoria++;
			}
		}
		?>
        </table>
        </form>
    </div>
</div>
</body>
</html>