<?php
global $tekodb;
$data = $_POST['datos'];
$res = $tekodb->update('planes_membresia',$data,['idPlanesMembresia' => $_POST['id']]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar la membresia')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La membresia se ha editado correctamente'), 'url' => site_url('dashboard/catalogos/enfoques')]);
}
