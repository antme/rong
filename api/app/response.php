<?php
define ( 'CODE_PARAMETER_EMPTY', 1000 );
define ( 'CODE_SUCCESS', 0 );

header ( "Content-type:application/json" );
function responseError($code, $msg) {
	$result = array ();
	$result ['rtn_code'] = $code;
	$result ['rtn_msg'] = $msg;
	
	echo json_encode ( $result );
}
function responseListData($data) {
	responseSingleData ( $data );
}
function responseSingleData($data) {
	$result = array ();
	$result ['rtn_code'] = CODE_SUCCESS;
	$result ['rtn_msg'] = "success";
	$result ['rtn_ext'] = "114.215.238.198|1.0";
	$result ['data'] = $data;
	echo json_encode ( $result );
}

?>