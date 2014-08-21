<?php
define ( 'CODE_PARAMETER_EMPTY', 1000 );
define ( 'CODE_SUCCESS', 1 );

header ( "Content-type:application/json" );
function responseError($code, $msg) {
	$result = array ();
	$result ['rtn_code'] = $code;
	$result ['rtn_msg'] = $msg;
	
	echo json_encode ( $result );
}
function responseListData($data) {
	$result = array ();
	$result ['rtn_code'] = CODE_SUCCESS;
	$result ['rtn_msg'] = "";
	$result ['data'] = $data;
	echo json_encode ( $result );
}

function responseSingleData($data) {
	$result = array ();
	$result ['rtn_code'] = CODE_SUCCESS;
	$result ['rtn_msg'] = "";
	$result ['data'] = $data;
	echo json_encode ( $result );
}

?>