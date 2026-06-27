<?
if($_SESSION["usrwlejd2oierewfj23jndp"]!=NULL){
    //sino, calculamos el tiempo transcurrido
    $fechaGuardada = $_SESSION["3eewrgwriwe0"];
    $ahora = date("Y-n-j H:i:s");
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
    //comparamos el tiempo transcurrido
   	if($tiempo_transcurrido >= 43200) {
		//si pasaron 10 minutos o más
		unset($_SESSION["usrwlejd2oierewfj23jndp"]);
		unset($_SESSION["3eewrgwriwe0"]);
		session_destroy(); // destruyo la sesión
		//echo "ERROR";
    }else {
    	$_SESSION["3eewrgwriwe0"] = $ahora;
    }
}else{
	header("location: index.php");
}
?>