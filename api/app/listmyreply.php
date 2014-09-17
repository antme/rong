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

if ($page == 0) {
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
		$start = $rows * ($page - 1);
		$end = $rows * ($page);
		$sql = "SELECT p.tid, t.subject, p.message as content, p.dateline FROM pre_forum_post as p left join pre_forum_thread as t on p.tid=t.tid where p.authorid='" . $token . "' and position <> 1 " . " limit " . $start . "," . $end;
		$posts = DB::fetch_all ( $sql );
		
		$countResut = DB::fetch_all ( "select count(*) as count FROM pre_forum_post where authorid='" . $token . "' and position <> 1" );
		$count = $countResut [0] ['count'];
		
// 		foreach ( $posts as &$post ) {
			
// 			$post ['dateline'] = date ( "Y-m-d H:i:s", $post ['dateline'] );
// 		}
		
		responseListData ( $posts, $count );
	}
}

?>
