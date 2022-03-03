<?php
global $tekodb;
$id = !empty($_POST['id']) ? $_POST['id'] : 0;

if($id){
	$familia = $tekodb->get_row("SELECT * FROM customer_journey_map WHERE idCustomer_journey_map= {$id}");
	if(!empty($familia)){
	}else{
		teko_json(array('error' => TRUE, 'mensaje' => _('No se ha encontrado información') ));
	}
}else{
	teko_json(array('error' => TRUE, 'mensaje' => _('No se ha recibido la información para hacer la búsqueda') ));
