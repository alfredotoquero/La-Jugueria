<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	if($_POST["enviar"]==1){
		$idmenu = $_POST["idmenu"];
		$cantidad = $_POST["slcCantidad"];
		$idingrediente = $_POST["idingrediente"];
		$tipo = $_POST["tipo"];
		$precio = $_POST["precio"];
		$cqsr = $_POST["cqsr"];
		$itta = $_POST["itta"];
		if($cqsr>0){
			$ingredientes = $_POST["txtIngrediente"];
			$num=0;
			foreach($ingredientes as $ingrediente){
				$ingrediente = explode("-",$ingrediente);
				$precio += (float)mysql_result(mysql_query("select precio from tcatcategoriascqsr where idcategoria = '".$ingrediente[0]."'"),0);
				mysql_query("insert into trcuentamenucqsrtmp values('$cqsr','".$ingrediente[0]."','".$ingrediente[1]."')");
			}
		}
		if($itta>0){
			$categorias = $_POST["txtCategoria"];
			$num=0;
			foreach($categorias as $categoria){
				$categoria = explode("-",$categoria);
				mysql_query("insert into trcuentamenuittatmp values('$itta','".$categoria[0]."','".$categoria[1]."')");
			}
			$precio = mysql_result(mysql_query("select precio from tittakates where idittakate = '".$_POST["idittakate"]."'"),0);
		}
		if(mysql_num_rows(mysql_query("select * from trcuentamenutmp where idmenu = '$idmenu' and idingrediente = '$idingrediente' and tipo = '$tipo' and cqsr = '$cqsr' and itta = '$itta'"))==0){
			mysql_query("insert into trcuentamenutmp values('$idmenu','$idingrediente','$cantidad','$tipo','$cqsr','$itta','$precio',0,0)");
		}else{
			mysql_query("update trcuentamenutmp set cantidad = cantidad + '$cantidad',precio = '$precio' where idmenu = '$idmenu' and idingrediente = '$idingrediente' and tipo = '$tipo'");
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
	$idmenu = $_GET["idmenu"];
	$idingrediente = $_GET["idingrediente"];
	$tipo = $_GET["tipo"];
	$precio = $_GET["precio"];
	if($_POST["precio"]==1){
		$idmenu = $_POST["idmenu"];
		$idingrediente = $_POST["idingrediente"];
		$tipo = $_POST["tipo"];
		$precio = $_POST["txtPrecio"];
	}
	if($_GET["enviarCQSR"]==1){
		$cqsr = (mysql_result(mysql_query("select max(cqsr) from trcuentamenutmp"),0) + 1);
		$ingredientes = $_GET["txtIngrediente"];
		$categorias = $_GET["txtCategoria"];
	}
	if($_GET["enviarITTA"]==1){
		$itta = (mysql_result(mysql_query("select max(itta) from trcuentamenutmp"),0) + 1);
		$idittakate = $_GET["idittakate"];
		$categoria1 = $_GET["txtCategoria1"];
		$categoria2 = $_GET["txtCategoria2"];
		$categoria3 = $_GET["txtCategoria3"];
		$categoria4 = $_GET["txtCategoria4"];
		$categoria5 = $_GET["txtCategoria5"];
	}
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
<input type="hidden" name="idmenu" value="<? echo $idmenu;?>" />
<input type="hidden" name="idingrediente" value="<? echo $idingrediente;?>" />
<input type="hidden" name="precio" value="<? echo $precio;?>" />
<input type="hidden" name="tipo" value="<? echo $tipo;?>" />
<input type="hidden" name="cqsr" value="<? echo $cqsr;?>" />
<input type="hidden" name="itta" value="<? echo $itta;?>" />
<?
if($cqsr>0){
foreach($ingredientes as $ingrediente){
?>
<input type="hidden" name="txtIngrediente[]" value="<? echo $ingrediente;?>" />
<?
}
}
if($itta>0){
?>
<input type="hidden" name="idittakate" value="<? echo $idittakate;?>" />
<input type="hidden" name="txtCategoria[]" value="<? echo $categoria1;?>" />
<input type="hidden" name="txtCategoria[]" value="<? echo $categoria2;?>" />
<input type="hidden" name="txtCategoria[]" value="<? echo $categoria3;?>" />
<input type="hidden" name="txtCategoria[]" value="<? echo $categoria4;?>" />
<input type="hidden" name="txtCategoria[]" value="<? echo $categoria5;?>" />
<?
}
?>
<div style="position:relative;">
	<div style="position:absolute; width:306px; height:130px; top:0px; left:0px;"><img src="../../images/pantallaCantidad.jpg" width="306" height="130" border="0" /></div>
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