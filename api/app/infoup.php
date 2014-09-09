<?php
define ( 'UC_API', 'http://localhost/uc_server1' );
require_once '../../source/class/class_core.php';
require_once 'config.inc.php';
require_once '../../uc_client/client.php';
require_once 'response.php';
C::app ()->init ();

$token = $_REQUEST ["token"];

$valid = true;
if (empty ( $token )) {
	responseError ( CODE_PARAMETER_EMPTY, "token不能为空" );
} else {
	
	$sql = "SELECT * FROM pre_common_member where uid='" . $token . "'";
	$member = DB::fetch_first ( $sql );
	
	if (empty ( $member )) {
		responseError ( USER_TOKEN_INVALID, "此token不存在或者已经失效" );
	} else {
		$realname = $_REQUEST ["realname"];
		$gender = $_REQUEST ["gender"];
		$occupation = $_REQUEST ["occupation"];
		
		if (! empty ( $gender )) {
			if ($gender != 0 && $gender != 1 && $gender != 2) {
				$gender = 0;
			}
		}
		
		if ($realname || $gender || $occupation) {
			$data = array (
					"realname" => $realname,
					"gender" => $gender,
					"occupation" => $occupation 
			);
			
			DB::update ( "common_member_profile", $data, array (
					"uid" => $token 
			) );
		}
		
		$residedist = $_REQUEST ["residedist"];
		$field1 = $_REQUEST ["floor"];
		$field2 = $_REQUEST ["room"];
		
		if ($residedist || $field1 || $field2) {
			$data = array (
					"residedist" => $residedist,
					"field1" => $field1,
					"field2" => $field2 
			);
			
			DB::update ( "common_member_profile", $data, array (
					"uid" => $token 
			) );
		}
		
		$rdata = array(
			'token' => $token
		);
		
		responseSingleData ( $rdata );
	}
}

?>
