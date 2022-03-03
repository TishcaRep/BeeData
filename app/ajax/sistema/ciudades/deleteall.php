<?php
global $tekodb;
$Delete = !empty($_POST['Delete']) ? $_POST['Delete'] : 0;
if ($Delete == TRUE) {
  $res = $tekodb->query('truncate catalogo_ciudad');
  if($res === FALSE){
  	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar las ciudades')]);
  } else {
  	teko_json(['error' => FALSE, 'mensaje' => _('las ciudades se eliminaron correctamente'), 'url' => site_url('dashboard/catalogos/ciudades')]);
  }
}
else {
  teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al eliminar las ciudades'),'err' => $res]);
}
