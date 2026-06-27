<?
date_default_timezone_set('America/Los_Angeles');
if($_SESSION["idusrx3209exum0q3em"]!=NULL){
    //sino, calculamos el tiempo transcurrido
    $fechaGuardada = $_SESSION["456udhsere"];
    $ahora = date("Y-n-j H:i:s");
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
    //comparamos el tiempo transcurrido
   	if($tiempo_transcurrido >= 43200) {
		//si pasaron 10 minutos o más
		unset($_SESSION["idusrx3209exum0q3em"]);
		unset($_SESSION["456udhsere"]);
		session_destroy(); // destruyo la sesión
		//echo "ERROR";
    }else {
    	$_SESSION["456udhsere"] = $ahora;
    }
}else{
	header("location: index.php");
}
?>