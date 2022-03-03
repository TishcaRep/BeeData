<?php
global $tekodb;

$id_giro = !empty($_POST['id']) ? $_POST['id'] : 0;

$res = $tekodb->get_row("SELECT * FROM momento_verdad WHERE fkidGirosEmpresariales = {$id_giro}");
if(empty($res))
{
	$res = $tekodb->delete('giros_empresariales', ['idGirosEmpresariales' => $id_giro]);
	if($res === FALSE){
		teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el giro empresarial')]);
	} else {
		teko_json(['error' => FALSE, 'mensaje' => _('El giro empresarial se ha editar correctamente'), 'url' => site_url('dashboard/enfoques')]);
	}
}
else {
	teko_json(['error' => TRUE, 'mensaje' => _('Este elemento tiene vinculos, no se puede eliminar')]);
}
