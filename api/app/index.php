<?php
require '../../source/class/class_core.php';
require 'response.php';
C::app ()->init ();

$token = $_REQUEST ["token"];

$valid = true;
if (empty ( $token )) {
	responseError ( CODE_PARAMETER_EMPTY, "token不能为空" );
} else {
	$sql = "SELECT * FROM pre_common_member where uid='" . $token . "'";
	$member = DB::fetch_first ( $sql );
	
	if (empty ( $member )) {
		responseError ( USER_TOKEN_INVALID, "此token不存在或者已经失效" );
	} else {
		$groups = DB::fetch_all ( "select fid, name from pre_forum_forum where type='group' and status=1" );
		$data = array ();
		$i = 0;
		foreach ( $groups as $group ) {
			
			$forums = DB::fetch_all ( "select f.fid, f.fup, f.name, i.icon from pre_forum_forum as f left join pre_forum_forumfield as i on f.fid=i.fid where fup='" . $group ['fid'] . "' and status=1" );
			foreach ( $forums as &$forum ) {
				
				if (! empty ( $forum ['icon'] )) {
					$forum ['icon'] = "http://114.215.238.198/data/attachment/common/" . $forum ['icon'];
				}
			}
			
			$group ['forums'] = $forums;
			$data [$i] = $group;
			$i = $i + 1;
		}
		
		$sql = "SELECT * FROM pre_common_member_profile where uid='" . $token . "'";
		$prifile = DB::fetch_first ( $sql );
		
		$countsql = "SELECT count(*) as count FROM pre_common_member_profile where residedist='" . $prifile ['residedist'] . "'";
		$residecount = DB::fetch_first ( $countsql );
		
		$infodata = array (
				"residedist" => $prifile ['residedist'],
				"floor" => $prifile ['field1'],
				"username" => $member ['username'],
				"nickname" => $prifile ['field3'],
				"gender" => $prifile ['gender'],
				"occupation" => $prifile ['occupation'],
				"room" => $prifile ['field2'] 
		);
		
		$resideinfo = array (
				"residepeples" => $residecount ['count'] 
		);
		
		$rdata = array (
				"finfo" => $data,
				"uinfo" => $infodata,
				"resideinfo" => $resideinfo 
		);
		
		responseListData ( $rdata );
	}
}

?>
