<?php
require_once '../../source/class/class_core.php';
require_once 'response.php';
C::app ()->init ();

$fid = $_REQUEST ["fid"];
$token = $_REQUEST ["token"];
$subject = $_REQUEST ["subject"];
$content = $_REQUEST ["content"];

if (empty ( $fid )) {
	responseError ( CODE_PARAMETER_EMPTY, "板块ID不能为空" );
} else if (empty ( $token )) {
	responseError ( CODE_PARAMETER_EMPTY, "token不能为空" );
} else if (empty ( $subject )) {
	responseError ( CODE_PARAMETER_EMPTY, "标题不能为空" );
} else if (empty ( $content )) {
	responseError ( CODE_PARAMETER_EMPTY, "内容不能为空" );
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
		
		$authorid = $token;
		
		$lastpost = get_second ();
		$lastposter = $author;
		
		$status = 32;
		
		$data = array (
				"fid" => $fid,
				"subject" => $subject,
				"author" => $author,
				"authorid" => $authorid,
				"dateline" => $lastpost,
				"lastpost" => $lastpost,
				"lastposter" => $author,
				"status" => 32 
		);
		
		$success = DB::insert ( "forum_thread", $data );
		
		if ($success) {
			$postsql = "SELECT count(*) as count FROM pre_forum_post";
			$post = DB::fetch_first ( $postsql );
			
			$contentdata = array (
					"fid" => $fid,
					"tid" => $tie ['tid'],
					"subject" => $subject,
					"author" => $author,
					"authorid" => $authorid,
					"dateline" => $lastpost,
					"message" => $content,
					"first" => 1,
					"pid" => $post ['count'] + 1 
			);
			
			$tie = DB::insert ( "forum_post", $contentdata );
			
			$rdata = array (
					
					"msg" => "发表成功" 
			);
			responseListData ( $rdata );
		} else {
			
		}
		
		// TODO
		// read useip and port data from client
	}
}

?>
