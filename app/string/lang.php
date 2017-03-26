<?php 

require_once APP . DS . 'string/lang/sk.php';


if (COUNTRY_CODE == 'cz') {
	require_once APP . DS . 'string/lang/cz.php';

	$lang = array_merge($sk, $cz);
} else {
	$lang = $sk;
}

