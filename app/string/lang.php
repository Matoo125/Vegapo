<?php

use app\model\Locale;

$sk = Locale::loadLang("sk");

if (COUNTRY_CODE == 'cz') {
  $cz = Locale::loadLang("cz");

	$lang = array_merge($sk, $cz);
} else {
	$lang = $sk;
}
