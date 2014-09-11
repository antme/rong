<?php
define ( 'UC_API', 'http://localhost/uc_server1' );
require_once '../../source/class/class_core.php';
require_once 'config.inc.php';
require_once '../../uc_client/client.php';
require_once 'response.php';
C::app ()->init ();

$mobile = $_REQUEST ["mobile"];
$regcode = $_REQUEST ["regcode"];
if (empty ( $mobile )) {
	responseError ( CODE_PARAMETER_EMPTY, "手机号不能为空" );
} else if (empty ( $regcode )) {
	responseError ( CODE_PARAMETER_EMPTY, "验证码不能为空" );
} else {
	
	$sql = "SELECT * FROM pre_rong_reg_code where mobile='" . $mobile . "' and regcode='" . $regcode . "'";
	$result = DB::fetch_first ( $sql );
	
	if (empty ( $result )) {
		responseError ( REG_CODE_INVALID, "验证码不正确" );
	} else {
		
		$sql = "SELECT * FROM pre_common_member_profile where mobile='" . $mobile . "'";
		$prifile = DB::fetch_first ( $sql );
		
		$usql = "SELECT * FROM pre_common_member where username='" . $mobile . "'";
		$user = DB::fetch_first ( $usql );
		
		if ($prifile ['uid'] || $user ['uid']) {
			
			$uid = $user ['uid'];
			if ($prifile ['uid'] && empty ( $uid )) {
				$uid = $prifile ['uid'];
			}
			login ( $mobile, $regcode, $uid );
		} else {
			
			reg ( $mobile, $regcode );
		}
	}
}
function login($mobile, $regcode, $uid) {
	$usql = "SELECT * from pre_common_member WHERE uid='" . $uid . "'";
	$user = DB::fetch_first ( $usql );
	
	$psql = "SELECT * from pre_common_member_profile WHERE uid='" . $uid . "'";
	$puser = DB::fetch_first ( $psql );
	
	$rdata = array (
			'id' => $uid,
			'username' => $user ['username'],
			'nickname' => $puser ['field3'],
			'token' => $uid,
			'action' => "login" 
	);
	responseSingleData ( $rdata );
}
function reg($mobile, $regCode) {
	$password = $_REQUEST ["password"];
	
	if (empty ( $password )) {
		$password = $mobile;
	}
	
	$uid = uc_user_register ( $mobile, $password, $mobile . "@rong.com" );
	
	if ($uid == - 3) {
		responseError ( USER_REG_EXISTS, "此用户已经存在" );
	} else {
		
		$user = array ();
		$user ['mobile'] = $mobile;
		$user ['uid'] = $uid;
		
		DB::insert ( "common_member_profile", $user );
		
		uc_user_synlogin ( $uid );
		
		$user = array ();
		$user ['email'] = $mobile . "@rong.com";
		$user ['username'] = $mobile;
		$user ['password'] = $password;
		$user ['status'] = 0;
		$user ['groupid'] = 10;
		$user ['regdate'] = get_second ();
		$user ['credits'] = 2;
		$user ['timeoffset'] = "9999";
		
		DB::insert ( "common_member", $user );
		$rdata = array (
				'id' => $uid,
				'token' => $uid,
				'action' => 'reg' 
		);
		
		$usercount = array (
				"uid" => $token,
				"posts" => 1,
				"threads" => 1 
		);
		
		DB::insert ( "common_member_count", $usercount );
		
		responseSingleData ( $rdata );
	}
}

?>
