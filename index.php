<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/otros/sesion.php");

if(isset($_SESSION["idusrx3209exum0q3em"])){
    header("location: admin.php");
}

$sucursal = isset($_GET["sucursal"]) ? strtolower($_GET["sucursal"]) : "";
$sucursalValida = preg_match("/^[a-z0-9-]+$/", $sucursal) === 1;

unset($_SESSION["authToken"]);
$_SESSION["authToken"] = sha1(uniqid(microtime(), true));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>La Juguería</title>
<link href="/assets/css/style.css" rel="stylesheet" type="text/css" />
<link href="/assets/css/sweetalert2.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($sucursalValida): ?>
<div id="divLogin">
  <form id="formLogin" name="formLogin">
    <input type="hidden" id="controlador" name="controlador" value="usuarios" />
    <input type="hidden" name="accion" value="login" />
    <input type="hidden" id="href" name="href" value="admin.php" />
    <input type="hidden" name="authToken" value="<?php echo $_SESSION["authToken"]; ?>" />
    <input type="hidden" name="sucursal" value="<?php echo htmlspecialchars($sucursal); ?>" />
    <table width="314" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="190">&nbsp;</td>
      </tr>
      <tr>
        <td height="40" align="center" valign="middle">
          <div id="divCampoLogin">
            <input type="text" name="txtUsuario" id="txtUsuario" class="txtLogin requerido" data-mensajeerror="Debes indicar un usuario" />
          </div>
        </td>
      </tr>
      <tr>
        <td height="15" align="center"></td>
      </tr>
      <tr>
        <td height="40" align="center" valign="middle">
          <div id="divCampoLogin">
            <input type="password" name="txtPassword" id="txtPassword" class="txtLogin requerido" data-mensajeerror="Debes indicar una contraseña" />
          </div>
        </td>
      </tr>
      <tr>
        <td height="15"></td>
      </tr>
      <tr>
        <td height="48" align="center">
          <div onclick="validarFormulario('formLogin')" id="btnEntrar" style="cursor:pointer;">
            <img src="/assets/images/btnEntrar.png" alt="" width="271" height="48" />
          </div>
        </td>
      </tr>
      <tr>
        <td height="15"></td>
      </tr>
    </table>
  </form>
</div>
<?php else: ?>
<div id="divLogin">
  <p style="color:#fff; text-align:center; padding-top:170px; font-family:Arial, Helvetica, sans-serif;">
    Ingresa al sistema utilizando la URL de tu sucursal.
  </p>
</div>
<?php endif; ?>

<script src="/assets/js/jquery.js"></script>
<script src="/assets/js/sweetalert2.min.js"></script>
<script src="/assets/js/funciones.js"></script>
</body>
</html>
