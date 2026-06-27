<table width="100%" border="0" cellspacing="5" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	<?
		include("../../../002wf3f3kgdvr/983y4rhouConRem.php");
		$valor = $_POST['filtro'];
		$filtro="";
		if($valor!=""){
			$filtro = "and nombre like '%$valor%' or telefono like '%$valor%'";
		}
		$clientes = mysql_query("select * from tclientes where nombre<>'' $filtro order by nombre");
		while($cliente = mysql_fetch_assoc($clientes)){
			echo "<tr onclick=\"seleccionaCliente(".$cliente['idcliente'].");\"><td><strong><span style=\"font-size:18px;\">".strtoupper($cliente['nombre'])."</strong>
			<br>TEL.".$cliente['telefono']." / ".strtoupper($cliente['direccion'])."
			</td></tr>";
		}
    ?>
</table>