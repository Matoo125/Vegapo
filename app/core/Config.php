<?php


	if ( strpos($_SERVER['HTTP_HOST'], "veg.dev" ) !== false ) {

		define("DB_HOST", "localhost");
		define("DB_NAME", 'veg-food');
		define("DB_USER", "phpmyadmin");
	 	define("DB_PASSWORD", "123456");
	 	define("SHOW_ERRORS", true);

	} else {

		 define("DB_HOST", "localhost");
		 define("DB_NAME", 'vegpotraviny');
		 define("DB_USER", "vegpotraviny");
		 define("DB_PASSWORD", "vegpass");
		 define("SHOW_ERRORS", false);

	}


	define( "COUNTRY_CODE", substr($_SERVER['SERVER_NAME'], -2) );
    define("SALT", "asd57rgre574sdfs5gs658floinbc489sdg49");

define( 'USERS', array(450 => "developer", 74 => "superadmin", 34 => "admin", 24 => "superuser", 4 => "user") );
define( 'VISIBILITY', array(1 => "visible", 2 => "request", 3 => "trash") );

if (COUNTRY_CODE == "sk") {
	setlocale(LC_TIME,"sk_SK.utf8"); //echo COUNTRY_CODE;
} elseif (COUNTRY_CODE == "cz") {
	setlocale(LC_TIME,"cs_CZ.utf8"); //echo COUNTRY_CODE;
}