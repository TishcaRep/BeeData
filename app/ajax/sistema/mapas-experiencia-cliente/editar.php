<?php
global $tekodb;
if(empty($_POST['datos']))
{
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el mapa de experiencia de usuario')]);
}
$temp = $_POST['datos'];
$data = [
	'Nombre' => $temp['Nombre'],
	'fkidGirosEmpresariales' => $temp['idgiro']

];
$res = $tekodb->update('customer_journey_map',$data,['idCustomer_journey_map' => $_POST['id']]);
if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el mapa de experiencia de usuario')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El mapa de experiencia de usuario verdad se ha editado correctamente')]);
}
