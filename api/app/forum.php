<?php
require '../../source/class/class_core.php';
C::app ()->init ();

$groups = DB::fetch_all ( "select fid, name from pre_forum_forum where type='group' and status=1" );

$arr = array ();
$i = 0;

foreach ( $groups as $group ) {
	
	$forums = DB::fetch_all ( "select fid, fup, name from pre_forum_forum where fup='" . $group ['fid'] . "' and status=1" );
	$group ['forums'] = $forums;
	$arr [$i] = $group;
	$i = $i + 1;
}

echo json_encode ( $arr );

?>
