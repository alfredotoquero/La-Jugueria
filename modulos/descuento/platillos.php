<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	if($_POST["enviar"]==1){
		$platillos = $_POST["txtPlatillo"];
		$descuentos = $_POST["txtDescuento"];
		$num = 0;
		foreach($descuentos as $descuento){
			$platillo = explode("-",$platillos[$num]);
			mysql_query("update trcuentamenutmp set descuento = '$descuento' where idmenu = '".$platillo[0]."' and tipo = '".$platillo[1]."' and cqsr = '".$platillo[2]."' and itta = '".$platillo[3]."' and precio = '".$platillo[4]."'");
			$num++;
		}
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
	$(':input:visible:enabled:first',document).focus();
	$(document).keydown(manejarEventos);
});

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
	background-color:#333;
}
</style>
</head>

<body>
<div style="position:relative;">
	<div style="position:absolute; width:600px; height:410px; top:0px; left:0px;"><img src="../../images/descuentoPlatillo.jpg" width="600" height="410" border="0" /></div>
    <div style="position:absolute; width:580px; height:340px; top:60px; left:10px; overflow:auto;">
    	<form name="formCQSR" id="formCQSR" method="post" action="">
        <input type="hidden" name="enviar" value="1" />
        <table width="540" align="center" border="0" cellpadding="3" cellspacing="0">
        <?
		$platillos = mysql_query("select * from trcuentamenutmp where idingrediente = 0");
		while($platillo = mysql_fetch_assoc($platillos)){
			if($platillo["descuento"]==0){
				$total += (float)($platillo["precio"]*$platillo["cantidad"]);
				$precio = $platillo["precio"];
			}else{
				$total += (float)(($platillo["precio"]*($platillo["descuento"]/100))*$platillo["cantidad"]);
				$precio = $platillo["precio"]*((100-$platillo["descuento"])/100);
			}
			$num++;
			?>
			<tr height="30">
				<td style="color:#FFF; font-weight:bold;"><input type="hidden" name="txtPlatillo[]" value="<? echo $platillo["idmenu"];?>-<? echo $platillo["tipo"];?>-<? echo $platillo["cqsr"];?>-<? echo $platillo["itta"];?>-<? echo $platillo["precio"];?>" style="width:30px;" /><input type="text" name="txtDescuento[]" value="<? echo $platillo["descuento"];?>" style="width:30px;" /> <? if($platillo["tipo"]==0 && $platillo["cqsr"]==0 && $platillo["itta"]==0){ echo mysql_result(mysql_query("select nombre from tmenu where idmenu = '".$platillo["idmenu"]."'"),0); }else if($platillo["tipo"]==1){ echo mysql_result(mysql_query("select producto from tinventario where idproducto = '".$platillo["idmenu"]."'"),0); }else if($platillo["cqsr"]==1){ echo "Cada quien su rollo"; }else{ echo "Itta-kate"; }?></td>
			</tr>
			<?
			if($platillo["cqsr"]==0 && $platillo["itta"]==0){
				$ingredientes = mysql_query("select * from trcuentamenutmp where idingrediente > 0 and idmenu = '".$platillo["idmenu"]."'");
				while($ingrediente = mysql_fetch_assoc($ingredientes)){
				?>
				<tr height="30">
					<td style="color:#FFF;">&nbsp;&nbsp;SIN <? echo mysql_result(mysql_query("select producto from tinventario where idproducto = '".$ingrediente["idingrediente"]."'"),0);?></td>
				</tr>
				<?		
				}
			}else{
				if($platillo["cqsr"]>0){
					$ingredientes = mysql_query("select * from trcuentamenucqsrtmp where idcqsr = '".$platillo["cqsr"]."'");
					while($ingrediente = mysql_fetch_assoc($ingredientes)){
					?>
					<tr height="30">
						<td style="color:#FFF;">&nbsp;&nbsp;<? echo mysql_result(mysql_query("select producto from tinventario where idproducto = '".$ingrediente["idingrediente"]."'"),0)." (".mysql_result(mysql_query("select nombre from tcatcategoriascqsr where idcategoria = '".$ingrediente["idcategoria"]."'"),0).")";?></td>
					</tr>
					<?		
					}
				}
				if($platillo["itta"]>0){
					$platillositta = mysql_query("select * from trcuentamenuittatmp where idittakate = '".$platillo["itta"]."'");
					while($platilloitta = mysql_fetch_assoc($platillositta)){
					?>
					<tr height="30">
						<td style="color:#FFF;">&nbsp;&nbsp;<? echo mysql_result(mysql_query("select platillo from tplatillosittakate where idplatillo = '".$platilloitta["idplatillo"]."'"),0)." (Categoria ".$platilloitta["idcategoria"].")";?></td>
					</tr>
					<?		
					}
				}
			}
		}
		?>
        </table>
        </form>
    </div>
</div>
</body>
</html>