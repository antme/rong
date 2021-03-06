<?php
require_once '../../source/class/class_core.php';
require_once 'response.php';
C::app ()->init ();

$token = $_REQUEST ["token"];

$rows = 10;
$page = 1;

if (isset ( $_REQUEST ["rows"] )) {
	$rows = $_REQUEST ["rows"];
}

if (isset ( $_REQUEST ["page"] )) {
	$page = $_REQUEST ["page"];
}

if($page == 0){
	$page = 1;
}


if (empty ( $token )) {
	responseError ( CODE_PARAMETER_EMPTY, "token不能为空" );
} else {
	
	$sql = "SELECT * FROM pre_common_member where uid='" . $token . "'";
	$member = DB::fetch_first ( $sql );
	
	if (empty ( $member )) {
		responseError ( USER_TOKEN_INVALID, "此token不存在或者已经失效" );
	} else {
		$countResut = DB::fetch_all ( "select count(*) as count from pre_forum_thread where authorid=" . $token );
		$count = $countResut [0] ['count'];
		$start = $rows * ($page-1);
		$end = $rows * ($page);
		$ties = DB::fetch_all ( "select t.tid, t.subject, t.author, t.dateline from pre_forum_thread as t where t.authorid=" . $token . " limit " . $start . "," . $end );
		
		foreach ( $ties as &$tie ) {
			$tie ['url'] = 'http://'. SERVER_ADDRESS. '/forum.php?mod=viewthread&tid=' . $tie ['tid'];
		}
		
		responseListData ( $ties, $count );
	}
}

?>
