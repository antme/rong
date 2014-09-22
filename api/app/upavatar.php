<?php
define ( 'IN_UC', '1' );
define ( 'UC_API', 'http://localhost/uc_server' );

require_once '../../source/class/class_core.php';
require_once 'config.inc.php';
require_once 'response.php';
C::app ()->init ();

$token = $_REQUEST ['token'];
if (empty ( $token )) {
	responseError ( CODE_PARAMETER_EMPTY, "token不能为空" );
} else {
	$uid = $token;
	$home = get_home ( $uid );
	if (! is_dir ( UC_DATADIR . './avatar/' . $home )) {
		set_home ( $uid, UC_DATADIR . './avatar/' );
	}
	uploadAvatar ( $token, $home );
	
	// http://localhost/uc_server/avatar.php?uid=20&size=middle
	
	$data = array (
			"avatar_url" => "http://114.215.238.198/uc_server/avatar.php?uid=" . $token . "&size=middle" 
	);
	
	responseSingleData ( $data );
}
function uploadAvatar($uid, $home) {
	$avatar_dir = DISCUZ_ROOT . 'uc_server' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'avatar' . DIRECTORY_SEPARATOR . $home . DIRECTORY_SEPARATOR;
	
	$tmpPath = $avatar_dir . $uid . "_avatar.jpg";
	$bigavatarfile = $avatar_dir . $uid . "_avatar_big.jpg";
	$middleavatarfile = $avatar_dir . $uid . "_avatar_middle.jpg";
	$smallavatarfile = $avatar_dir . $uid . "_avatar_small.jpg";
	
	@unlink ( $bigavatarfile );
	@unlink ( $middleavatarfile );
	@unlink ( $smallavatarfile );
	@unlink ( $tmpPath );
	
	// ?????
	if (@copy ( $_FILES ['avartar_file'] ['tmp_name'], $tmpPath )) {
	}
	
	if (@copy ( $_FILES ['avartar_file'] ['tmp_name'], $bigavatarfile )) {
	}
	if (@copy ( $_FILES ['avartar_file'] ['tmp_name'], $middleavatarfile )) {
	}
	
	if (@copy ( $_FILES ['avartar_file'] ['tmp_name'], $smallavatarfile )) {
	}
	
	@unlink ( $_FILES ['avartar_file'] ['tmp_name'] );
	// list ( $width, $height, $type, $attr ) = getimagesize ( $tmpPath );
	
	return $tmpPath;
}
function get_home($uid) {
	$uid = sprintf ( "%09d", $uid );
	$dir1 = substr ( $uid, 0, 3 );
	$dir2 = substr ( $uid, 3, 2 );
	$dir3 = substr ( $uid, 5, 2 );
	return $dir1 . '/' . $dir2 . '/' . $dir3;
}
function set_home($uid, $dir = '.') {
	$uid = sprintf ( "%09d", $uid );
	$dir1 = substr ( $uid, 0, 3 );
	$dir2 = substr ( $uid, 3, 2 );
	$dir3 = substr ( $uid, 5, 2 );
	! is_dir ( $dir . '/' . $dir1 ) && mkdir ( $dir . '/' . $dir1, 0777 );
	! is_dir ( $dir . '/' . $dir1 . '/' . $dir2 ) && mkdir ( $dir . '/' . $dir1 . '/' . $dir2, 0777 );
	! is_dir ( $dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3 ) && mkdir ( $dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3, 0777 );
}
function get_avatar($uid, $size = 'big', $type = '') {
	$size = in_array ( $size, array (
			'big',
			'middle',
			'small' 
	) ) ? $size : 'big';
	$uid = abs ( intval ( $uid ) );
	$uid = sprintf ( "%09d", $uid );
	$dir1 = substr ( $uid, 0, 3 );
	$dir2 = substr ( $uid, 3, 2 );
	$dir3 = substr ( $uid, 5, 2 );
	$typeadd = $type == 'real' ? '_real' : '';
	return $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr ( $uid, - 2 ) . $typeadd . "_avatar_$size.jpg";
}



