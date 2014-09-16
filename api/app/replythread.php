<?php
require_once '../../source/class/class_core.php';
require_once 'response.php';
C::app ()->init ();

$tid = $_REQUEST ["tid"];
$token = $_REQUEST ["token"];
$subject = "";
$content = $_REQUEST ["content"];

if (empty ( $tid )) {
	responseError ( CODE_PARAMETER_EMPTY, "帖子ID不能为空" );
} else if (empty ( $token )) {
	responseError ( CODE_PARAMETER_EMPTY, "token不能为空" );
}  else if (empty ( $content )) {
	responseError ( CODE_PARAMETER_EMPTY, "回复内容不能为空" );
} else {
	
	$sql = "SELECT * FROM pre_common_member where uid='" . $token . "'";
	$member = DB::fetch_first ( $sql );
	
	if (empty ( $member )) {
		responseError ( USER_TOKEN_INVALID, "此token不存在或者已经失效" );
	} else {
		
		
		
		$sql = "SELECT * FROM pre_common_member_profile where uid='" . $token . "'";
		$prifile = DB::fetch_first ( $sql );
		
		if (! empty ( $prifile ['field3'] )) {
			$author = $prifile ['field3'];
		} else {
			$author = $member ['username'];
		}
		
		
		$content = urldecode($content);
		$authorid = $token;
		$lastpost = get_second ();
		$lastposter = $author;
		$status = 32;
		
		
		$tsql = "SELECT * FROM pre_forum_thread where tid='" . $tid . "'";
		$thread = DB::fetch_first ( $tsql );
	

		$csql = "SELECT count(*) as count FROM pre_forum_post where tid='" . $tid . "'";
		$count = DB::fetch_first ( $csql );
		
		if ($tid) {
			$post ["tid"] = $tid;
			$post ["fid"] = $thread['fid'];
			$post ["subject"] = "";
			$post ["position"] = $count['count'] + 1;
			$post ["first"] = 0;
			$post ["smileyoff"] = "-1";
			$post ["bbcodeoff"] = "-1";
			$post ["usesig"] = 1;
			$post ["author"] = $author;
			$post ["authorid"] = $authorid;
			$post ["dateline"] = $lastpost;
			$post ["invisible"] = 0;
			$post ["usesig"] = 0;
			// C::t ( 'forum_sofa' )->insert ( $sofa, 1 );
			$pid = C::t ( 'forum_post_tableid' )->insert ( array (
					'pid' => null 
			), true );
			$post ["pid"] = $pid;
			$post ["message"] = $content;
			$okid = C::t ( 'forum_post' )->insert ( "0", $post, 1 );
		}
		
		$usercount = DB::fetch_first ( "SELECT * FROM pre_common_member_count where uid ='" . $token . "'" );
		
		if (empty ( $usercount )) {
			
			$usercount = array (
					"uid" => $token,
					 "posts" =>1,
					"threads" =>1
			);
			
			
			DB::insert ( "common_member_count", $usercount );
		} else {
			
			DB::query ( "UPDATE " . DB::table ( 'common_member_count' ) . " SET  posts=posts+1 WHERE uid='" . $token . "'", 'UNBUFFERED' );
		}
		
		DB::query ( "UPDATE " . DB::table ( 'forum_forum' ) . " SET lastpost='$lastpost', posts=posts+1, todayposts=todayposts+1 WHERE fid='" . $fid . "'", 'UNBUFFERED' );
		
		$rdata = array (
				
				"msg" => "发表成功" 
		);
		responseListData ( $rdata );
	}
}

?>
