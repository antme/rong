<?php
define ( 'UC_API', 'http://localhost/uc_server' );
require 'config.inc.php';
require '../../uc_client/client.php';
require 'response.php';

$userName = $_REQUEST ["userName"];
$password = $_REQUEST ["password"];

if (empty ( $userName )) {
	responseError ( CODE_PARAMETER_EMPTY, "用户名不能为空" );
} 

else if (empty ( $password )) {
	responseError ( CODE_PARAMETER_EMPTY, "密码不能为空" );
} else {
	
	$loginResult = uc_user_login ( $userName, $password );	
	
	if($loginResult[0] < 0){
		responseError ( USERNAME_OR_PASSWORD_ERROR, "密码不能为空" );
	}else{
		$data = array();
		$data['id'] = $loginResult[0];
		responseSingleData($data, $total);
	}
}

?>
