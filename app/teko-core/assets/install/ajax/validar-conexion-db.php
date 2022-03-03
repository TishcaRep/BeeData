<?php

$tekoconfig = $_POST;

$db_user = $tekoconfig['db']['user'];
$db_pass = $tekoconfig['db']['pass'];
$db_name = $tekoconfig['db']['name'];
$db_host = $tekoconfig['db']['host'];

try {
    $pdo = new PDO("mysql:dbname={$db_name};host={$db_host};charset=utf8", $db_user, $db_pass);
    teko_json(['error' => FALSE, 'mensaje' => 'La conexión con la base de datos se realizó correctamente']);
} catch (Exception $ex){
    teko_json(['error' => TRUE, 'mensaje' => 'Ocurrió un error al conectarse con la base de datos']);
}


function teko_json($arr){
    header('Content-Type: application/json');
    die(json_encode($arr));
}