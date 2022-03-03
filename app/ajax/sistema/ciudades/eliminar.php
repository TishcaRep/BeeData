<?php
global $tekodb;
$id = !empty($_POST['id']) ? $_POST['id'] : 0;
$res = $tekodb->delete('catalogo_ciudad', ['idCatalogoCiudad' => $id]);
if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el enfoque')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El enfoque se ha editar correctamente'), 'url' => site_url('dashboard/catalogos/ciudades')]);
}
