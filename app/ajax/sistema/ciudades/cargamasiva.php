<?php
global $tekodb;
require_once(APPPATH.'/lib/uploader/class.upload.php');
require_once(APPPATH.'/lib/simplexlsx/SimpleXLSX.php');
if (empty($_FILES['archivo'])) {
  teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$archivo = $_FILES['archivo'];
$path = ABSPATH . '/archivos';
$handle = new upload($archivo, 'es_ES');
//$handle->allowed = array('image/*');
$handle->file_new_name_body = 'ciudades';
$handle->file_overwrite = true;
if ($handle->uploaded) {
	$handle->process($path);
	if($handle->processed){
		$fecha = date('Y-m-d H:i:s');
		$handle->clean();
    $filename = $path.'/'.$handle->file_dst_name;
    $xlsx = SimpleXLSX::parse($filename);
    $res = $tekodb->query('truncate catalogo_ciudad;');
    if($xlsx){
      foreach ($xlsx->rows() as $i => $row) {
        if ($i==0) {
          continue;
        }
        else {
          if (empty($row[0])) {
            break;
          }
          $res = $tekodb->insert(
          	'catalogo_ciudad',
          	array(
          		'Estado' => $row[4],
              'Municipio' => $row[3],
              'Colonia' => $row[1],
              'CodigoPostal' => $row[0],
          	)
          );
        }
      }
			teko_json(['error' => FALSE, 'mensaje' => _('Los datos se han cargado correctamente'), 'url' => site_url('dashboard/catalogos/ciudades'),'array' =>$xlsx->rows()]);
		} else {
			teko_json(['error' => TRUE, 'mensaje' => SimpleXLSX::parseError() ]);
		}
	} else {
		teko_json(['error' => TRUE, 'handle' => $handle->error, 'mensaje' => _('Ocurrió un problema al cargar el archivo')]);
	}
} else {
	teko_json(['error' => TRUE, 'handle' => $handle->error, 'mensaje' => _('Ocurrió un problema al cargar el archivo')]);
}
