<?php
global $tekodb;

$id = !empty($_POST['id']) ? $_POST['id'] : 0;

$res = $tekodb->delete('customer_journey_map', ['idCustomer_journey_map' => $id]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el momento verdad')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El enfoque se ha editar momento verdad')]);
}
