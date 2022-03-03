<?php
global $tekodb;
$data = $_POST['datos'];
$res = $tekodb->update('catalogo_ciudad',$data,['idCatalogoCiudad' => $_POST['id']]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar la ciudad'));
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La ciudad se ha editado correctamente'), 'url' => site_url('dashboard/catalogos/ciudades')]);
}
