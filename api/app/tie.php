<?php
require_once '../../source/class/class_core.php';
require_once 'response.php';
C::app ()->init ();

$fid = $_REQUEST ["fid"];

$rows = 10;
$page = 0;

if (isset ( $_REQUEST ["rows"] )) {
	$rows = $_REQUEST ["rows"];
}

if (isset ( $_REQUEST ["page"] )) {
	$page = $_REQUEST ["page"];
}

if (empty ( $fid )) {
	responseError ( CODE_PARAMETER_EMPTY, "板块ID不能为空" );
} else {
	$countResut = DB::fetch_all ( "select count(*) as count from pre_forum_thread where fid=" . $fid );
	$count = $countResut [0] ['count'];
	$start = $rows * $page;
	$end = $rows * ($page + 1);
	$ties = DB::fetch_all ( "select t.tid, t.subject, t.author, t.dateline from pre_forum_thread as t where t.fid=" . $fid . " limit " . $start . "," . $end );
	
	foreach ($ties as &$tie){
		$ties['url'] = 'http://114.215.238.198/forum.php?mod=viewthread&tid=' . $tie['tid'];
	}
	
	responseListData ( $ties, $count );
}

?>
