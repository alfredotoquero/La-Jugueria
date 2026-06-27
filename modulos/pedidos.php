<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../002wf3f3kgdvr/983y4rhou.php");
	$idcorte = mysql_result(mysql_query("select idcorte from tcortes order by idcorte desc limit 1"),0);
	if($_POST["enviar"]==1){
		$numcuenta = $_POST["txtCuenta"]-1;
		$cuenta = mysql_result(mysql_query("select idcuenta from tcuentas where idcorte = '$idcorte' and status = 'A' and tipo = '2' order by idcuenta limit $numcuenta,1"),0);
	?>
    	<script>
			parent.recargarCuenta('<? echo $cuenta;?>');
			parent.$.fancybox.close();
		</script>
    <?
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
	$("#txtCuenta").focus();
});
</script>
<style>
body{
	margin:0px;
}
</style>
</head>

<body>
<div style="position:relative;">
	<div style="position:absolute; width:800px; height:500px; top:0px; left:0px;"><img src="../images/pantallaPedidos.jpg" width="800" height="500" border="0" /></div>
    <div style="position:absolute; width:697px; height:31px; top:56px; left:85px;">
    <form name="formCuenta" id="formCuenta" method="post" action="">
    	<input type="hidden" name="enviar" value="1" />
        <input type="text" name="txtCuenta" id="txtCuenta" style="border:0px; width:100%; height:100%;" />
    </form>
    </div>
    <div style="position:absolute; width:776px; height:381px; top:105px; left:12px;">
    <?
	$cuentas = mysql_query("select * from tcuentas where idcorte = '$idcorte' and status = 'A' and tipo = '2' order by idcuenta");
	if(mysql_num_rows($cuentas)>0){
	?>
    <table width="776" border="0" cellpadding="0" cellspacing="0">
    <?
	$num = 1;
	$cont = 1;
	while($cuenta = mysql_fetch_assoc($cuentas)){
		if($num==1){
		?>
        <tr>
        <?	
		}
		?>
        	<td width="188" height="88">
            	<div style="position:relative;">
                	<div style="position:absolute; width:188px; height:88px; top:0px; left:0px;"><img src="../images/fondoCuenta.jpg" width="188" height="88" border="0" /></div>
                    <div style="position: absolute; width:164px; height:70px; top:9px; left:12px;">
                    	<table width="164" border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td style="font-size:16px; font-weight:bold; color:#FFF;" height="50" align="center"><? if($cuenta["nombre"]!=""){ echo $cuenta["nombre"]; }else{ echo "Sin Nombre"; }?></td>
                            </tr>
                            <tr>
                            	<td align="right" style="font-size:10px; font-weight:bold; color:#FFF;" height="20"><? echo $cont;?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
    
    <?
		if($num==4){
		?>
        </tr>
        <?	
			$num=0;
		}
		$num++;
		$cont++;
	}
	?>
    </table>
    <?
	}
	?>
    </div>
</div>
</body>
</html>