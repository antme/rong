<?php

define('SERVER_ADDRESS', '114.215.238.198:8080');

define ( 'CODE_PARAMETER_EMPTY', 1000 );

define ( 'USERNAME_OR_PASSWORD_ERROR', 10001 );
define ( 'REG_CODE_SEND_ERROR', 10002 );
define ( 'REG_CODE_INVALID', 10003 );
define ( 'USER_REG_EXISTS', 10004 );
define ( 'USER_NOT_EXISTS', 10005 );

define ( 'USER_TOKEN_INVALID', 10006 );
define ( 'THREAD_NOT_EXISTS', 10007 );


define ( 'CODE_SUCCESS', 0 );
function responseError($code, $msg) {
	$result = array ();
	$result ['rtn_code'] = $code;
	$result ['rtn_msg'] = $msg;
	header ( "Content-type:application/json" );
	
	echo json_encode ( $result );
}
function responseListData($data, $total) {
	responseSingleData ( $data, $total );
}
function responseSingleData($data, $total = null) {
	$result = array ();
	$result ['rtn_code'] = CODE_SUCCESS;
	$result ['rtn_msg'] = "success";
	$result ['rtn_ext'] = "114.215.238.198|1.0";
	$result ['data'] = $data;
	
	if (isset ( $total )) {
		$result ['total'] = $total;
	}
	header ( "Content-type:application/json" );
	
	echo json_encode ( $result );
}
function get_millisecond() {
	list ( $s1, $s2 ) = explode ( ' ', microtime () );
	return ( float ) sprintf ( '%.0f', (floatval ( $s1 ) + floatval ( $s2 )) * 1000 );
}
function get_second() {
	list ( $s1, $s2 ) = explode ( ' ', microtime () );
	return ( float ) sprintf ( '%.0f', (floatval ( $s1 ) + floatval ( $s2 )) );
}

?>