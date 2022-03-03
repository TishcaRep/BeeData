<?php
global $tekodb;

$id = !empty($_POST['id']) ? $_POST['id'] : 0;

$res = $tekodb->delete('respuestas', ['fkidGrupoRespuestas' => $id]);
$res = $tekodb->delete('grupo_respuestas', ['idGrupoRespuestas' => $id]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar el grupo de respuestas')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El grupo de respuestas se ha eliminado')]);
}
