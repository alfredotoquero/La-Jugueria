<?
	ini_set("session.gc_maxlifetime","10");
	session_name("gt4e57i6rhdrg");
	session_start();

	function db($conectar){ 
        $link='';
		$bandera = 0;
        if($conectar == "conectar"){
            $link = mysql_connect("db.interface.mx","hdelsushi","sush110") or die($bandera = 0);
			$bandera = 1;
            mysql_select_db("hijosdelsushi",$link) or die($bandera = 0);
			$bandera = 1; 
        } 
        if($conectar == "desconectar"){ 
            $link = mysql_connect("db.interface.mx","hdelsushi","sush110"); 
            mysql_close($link); 
        } 
		return $bandera;
    } 
	
//conexion via internet
$bandera = db('conectar');
if($bandera==1){
	//leemos el numero de mensaje y la ultima vez que se ingreso a mensajes para obtener el numero de mensajes nuevos...
	$mensajesNuevos = mysql_num_rows(mysql_query("select * from tmensajero where destinatario = ".$_SESSION["idsucursal"]." and status = 0"));
	if($mensajesNuevos>0){
		echo "<span style=\"color:#D12B30; font-weight:bold;\">MENSAJES($mensajesNuevos)</span>";
	}else{
		echo "MENSAJES($mensajesNuevos)";
	}
}else if ($bandera == 0){
	echo "SIN CONEXION";
}
db('desconectar');
?>