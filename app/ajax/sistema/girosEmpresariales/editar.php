<?php
global $tekodb;
$data = $_POST['datos'];
$res = $tekodb->update('giros_empresariales',$data,['idGirosEmpresariales' => $_POST['id']]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el giro empresarial')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El giro empresarial se ha eliminado correctamente'), 'url' => site_url('dashboard/catalogos/giroempresarial')]);
}
