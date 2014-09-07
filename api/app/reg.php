<?php
define ( 'UC_API', 'http://localhost/uc_server1' );
require_once '../../source/class/class_core.php';
require_once 'config.inc.php';
require_once '../../uc_client/client.php';
require_once 'response.php';
C::app ()->init ();

$mobile = $_REQUEST ["mobile"];
$regcode = $_REQUEST ["regcode"];
$password = $_REQUEST ["password"];

/*
 * else if (empty ( $password )) {
	responseError ( CODE_PARAMETER_EMPTY, "密码不能为空" );
}
 */

$password = 'abc123_';
$valid = true;
if (empty ( $mobile )) {
	responseError ( CODE_PARAMETER_EMPTY, "手机号不能为空" );
} else if (empty ( $regcode )) {
	responseError ( CODE_PARAMETER_EMPTY, "验证码不能为空" );
}  else {
	
	$sql = "SELECT * FROM pre_common_member_profile where mobile='" . $mobile . "'";
	$prifile = DB::fetch_all ( $sql );
	
	if (count ( $profile ) == 0) {
		
		$sql = "SELECT * FROM pre_rong_reg_code where mobile='" . $mobile . "' and regcode='" . $regcode . "'";
		
		$result = DB::fetch_all ( $sql );
		
		if (count ( $result ) == 1) {
			$uid = uc_user_register ( $mobile, $password, $mobile . "@rong.com" );
			
			if ($uid == - 3) {
				responseError ( USER_REG_EXISTS, "此用户已经存在" );
			} else {
				$rdata = array (
						'id' => $uid,
						'token' =>  $uid
				);
				
				$user = array ();
				$user ['mobile'] = $mobile;
				$user ['uid'] = $uid;
				
				DB::insert ( "common_member_profile", $user );
				
				responseSingleData ( $rdata );
			}
		} else {
			responseError ( REG_CODE_INVALID, "验证码不正确" );
		}
	} else {
		responseError ( USER_REG_EXISTS, "此用户已经存在" );
	}
}

?>
