<?php
if(function_exists('curl_init')){
	echo 'CURL is OK ';
}else{
	echo 'Can\'t use CURL';
}
?>