<?php

//Registrar
function tk_register($correo, $pass, $role = 'usuario' ,$usuario = null, $verifica = false, $extra = []){
    global $tekodb, $tekoauth, $token;
    try {
        if($verifica){
            $id = $tekoauth->register($correo, $pass, $usuario, function ($selector, $token) {
                $GLOBALS['token'] = urlencode(base64_encode(json_encode(compact('selector', 'token'))));
            });
        } else {
            $id = $tekoauth->register($correo, $pass, $usuario);
        }
        if(count($extra)){
            $extra['role'] = $role;
            $tekodb->update('users', $extra, compact('id'));
        } else {
            $tekodb->update('users', compact('role'), compact('id'));
        }
        return ($verifica) ? array('error' => FALSE, 'id'=>$id, 'token' => $token) : array('error' => FALSE, 'id' => $id);
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        return array('error' => TRUE, 'tipo' => 'correo', 'mensaje' => 'El correo es incorrecto');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        return array('error' => TRUE, 'tipo' => 'contraseña', 'mensaje' => 'La contraseña es incorrecta');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
        return array('error' => TRUE, 'tipo' => 'usuario_existente', 'mensaje' => 'El usuario ingresao ya existe');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'tipo' => 'intentos_multiples', 'mensaje' => 'Intente más tarde por favor');
    }
    catch (\Delight\Auth\DatabaseException $e) {
        return array('error' => TRUE, 'tipo' => 'db', 'mensaje' => 'Error al insertar en base de datos');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'tipo' => 'desconocido', 'mensaje' => 'Error desconocido', 'e' => $e);
    }
}

//Confirmar Email
function tk_confirm($token){
    global $tekoauth;
    try {
        $partes = json_decode(base64_decode(urldecode($token)));
        $tekoauth->confirmEmail($partes->selector, $partes->token);
        return array('error' => FALSE);
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        return array('error' => TRUE, 'tipo' => 'token_invalido', 'mensaje' => 'El token es inválido');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        return array('error' => TRUE, 'tipo' => 'token_expirado', 'mensaje' => 'El token ha expirado');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'tipo' => 'intentos_multiples', 'mensajes' => 'Intente más tarde por favor');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'tipo' => 'desconocido', 'mensaje' => 'Error desconocido', 'e' => $e);
    }
}

//Contraseña Perdida
function tk_recover($correo){
    global $tekoauth, $token;
    try {
        $token = '';
        $tekoauth->forgotPassword($correo, function ($selector, $token) {
            $GLOBALS['token'] = urlencode(base64_encode(json_encode(compact('selector', 'token'))));
        });
        return array('error' => FALSE, 'token' => $token);
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        return array('error' => TRUE, 'tipo' => 'correo', 'mensaje' => 'El correo es inválido');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'tipo' => 'intentos_multiples', 'mensaje' => 'Intente más tarde por favor');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'tipo' => 'desconocido', 'mensaje' => 'Error desconocido', 'e' => $e);
    }
}

//Resetear Contraseña
function tk_reset_password($token, $contraseña){
    global $tekoauth;
    try {
        $partes = json_decode(base64_decode(urldecode($token)));
        $tekoauth->resetPassword($partes->selector, $partes->token, $contraseña);
        return array('error' => FALSE);
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        return array('error' => TRUE, 'tipo' => 'token_invalido', 'mensaje' => 'El token es inválido');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
        return array('error' => TRUE, 'tipo' => 'token_expirado', 'mensaje' => 'El token ha expirado');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        return array('error' => TRUE, 'tipo' => 'contraseña', 'mensaje' => 'Contraseña inválida');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'tipo' => 'intentos_multiples', 'mensaje' => 'Intente más tarde por favor');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'tipo' => 'desconocido', 'mensaje' => 'Error desconocido', 'e' => $e);
    }
}

//Cambiar contraseña del usuario actual (valida anterior)
function tk_change_current_password($antigua, $nueva){
    global $tekoauth;
    try {
        $tekoauth->changePassword($antigua, $nueva);
        return array('error' => FALSE);
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
        return array('error' => TRUE, 'tipo' => 'sin_ingresar', 'mensaje' => 'No has iniciado sesión');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        return array('error' => TRUE, 'tipo' => 'contraseña', 'mensaje' => 'Contraseña incorrecta');
    }
    catch (Exception $e) {
        return array('error' => TRUE, 'tipo' => 'desconocido', 'mensaje' => 'Error desconocido', 'e' => $e);
    }
}

//Cambiar contraseña de un usuario, sin necesidad de la anterior
function tk_change_password($password, $usuario = FALSE){
    global $tekodb;
    $id = ($usuario) ? $usuario : tk_id();
    $password = password_hash($password, PASSWORD_DEFAULT);
    $res = $tekodb->update('users', compact('password'), compact('id'));
    if($res !== FALSE){
        return array('error' => FALSE, 'mensaje' => 'Contraseña actualizada correctamente');
    } else {
        return array('error' => TRUE, 'mensaje' => 'Ocurrió un error al cambiar la contraseña');
    }
}

//Cambiar role de usuario
function tk_change_role($id, $role){
    global $tekodb;
    $tekodb->update('users', compact('role'), compact('id'));
    return array('error' => FALSE, 'mensaje' => 'El rol ha sido actualizado correctamente');
}

//Ingresar
function tk_login($correo, $pass, $recordar = FALSE){
    global $tekoauth, $tekodb;
	$recordar = ($recordar) ? ((int) (60 * 60 * 24 * 365.25)) : null;
    try {
    	if(filter_var($correo, FILTER_VALIDATE_EMAIL)){
			$tekoauth->login($correo, $pass, $recordar);
		} else {
			$tekoauth->loginWithUsername($correo, $pass, $recordar);
		}
        return array('error' => FALSE, 'id'=>$tekoauth->getUserId());
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
        return array('error' => TRUE, 'tipo' => 'correo', 'mensaje' => 'El correo es incorrecto');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
        return array('error' => TRUE, 'tipo' => 'contraseña', 'mensaje' => 'La contraseña es incorrecta');
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
        return array('error' => TRUE, 'tipo' => 'correo_no_verificado', 'mensaje' => 'El usuario aún no ha sido verificado, revise su bandeja de entrada por favor');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
        return array('error' => TRUE, 'tipo' => 'intentos_multiples', 'mensaje' => 'Intenta más tarde por favor');
    }
	catch(\Delight\Auth\UnknownUsernameException $e) {
		return array('error' => TRUE, 'tipo' => 'usuario_inexistente', 'mensaje' => 'Usuario y/o contraseña erroneos');
	}
    catch (Exception $e) {
        return array('error' => TRUE, 'tipo' => 'desconocido', 'mensaje' => 'Error desconocido', 'nombre' => get_class($e));
    }
}

//Cerrar Sesión
function tk_logout(){
    global $tekoauth;
    $tekoauth->logout();
}

//Obtener id del usuario actual
function tk_id(){
    global $tekoauth;
    if ($tekoauth->isLoggedIn()) {
        return $tekoauth->getUserId();
    }
    else {
        return 0;
    }
}
//Alias
function get_current_user_id(){ return tk_id(); }

//Obtener el row del usuario actual
function tk_current_user(){
    global $tekoauth,$tekodb;
    $id = tk_id();
    return $tekodb->get_row("SELECT * FROM users WHERE id={$id}");
}


//Obtener el rol del usuario actual
function get_current_user_role(){
    global $tekodb;
    return $tekodb->get_var("SELECT role FROM users WHERE id=".tk_id());
}

//Comparar si la contraseña es correcta 
function tk_check_password($password, $id_user = 0){
    global $tekodb;
    $id_user = ($id_user) ? $id_user : tk_id();
    if($id_user){
        $passwordInDatabase = $tekodb->get_var("SELECT password FROM users WHERE id = {$id_user}");
        return password_verify($password, $passwordInDatabase);
    } else {
        return false;
    }
}