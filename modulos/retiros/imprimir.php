<?
		ini_set("session.gc_maxlifetime","43200");
		session_name("2q093ex8uq2ewun");
		session_start();
		date_default_timezone_set('America/Los_Angeles');
		include("../../002wf3f3kgdvr/983y4rhouCon.php");
		include("../../002wf3f3kgdvr/983y4rhou.php");
		
		include("../num2letras.php");
		
		$idretiro = $_GET['idretiro'];
		$retiro = mysql_fetch_assoc(mysql_query("select * from tretiros where idretiro = $idretiro"));
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
			echo "AV. GASTELUM NO. 1598<br>".
			"ZONA CENTRO C.P. 22800<br>".
			"ENSENADA, B.C.<br>".
			"MARIO ERASMO OLIVARRIA ALVAREZ<br>".
			"OIAM 710119 JL1<br>".
			"REGIMEN DE INC0RPORACION FISCAL<br>";
			$idticket = "";
			for($i=strlen($idretiro);$i<7;$i++){
				$idticket .= "0";
			}
			$idticket = $idticket.$idcorte;
			echo date("d/m/Y")." ".date("H:i:s A")." ".$idticket;
		?>
		</td>
		</tr>
		<tr>
		<td align="center">========================================</td>
		</tr>
		<tr>
		<td align="center">RETIRO DE EFECTIVO</td>
		</tr>
		<tr>
		<td align="center">========================================</td>
		</tr>
		<tr>
        	<td align="right">
				<table width="600" cellspacing="0" cellpadding="0" class="impresion">
					<tr>
					  <td>RETIRO DE EFECTIVO:</td>
					  <td align="right">$<? echo number_format($retiro['monto'],2); ?></td>
                    </tr>
					<tr>
				    <td colspan="2">DESCRIPCION:<br /><? echo strtoupper($retiro['descripcion']); ?></td></tr>
				<tr>
					  <td>FECHA Y HORA:<br /><? echo $retiro['fecha']." A LAS ".$retiro['hora']; ?></td>
				  </tr>
                    </table>
        
		</td></tr>
		<tr>
		  <td align="center">========================================</td></tr>
		<tr>
		  <td height="200" align="center" valign="top">FIRMAS</td></tr>
		<tr>
		  <td align="center">========================================</td></tr>
		<tr>
		  <td align="center"><b>RETIRO DE EFECTIVO</b></td></tr>
</table> 
</body></html>
<script>
window.print();
parent.$.fancybox.close();
</script>