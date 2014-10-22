<?php
require '../../source/class/class_core.php';
require 'response.php';
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
		
		$sql = "SELECT * FROM pre_common_member_profile where uid='" . $token . "'";
		$prifile = DB::fetch_first ( $sql );
		
		$infodata = array (
				"residedist" => $prifile ['residedist'],
				"floor" => $prifile ['field1'],
				"username" => $member ['username'],
				"nickname" => $prifile ['field3'],
				"gender" => $prifile ['gender'],
				"occupation" => $prifile ['occupation'],
				"room" => $prifile ['field2'],
				"avatar_url" => "http://'. SERVER_ADDRESS. '/uc_server/avatar.php?uid=" . $token . "&size=middle"
		);
		
		responseSingleData ( $infodata );
	}
}

?>
