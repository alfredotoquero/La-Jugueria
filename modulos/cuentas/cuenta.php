<?
	ini_set("session.gc_maxlifetime","43200");  
	session_name("gt4e57i6rhdrg");
	session_start();
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	include("../../002wf3f3kgdvr/983y4rhou.php");
	if($_GET["idcuenta"]>0){
	?>
    	<script>
    	document.getElementById("txtNombreCuenta").innerHTML = '<? echo strtoupper(mysql_result(mysql_query("select nombre from tcuentas where idcuenta = '".$_GET["idcuenta"]."'"),0));?>';
		</script>
    <?
		mysql_query("truncate trcuentamenutmp");
		mysql_query("truncate trcuentamenucqsrtmp");
		mysql_query("truncate trcuentamenuittatmp");
		mysql_query("insert into trcuentamenutmp select idmenu,idingrediente,cantidad,tipo,cqsr,itta,precio,descuento,'".$_GET["idcuenta"]."' from trcuentamenu where idcuenta = '".$_GET["idcuenta"]."'");
		mysql_query("insert into trcuentamenucqsrtmp select idcqsr,idcategoria,idingrediente from trcuentamenucqsr where idcuenta = '".$_GET["idcuenta"]."'");
		mysql_query("insert into trcuentamenuittatmp select idittakate,idcategoria,idplatillo from trcuentamenuitta where idcuenta = '".$_GET["idcuenta"]."'");
	}else{
		if($_SESSION["idcuenta"]>0){
		?>
        <script>
    	document.getElementById("txtNombreCuenta").innerHTML = '<? echo mysql_result(mysql_query("select nombre from tcuentas where idcuenta = '".$_SESSION["idcuenta"]."'"),0);?>';
		</script>
        <?
		}else{
		?>
    	<script>
    	document.getElementById("txtNombreCuenta").innerHTML = '';
		</script>
    	<?	
		}
	}
?>
<table width="690" border="0" cellpadding="3" cellspacing="0" align="center" class="navigateable">
    <thead>
        <tr height="30">
            <td width="50" align="center"><b>CANT.</b></td>
            <td align="center"><b>CONCEPTO</b></td>
            <td width="100" align="center"><b>P.U.</b></td>
            <td width="100" align="center"><b>% DESC.</b></td>
            <td width="100" align="center"><b>TOTAL</b></td>
            <td width="50"></td>
        </tr>
    </thead>
    <tbody>
    <?
	$total = 0;
	$num = 0;
    $platillos = mysql_query("select * from trcuentamenutmp where idingrediente = 0");
    while($platillo = mysql_fetch_assoc($platillos)){
		if($platillo["descuento"]==0){
			$precio = $platillo["precio"];
			$total += (float)($precio*$platillo["cantidad"]);
		}else{
			$precio = $platillo["precio"]*((100-$platillo["descuento"])/100);
			$total += (float)($precio*$platillo["cantidad"]);
			$precio = $platillo["precio"];
		}
		$num++;
    	?>
        <tr height="30">
            <td align="center" onmouseout="borrarDescripcion();" onmouseover="<? if(file_exists("http://www.hijosdelsushi.interface.mx/imagenes/menu/menu".$platillo["idmenu"].".jpg")){ ?>cargarImagen('<? echo $platillo["idmenu"];?>');<? } ?>cargarDescripcion('<? echo $platillo["descripcion"];?>');" <? if($platillo["tipo"]==0){ ?>onclick="fancy(600,410,'modulos/menu/ingredientes.php?idmenu=<? echo $platillo["idmenu"];?>');"<? } ?>><? echo $platillo["cantidad"];?></td>
            <td <? if($platillo["tipo"]==0){ ?>onclick="fancy(600,410,'modulos/menu/ingredientes.php?idmenu=<? echo $platillo["idmenu"];?>');"<? } ?>><? if($platillo["tipo"]==0 && $platillo["cqsr"]==0 && $platillo["itta"]==0){ echo mysql_result(mysql_query("select nombre from tmenu where idmenu = '".$platillo["idmenu"]."'"),0); }else if($platillo["tipo"]==1){ echo mysql_result(mysql_query("select producto from tinventario where idproducto = '".$platillo["idmenu"]."'"),0); }else if($platillo["cqsr"]==1){ echo "Cada quien su rollo"; }else{ echo "Itta-kate"; }?></td>
            <td <? if($platillo["tipo"]==0){ ?>onclick="fancy(600,410,'modulos/menu/ingredientes.php?idmenu=<? echo $platillo["idmenu"];?>');"<? } ?> align="right">$<? echo number_format($precio,2);?></td>
            <td <? if($platillo["tipo"]==0){ ?>onclick="fancy(600,410,'modulos/menu/ingredientes.php?idmenu=<? echo $platillo["idmenu"];?>');"<? } ?> align="right">%<? echo number_format($platillo["descuento"],2);?></td>
            <td <? if($platillo["tipo"]==0){ ?>onclick="fancy(600,410,'modulos/menu/ingredientes.php?idmenu=<? echo $platillo["idmenu"];?>');"<? } ?> align="right">$<? echo number_format($total,2);?></td>
            <td align="center"><a href="javascript:;" onclick="agregarPlatillo('<? echo $platillo["idmenu"];?>','<? echo $platillo["idingrediente"];?>','<? echo $platillo["tipo"];?>','<? echo $platillo["cqsr"];?>','<? echo $platillo["itta"];?>');"><img src="images/iconoMas.png" /></a> <a href="javascript:;" onclick="eliminarPlatillo('<? echo $platillo["idmenu"];?>','<? echo $platillo["idingrediente"];?>','<? echo $platillo["tipo"];?>','<? echo $platillo["cqsr"];?>','<? echo $platillo["itta"];?>');"><img src="images/iconoMenos.png" /></a></td>
        </tr>
    	<?
		if($platillo["cqsr"]==0 && $platillo["itta"]==0){
			$ingredientes = mysql_query("select * from trcuentamenutmp where idingrediente > 0 and idmenu = '".$platillo["idmenu"]."'");
			while($ingrediente = mysql_fetch_assoc($ingredientes)){
			?>
			<tr height="30">
				<td align="right">* <? echo $ingrediente["cantidad"];?></td>
				<td>SIN <? echo mysql_result(mysql_query("select producto from tinventario where idproducto = '".$ingrediente["idingrediente"]."'"),0);?></td>
				<td align="right"></td>
				<td align="right"></td>
                <td></td>
				<td align="center"><a href="javascript:;" onclick="agregarPlatillo('<? echo $ingrediente["idmenu"];?>','<? echo $ingrediente["idingrediente"];?>','<? echo $ingrediente["tipo"];?>','<? echo $ingrediente["cqsr"];?>','<? echo $ingrediente["itta"];?>');"><img src="images/iconoMas.png" /></a> <a href="javascript:;" onclick="eliminarPlatillo('<? echo $ingrediente["idmenu"];?>','<? echo $ingrediente["idingrediente"];?>','<? echo $ingrediente["tipo"];?>','<? echo $ingrediente["cqsr"];?>','<? echo $ingrediente["itta"];?>');"><img src="images/iconoMenos.png" /></a></td>
			</tr>
			<?		
			}
		}else{
			if($platillo["cqsr"]>0){
            	$ingredientes = mysql_query("select * from trcuentamenucqsrtmp where idcqsr = '".$platillo["cqsr"]."'");
                while($ingrediente = mysql_fetch_assoc($ingredientes)){
                ?>
                <tr height="30">
                    <td align="right"></td>
                    <td><? echo mysql_result(mysql_query("select producto from tinventario where idproducto = '".$ingrediente["idingrediente"]."'"),0)." (".mysql_result(mysql_query("select nombre from tcatcategoriascqsr where idcategoria = '".$ingrediente["idcategoria"]."'"),0).")";?></td>
                    <td align="right"></td>
                    <td align="right"></td>
                    <td></td>
                    <td align="center"></td>
                </tr>
                <?		
                }
			}
			if($platillo["itta"]>0){
            	$platillositta = mysql_query("select * from trcuentamenuittatmp where idittakate = '".$platillo["itta"]."'");
                while($platilloitta = mysql_fetch_assoc($platillositta)){
                ?>
                <tr height="30">
                    <td align="right"></td>
                    <td><? echo mysql_result(mysql_query("select platillo from tplatillosittakate where idplatillo = '".$platilloitta["idplatillo"]."'"),0)." (Categoria ".$platilloitta["idcategoria"].")";?></td>
                    <td align="right"></td>
                    <td align="right"></td>
                    <td></td>
                    <td align="center"></td>
                </tr>
                <?		
                }
			}
		}
    }
    ?>
    </tbody>
</table>
<script>
mostrarTotal('<? echo number_format($total,2);?>','<? echo $num;?>');
</script>