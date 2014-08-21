<?php
require '../../source/class/class_core.php';
require 'response.php';
C::app ()->init ();

$groups = DB::fetch_all ( "select fid, name from pre_forum_forum where type='group' and status=1" );
$data = array ();
$i = 0;
foreach ( $groups as $group ) {
	
	$forums = DB::fetch_all ( "select f.fid, f.fup, f.name, i.icon from pre_forum_forum as f left join pre_forum_forumfield as i on f.fid=i.fid where fup='" . $group ['fid'] . "' and status=1" );
	foreach ( $forums as &$forum ) {
		
		if (! empty ( $forum ['icon'] )) {
			$forum ['icon'] = "http://114.215.238.198/data/attachment/" . $forum ['icon'];
		}
	}
	
	$group ['forums'] = $forums;
	$data [$i] = $group;
	$i = $i + 1;
}

responseListData ( $data );

?>
