<?php
require_once '../../source/class/class_core.php';
require_once 'response.php';
C::app ()->init ();

$fid = $_REQUEST ["fid"];

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

if (empty ( $fid )) {
	responseError ( CODE_PARAMETER_EMPTY, "板块ID不能为空" );
} else {
	$countResut = DB::fetch_all ( "select count(*) as count from pre_forum_thread where fid=" . $fid );
	$count = $countResut [0] ['count'];
	$start = $rows * ($page-1);
	$end = $rows * $page;
	$ties = DB::fetch_all ( "select t.tid, t.subject, t.author, t.dateline from pre_forum_thread as t where t.fid=" . $fid . " limit " . $start . "," . $end );
	
	foreach ($ties as &$tie){
		$countResut = DB::fetch_all ( "select count(*) as count FROM pre_forum_post where tid='" . $tie['tid'] . "' and position <> 1");
		$count = $countResut [0] ['count'];
		$tie['totalReply'] = $count;
		$tie['url'] = 'http://114.215.238.198/forum.php?mod=viewthread&tid=' . $tie['tid'] .'&random='.get_second();
	}
	
	responseListData ( $ties, $count );
}

?>
