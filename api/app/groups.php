<?php
require '../../source/class/class_core.php';
C::app ()->init ();

$groups = DB::fetch_all ( "select fid, name from pre_forum_forum where type='group' and status=1" );

$result = array();

$arr = array ();
$i = 0;

foreach ( $groups as $group ) {
	
	$forums = DB::fetch_all ( "select f.fid, f.fup, f.name, i.icon from pre_forum_forum as f left join pre_forum_forumfield as i on f.fid=i.fid where fup='" . $group ['fid'] . "' and status=1" );
	$group ['forums'] = $forums;
	$arr [$i] = $group;
	$i = $i + 1;
}

$result['code'] = 1;
$result['rows'] = $arr;
header("Content-type:application/json");

echo json_encode ( $result );

?>
