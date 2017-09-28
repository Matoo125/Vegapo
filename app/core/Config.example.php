<?php

// if dev server is running
if ( strpos($_SERVER['HTTP_HOST'], "vegapo.dev" ) !== false ) {

  define("DB_HOST", "localhost");
  define("DB_NAME", 'vegapo');
  define("DB_USER", "root");
  define("DB_PASSWORD", "");
  define("SHOW_ERRORS", true);
  define("DEVELOPMENT", true);
  define("SALT", "123");

   // Whoops error handler
   $whoops = new \Whoops\Run;
   $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
   // uncomment this if you want Json errors
   // $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
   $whoops->register();

} 
// if production
else {

   define("DB_HOST", "***");
   define("DB_NAME", '***');
   define("DB_USER", "*****");
   define("DB_PASSWORD", "***");
   define("SHOW_ERRORS", false);
   define("DEVELOPMENT", false);
   define("SALT", "***");

}

define("HOSTING_API_KEY", false);

define("PATH_TO_LOGS", ROOT . '/error.log');
ini_set('error_log', PATH_TO_LOGS);

// facebook credentials
define("FACEBOOK_APP_ID", '***');
define("FACEBOOK_APP_SECRET", '***');
// take country code from domain
define( "COUNTRY_CODE", substr($_SERVER['SERVER_NAME'], -2) ); 
define("EMAIL_PASSWORD", '***');
    
// I know those numbers do not make sense
define( 'USERS', array(450 => "developer", 74 => "superadmin", 34 => "admin", 24 => "superuser", 4 => "user") );
// visibility of products, not sure why it is here
define( 'VISIBILITY', array(1 => "visible", 2 => "request", 3 => "trash") );

// set locale, it is probably not even used.
if (COUNTRY_CODE == "sk") {
  setlocale(LC_TIME,"sk_SK.utf8"); 
} elseif (COUNTRY_CODE == "cz") {
  setlocale(LC_TIME,"cs_CZ.utf8"); 
}