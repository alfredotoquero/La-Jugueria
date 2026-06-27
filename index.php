<?
	$fondoInicial = 1000;
	if($_POST['iniciar']==1){
		include("002wf3f3kgdvr/983y4rhouCon.php");
		$usuario=$_POST['txtUsuario'];
		$password=$_POST['txtPassword'];
		$usr=mysql_num_rows(mysql_query("select * from tusuarios where usuario='$usuario' and password='$password' and status='A' limit 1",$con));
		if($usr==1){

				$validaCorte = mysql_num_rows(mysql_query("select * from tcortes where status = 0",$con));
				$idusuario = mysql_result(mysql_query("select idusuario from tusuarios where usuario='$usuario' and password='$password' and status='A' limit 1",$con),0);

				if($validaCorte==0){
					
					ini_set("session.gc_maxlifetime","43200");
					session_name("2q093ex8uq2ewun");
					session_start();
					date_default_timezone_set('America/Los_Angeles');
					
					$_SESSION["idusrx3209exum0q3em"]= $idusuario;
					$_SESSION["456udhsere"] = date("Y-n-j H:i:s");
					
					$fecha = date('Y-m-d');
					$hora = date('H:i:s');
					mysql_query("insert into tcortes (idcorte,idusuario,fechainicio,horainicio,fondoinicial) values (NULL,'$idusuario','$fecha','$hora','$fondoInicial')");
					header("location: admin.php");
					
				}else{
					$validaCajero = mysql_num_rows(mysql_query("select * from tcortes where idusuario = $idusuario and status = 0",$con));
					if($validaCajero==1){
						
						ini_set("session.gc_maxlifetime","43200");
						session_name("2q093ex8uq2ewun");
						session_start();
						
						$_SESSION["idusrx3209exum0q3em"]= $idusuario;
						$_SESSION["456udhsere"] = date("Y-n-j H:i:s");
					
						header("location: admin.php");
					}else{
						$id_alert="errorCajero";
					}
				}
		}else{
			$id_alert="error";
		}
	}else{
		ini_set("session.gc_maxlifetime","43200");  
		session_name("2q093ex8uq2ewun");
		session_start();
		include("002wf3f3kgdvr/983y4rhouCon.php");
		if($_SESSION["idusrx3209exum0q3em"]!=NULL){
			header("location: admin.php");
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Jugos Sonora</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="divLogin">
  <form id="formLogin" name="formLogin" method="post" action="">
  <input type="hidden" name="iniciar" value="1" />
    <table width="314" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="190">&nbsp;</td>
      </tr>
      <tr>
        <td height="40" align="center" valign="middle">
        <div id="divCampoLogin">
        <input type="text" name="txtUsuario" id="txtUsuario" class="txtLogin" />
      	</div>
        </td>
      </tr>
      <tr>
        <td height="15" align="center"></td>
      </tr>
      <tr>
        <td height="40" align="center" valign="middle"><div id="divCampoLogin"><input type="password" name="txtPassword" id="txtPassword" class="txtLogin"/></div></td>
      </tr>
      <tr>
        <td height="15"></td>
      </tr>
      <tr>
        <td height="48" align="center"><div onclick="document.getElementById('formLogin').submit()" id="btnEntrar" style="cursor:pointer;"><img src="images/btnEntrar.png" alt="" width="271" height="48" /></div></td>
      </tr>
      <tr>
        <td height="15"></td>
      </tr>
    </table>
  </form>
</div>
<?
	if($id_alert=="error"){
		echo "<script>alert('Usuario o Password incorrectos, por favor vuelta a intentar.');</script>";
	}
	if($id_alert=="errorCajero"){
		echo "<script>alert('Existe un corte iniciado por otro cajero.');</script>";
	}
?>
</body>
</html>