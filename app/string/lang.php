<?php

use app\model\Locale;
// require_once APP . DS . 'string/lang/sk.php';
$sk = Locale::loadLang("sk");

if (COUNTRY_CODE == 'cz') {
	// require_once APP . DS . 'string/lang/cz.php';
  $cz = Locale::loadLang("cz");

	$lang = array_merge($sk, $cz);
} else {
	$lang = $sk;
}

// require_once APP . DS . 'string/lang/cz.php';
// saveLang("sk",$sk);
// saveLang("cz",$cz);
// use app\helper\SDiff;

// use mrkovec\sdiff\SDiff;
//
// $a = loadLang("sk");
// $b = loadLang("cz");

// $a ="dad bbb ccc\nca ca\n123 5555";
// $b ="ddd bbbb ccn\nca ca\n234 5655";

// print_r(SDiff::getCharDiff("a","bbabb"));
// echo SDiff::getTextDiff($a,$b, " ",True);
// echo SDiff::getObjectDiff($a,$b);
// echo SDiff::getCharDiff($a, $b)."<br>";
// echo SDiff::getWordDiff($a, $b)."<br>";
// echo SDiff::getClauseDiff($a, $b)."<br>";

// print_r(SDiff::getDiff($a, $b, [["spliter" => "\n", "retEqual"=>True],["spliter" => " ", "retEqual"=>True], ["spliter" => ""]]));
// ,["spliter" => " "],["spliter" => ""]]));
// exit;
