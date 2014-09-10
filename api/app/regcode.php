<?php
require_once '../../source/class/class_core.php';
require_once 'response.php';
C::app ()->init ();

$mobile = $_REQUEST ["mobile"];

if (empty ( $mobile )) {
	responseError ( CODE_PARAMETER_EMPTY, "手机号不能为空" );
} else {
	
	DB::delete ( "rong_reg_code", array (
			'mobile' => $mobile 
	) );
	
	$regcode = rand ( 1000, 9999 );
	$data = array ();
	$data ['mobile'] = $mobile;
	$data ['regcode'] = $regcode;
	$time = explode ( " ", microtime () );
	$data ['createdon'] = $time [1];
	
	$ch = curl_init (); // 初始化curl
	$post_data = array (
			'account' => 'cf_rongju',
			'password' => 'change2014',
			'mobile' => $mobile,
			'content' => "验证码为" . $regcode . "，请在客户端正确输入以完成手机验证，验证码10分钟内有效。" 
	); // 定义参数
	
	$url = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
	curl_setopt ( $ch, CURLOPT_URL, $url ); // 设置链接
	
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // 设置是否返回信息
	                                                
	// curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header ); // 设置HTTP头
	
	curl_setopt ( $ch, CURLOPT_POST, 1 ); // 设置为POST方式
	
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data ); // POST数据
	
	$response = curl_exec ( $ch ); // 接收返回信息
	
	if (curl_errno ( $ch )) { // 出错则显示错误信息
		curl_close ( $ch ); // 关闭curl链接
		
		responseError ( REG_CODE_SEND_ERROR, "验证码发送失败" );
	} else {
		curl_close ( $ch ); // 关闭curl链接
		
		$p = xml_parser_create ();
		xml_parse_into_struct ( $p, $response, $vals, $index );
		xml_parser_free ( $p );
		$code = $vals [1] ['value'];
		if ($code == '2') {
			DB::insert ( "rong_reg_code", $data );
			$result_data = array (
					'msg' => '验证码已经发送' 
			);
			responseSingleData ( $result_data );
		} else {
			responseError ( REG_CODE_SEND_ERROR, $vals [3] ['value'] );
		}
	}
}
function get_total_millisecond() {
	$time = explode ( " ", microtime () );
	$time = $time [1] . ($time [0] * 1000);
	$time2 = explode ( ".", $time );
	$time = $time2 [0];
	return $time;
}
?>
