<?php

use app\model\Locale;

// require_once APP . DS . 'string/lang/sk.php';
// require_once APP . DS . 'string/lang/cz.php';
// //
// Locale::saveLang("sk", $sk);
// Locale::saveLang("cz", $cz);
// exit;

$sk = Locale::loadLang("sk");

if (COUNTRY_CODE == 'cz') {
  $cz = Locale::loadLang("cz");

	$lang = array_merge($sk, $cz);
} else {
	$lang = $sk;
}
