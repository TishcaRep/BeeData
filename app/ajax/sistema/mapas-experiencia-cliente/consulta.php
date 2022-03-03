<?php
global $tekodb;
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$giro = $tekodb->get_row("SELECT idCustomer_journey_map as id,Nombre, fkidGirosEmpresariales as idG FROM customer_journey_map WHERE idCustomer_journey_map = {$_POST['id']}");
if(empty($giro)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar el momento')]);
} else {
	teko_json(['error' => FALSE, 'data' => $giro]);
}
