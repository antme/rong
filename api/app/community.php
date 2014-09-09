<?php
require '../../source/class/class_core.php';
require 'response.php';
C::app ()->init ();

$fields = DB::fetch_all ("select fieldid, choices from pre_common_member_profile_setting where fieldid = 'field1'" );
$data = array ();
foreach ( $fields as $field ) {	
	$data = split("\n", $field['choices']);
}
responseListData($data)

?>
