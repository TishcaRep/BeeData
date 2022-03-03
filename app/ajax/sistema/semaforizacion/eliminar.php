<?php
global $tekodb;

$id = !empty($_POST['id']) ? $_POST['id'] : 0;

$res = $tekodb->delete('semaforizacion', ['idSemaforo' => $id]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar la semaforizacion')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La semaforización se ha eliminado correctamente')]);
}
