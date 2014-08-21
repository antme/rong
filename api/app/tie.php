<?php
require '../../source/class/class_core.php';
require 'response.php';
C::app ()->init ();

$fid = $_REQUEST ["fid"];
if (empty ( $fid )) {
	responseError ( CODE_PARAMETER_EMPTY, "板块ID不能为空" );
} else {
}

?>
