<?php
global $tekodb;

if(empty($_POST['id']))
{
	return teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}

$data = $tekodb->get_row("SELECT * FROM sucursales WHERE fkidUnidadNegocio = {$_POST['id']}");
if (!empty($data)) {
	$idNewS = $data->fkidSucursal;
	$dell = $tekodb->delete('facturacion', ['fkidUnidadNegocio' => $_POST['id']]);
	$res = $tekodb->delete('sucursales',['fkidUnidadNegocio' => $_POST['id'],'fkidSucursal'=>$idNewS]);
	$res = $tekodb->update('sucursales',['fkidUnidadNegocio'=>$idNewS],['fkidUnidadNegocio' => $_POST['id']]);
}
$dell = $tekodb->delete('facturacion', ['fkidUnidadNegocio' => $_POST['id']]);
$dell = $tekodb->delete('administrador_unidad_negocio', ['fkidUnidadNegocio' => $_POST['id']]);
$res = $tekodb->delete('sucursales', ['fkidSucursal' => $_POST['id']]);
$dell = $tekodb->delete('unidad_negocio', ['idUnidadNegocio' => $_POST['id']]);


if(empty($dell)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar la unidad de negocio')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La unidad de negocio se ha eliminado correctamente')]);
}
