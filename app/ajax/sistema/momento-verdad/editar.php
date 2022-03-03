<?php
global $tekodb;
if(empty($_POST['datos']))
{
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el momento verdad')]);
}
$temp = $_POST['datos'];
$data = [
	'Momento_verdad' => $temp['momento'],
	'fkidGirosEmpresariales' => $temp['idG'],
	'fkidCoustomer_journey_map' => $temp['idcjm']
];
$res = $tekodb->update('momento_verdad',$data,['idMomento_verdad' => $_POST['id']]);
if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el momento verdad')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El momento verdad se ha editado correctamente')]);
}
