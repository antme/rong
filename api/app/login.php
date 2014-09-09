<?php
define ( 'UC_API', 'http://localhost/uc_server1' );
require_once '../../source/class/class_core.php';
require_once 'config.inc.php';
require_once '../../uc_client/client.php';
require_once 'response.php';
C::app ()->init ();

$mobile = $_REQUEST ["mobile"];
$regcode = $_REQUEST ["regcode"];

$valid = true;
if (empty ( $mobile )) {
	responseError ( CODE_PARAMETER_EMPTY, "手机号不能为空" );
} else if (empty ( $regcode )) {
	responseError ( CODE_PARAMETER_EMPTY, "验证码不能为空" );
} else {
	
	$sql = "SELECT * FROM pre_common_member_profile where mobile='" . $mobile . "'";
	$prifile = DB::fetch_first ( $sql );
	if (empty ( $prifile ['uid'] )) {
		echo $prifile ['uid'];
		
		responseError ( USER_REG_EXISTS, "此用户不存在" );
	} else {
		
		$sql = "SELECT * FROM pre_rong_reg_code where mobile='" . $mobile . "' and regcode='" . $regcode . "'";
		$result = DB::fetch_first ( $sql );
		
		if (empty ( $result )) {
			responseError ( REG_CODE_INVALID, "验证码不正确" );
		} else {
			$uid = $prifile ['uid'];
			
			$usql = "SELECT * from pre_common_member WHERE uid='" . $uid . "'";
			$user = DB::fetch_first ( $usql );
			
			
			$psql = "SELECT * from pre_common_member_profile WHERE uid='" . $uid . "'";
			$puser = DB::fetch_first ( $psql );
			
			
			$rdata = array (
					'id' => $uid,
					'username' => $user ['username'],
					'nickname' => $puser ['field3'],
					'token' => $uid 
			);
			responseSingleData ( $rdata );
		}
	}
}

?>
