<?php
global $tekodb;
$data = $_POST['datos'];
$res = $tekodb->update('semaforizacion',$data,['idSemaforo' => $_POST['id']]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar la membresia')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El semaforo se ha actualizado correctamente')]);
}
