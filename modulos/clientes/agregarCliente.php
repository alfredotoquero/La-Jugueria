<? 
	include("../../002wf3f3kgdvr/983y4rhouConRem.php");
	if($_POST['enviar']==1){
		$nombre = $_POST['txtNombre'];
		$correo = $_POST['txtCorreo'];
		$telefono = $_POST['txtTelefono'];
		$direccion = $_POST['txtDireccion'];
		$referencia = $_POST['txtReferencia'];
		$lat = $_POST['txtLat'];
		$lng = $_POST['txtLng'];
		mysql_query("insert into tclientes values ('NULL','$nombre','$correo','$telefono','$direccion','$referencia','A','$lat','$lng')");
		echo "<script>parent.fancy(600,410,'modulos/clientes.php');</script>";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Menu</title>
<script src="../../js/jquery.js"></script>
<script>

function manejarEventos(evento){
	var code = (evento.keyCode ? evento.keyCode : evento.which);
	if(code==112 || code==113){
		evento.preventDefault();
	}
	if(code==27){
		parent.$.fancybox.close();
	}
	if(code==113){
		document.getElementById('formNuevoCliente').submit();
	}
}

$(document).ready(function(){
	$(document).keydown(manejarEventos);
	document.getElementById('txtNombre').focus();
});

function enviarForm(){
	var nombre = document.getElementById('txtNombre').value;
	var telefono = document.getElementById('txtTelefono').value;
	var direccion = document.getElementById('txtDireccion').value;
	if(nombre==""){
		alert("ATENCION!: debe introducir NOMBRE del cliente");
		document.getElementById('txtNombre').focus();
		return(false);
	}
	if(telefono==""){
		alert("ATENCION!: debe introducir TELEFONO del cliente.");
		document.getElementById('txtTelefono').focus();
		return(false);
	}
	if(direccion==""){
		alert("ATENCION!: debe introducir DIRECCION del cliente");
		return(false);
	}
	document.getElementById('formNuevoCliente').submit();
}

</script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"> </script>
<script type="text/javascript">
var map;
var markersArray = [];
var geocoder;
function initialize() {
	geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(31.85962589634875, -116.60631164550784);
    var myOptions = {
    	zoom: 17,
      	center: latlng,
	  	disableDefaultUI: true,
      	mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
	//aqui estabas declarando una nueva variable, tiene que ser una "global" para que trabaje con las demas funciones
	map = new google.maps.Map(document.getElementById("divMapa"),myOptions);
	
	google.maps.event.addListener(map,'click',function(event){
		clearOverlays();
		var marker = new google.maps.Marker({
			position: event.latLng,
			map: map,
			draggable: false,
		});
		map.panTo(event.latLng);
		document.getElementById("txtLat").value = event.latLng.lat();
		document.getElementById("txtLng").value = event.latLng.lng();
		markersArray.push(marker);
	});  
}

	function codeAddress() {
    	var address = document.getElementById('txtDireccion').value;
        geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				//faltaba esta linea
				clearOverlays();
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
				document.getElementById("txtLat").value = results[0].geometry.location.lat();
				document.getElementById("txtLng").value = results[0].geometry.location.lng();	
				//faltaba esta linea
				markersArray.push(marker);
			} else {
				alert('AVISO!: NO SE HA ENCONTRADO LA DIRECCION EN EL MAPA.');
			}
    	});
	}

function clearOverlays() {
	if (markersArray) {
		for (i in markersArray) {
			markersArray[i].setMap(null);
		}
		markersArray.length = 0;
	}
}

</script>
<style>
body{
	margin:0px;
}
.campo{
	border:0px;
	outline:0px;
	background:none;
}
</style>
</head>

<body onload="initialize();">
<form id="formNuevoCliente" name="formNuevoCliente" method="post" action="">
<input type="hidden" name="txtLat" id="txtLat" value="" />
<input type="hidden" name="txtLng" id="txtLng" value="" />
<input type="hidden" name="enviar" value="1" />
<div style="position:relative;" id="div">
  <div style="position:absolute; width:700px; height:530px; top:0px; left:0px;"><!--<img src="../images/clientes.jpg" width="600" height="410" border="0" usemap="#Map" />--><img src="../../images/cliente.jpg" width="700" height="530" border="0" usemap="#Map" />
    <map name="Map" id="Map">
      <area shape="rect" coords="14,471,225,511" href="javascript:;" onclick="enviarForm();" />
      <area shape="rect" coords="244,470,455,512" href="#" />
      <area shape="rect" coords="472,472,682,512" href="#" />
    </map>
  </div>
  <div style="position: absolute; width: 330px; height: 32px; top: 84px; left: 16px; z-index: 2;">
    <input type="text" name="txtNombre" id="txtNombre" class="campo" style="width: 330px; height: 32px;" />
  </div>
  <div style="position: absolute; width: 330px; height: 32px; top: 143px; left: 16px; z-index: 2;">
    <input type="text" name="txtCorreo" id="txtCorreo" class="campo" style="width: 330px; height: 32px;" />
  </div>
  <div style="position: absolute; width: 330px; height: 32px; top: 201px; left: 15px; z-index: 2;">
    <input type="text" name="txtTelefono" id="txtTelefono" class="campo" style="width: 330px; height: 32px;" />
  </div>
<div style="position: absolute; width: 330px; height: 32px; top: 259px; left: 16px; z-index: 2;">
    <input type="text" name="txtDireccion" id="txtDireccion" class="campo" style="width: 330px; height: 32px;" onblur="codeAddress();" />
  </div>
   <div style="position: absolute; width: 335px; height: 130px; top: 325px; left: 14px; z-index: 2;">
     <textarea name="txtReferencia" rows="5" class="campo" id="txtReferencia" style="width: 330px; height: 130px;"></textarea>
   </div>
   <div style="position: absolute; width: 314px; height: 388px; top: 68px; left: 368px; z-index: 2; overflow: auto;" id="divMapa">
   </div>
</div>
</form>
</body>
</html>