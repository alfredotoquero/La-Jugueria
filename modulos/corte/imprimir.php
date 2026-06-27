<?
		ini_set("session.gc_maxlifetime","43200");
		session_name("2q093ex8uq2ewun");
		session_start();
		date_default_timezone_set('America/Los_Angeles');
		include("../../002wf3f3kgdvr/983y4rhouCon.php");
		include("../../002wf3f3kgdvr/983y4rhou.php");
		
		include("../num2letras.php");
		
		$idcorte = $_GET['idcorte'];
		$corte = mysql_fetch_assoc(mysql_query("select * from tcortes where idcorte = $idcorte"));
?>
<html>
<head>
		<style>
		.impresion{
			font:20pt sans-serif;
		}
		.titImpresion{
			font:26pt sans-serif;
		}
		</style>		
		</head>
		<body>
		<table width="600"  cellspacing="0" cellpadding="0" align="center" class="impresion">
		<tr><td align="center" >
        <span class="titImpresion">
        <b>JUGOS SONORA</b>
        </span>
        <br>
		<? 
			echo "AV. GASTELUM NO. 613B<br>".
			"ENSENADA CENTRO C.P. 22800<br>".
			"ENSENADA, B.C.<br>".
			"DIANA ALEJANDRA OLIVARRIA SANDOVAL<br>".
			"OISD 951101 TB3<br>".
			"REGIMEN DE INC0RPORACION FISCAL<br>";
			$idticket = "";
			for($i=strlen($idcorte);$i<7;$i++){
				$idticket .= "0";
			}
			$idticket = $idticket.$idcorte;
			echo date("d/m/Y",strtotime($corte["fechafinal"]))." ".date("H:i:s A",strtotime($corte["horafinal"]))." ".$idticket;
		?>
		</td>
		</tr>
		<tr>
		<td align="center">========================================</td>
		</tr>
		<tr>
		<td align="center">CORTE DE CAJA</td>
		</tr>
		<tr>
		<td align="center">========================================</td>
		</tr>
		<tr>
        	<td align="right">
				<table width="600" cellspacing="0" cellpadding="0" class="impresion">
					<tr>
					  <td>FONDO FINAL</td><td align="right">$<? echo number_format($corte['fondofinal'],2); ?></td>
                    </tr>
					<tr>
				    <td>DESGLOSE:</td><td align="right">&nbsp;</td></tr>
                    <tr>
                      <td>FONDO INICIAL (MXN)</td><td align="right">$<? echo number_format($corte['fondoinicial'],2); ?></td>
                    </tr>
                    <?
					if($corte['ventas']>0){
						?>
						<tr>
						  <td>EFECTIVO (MXN)</td><td align="right">$<? echo number_format($corte['ventas'],2); ?></td>
                        </tr>
						<?
                    }
					?>
                    <tr>
                        <td>&nbsp;</td><td align="right">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>TOTAL DE GASTOS</td><td align="right">$<? echo number_format($corte['gastos'],2); ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td align="right">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>FOLIO INICIAL DEL CORTE</td>
                      <td align="right"><? echo $corte['folioinicial']; ?></td>
                    </tr>
                    <tr>
                      <td>FOLIO FINAL DEL CORTE</td>
                      <td align="right"><? echo $corte['foliofinal']; ?></td>
                    </tr>
                    </table>
        
		</td></tr>
		<tr><td align="center"><? echo strtoupper(num2letras(number_format($corte['fondofinal'],2,'.',''))); ?></td></tr>
		<tr>
		  <td align="center">========================================</td></tr>
		<tr>
		  <td height="200" align="center" valign="top">FIRMAS</td></tr>
		<tr>
		  <td align="center">========================================</td></tr>
		<tr>
		  <td align="center"><b>CORTE DE CAJA</b></td></tr>
</table> 
		</body>
<script>
window.print();
parent.location.href='../../salir.php';
parent.$.fancybox.close();
</script>
</html>