<?php
global $tekodb;
$uns = null;
$id_usuario  = tk_id();
$uns = $tekodb->get_results("SELECT * FROM vunidad_negocio WHERE idU = {$id_usuario} AND tipo = 'Principal'");
if(empty($uns)){
	teko_json(['error' => FALSE]);
} else {
  teko_json(['error' => FALSE,'datos' => $uns]);
}
