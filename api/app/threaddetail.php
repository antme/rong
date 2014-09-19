<?php
require_once '../../source/class/class_core.php';
require_once 'response.php';
C::app ()->init ();

$tid = $_REQUEST ["tid"];
$token = $_REQUEST ["token"];

if (empty ( $tid )) {
	responseError ( CODE_PARAMETER_EMPTY, "帖子ID不能为空" );
} else if (empty ( $token )) {
	responseError ( CODE_PARAMETER_EMPTY, "token不能为空" );
} else {
	
	$sql = "SELECT * FROM pre_common_member where uid='" . $token . "'";
	$member = DB::fetch_first ( $sql );
	
	if (empty ( $member )) {
		responseError ( USER_TOKEN_INVALID, "此token不存在或者已经失效" );
	} else {
		
		$tie = DB::fetch_first ( "select t.tid, t.subject, t.author, t.dateline from pre_forum_thread as t where t.tid=" . $tid );
		
		$countResut = DB::fetch_all ( "select count(*) as count FROM pre_forum_post where tid='" . $tid . "' and position <> 1" );
		$count = $countResut [0] ['count'];
		$tie ['totalReply'] = $count;
		$tie ['url'] = 'http://114.215.238.198/forum.php?mod=viewthread&tid=' . $tie ['tid'];
		
		responseSingleData ( $tie );
	}
}

?>
