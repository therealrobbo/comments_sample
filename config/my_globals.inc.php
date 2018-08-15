<?php
global $gEnvironment, $gPZ;


$gPZ['environment']         = $gEnvironment;
$gPZ['app_name']            = 'Comment Exercise';
$gPZ['app_slogan']          = 'Test code for TMBC';
$gPZ['app_dir']             = '';
$gPZ['inquiries_email']     = 'rmw.technology@gmail.com';
$gPZ['session_cookie']      = 'comments_93shnfdk30fl032lf';
$gPZ['default_controller']  = 'home';


switch ( $gPZ['environment'] ) {
    case ENV_LOC:
        $gPZ['doc_root']          = 'C:/Users/Dev/Documents/GitHub/comment_exercise/';

        $gPZ['base_url']          = 'http://loc.comments.com';
        $gPZ['base_url_ssl']      = 'http://loc.comments.com';
        $gPZ['ssl_environment']   = false;
        $gPZ['admin_email']       = $gPZ['inquiries_email'];
        $gPZ['admin_cookie']      = 'XmRZ20FK16L';
        $gPZ['db_config']         = array(
            "username"  => "root",
            "password"  => "",
            "database"  => "comment_test",
            "hostname"  => "127.0.0.1"
        );
        $gPZ['session_cookie']    = 'comment_9dk30fl032lflocal';
        $gPZ['uploads_path']      = $gPZ['doc_root'] . "../comment-content/";
        error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE ^ E_STRICT );
        break;

    case ENV_PROD:
        $gPZ['doc_root']          = '';     // TODO: Configure document root

        $gPZ['base_url']          = '';     // TODO: Configure base URL
        $gPZ['base_url_ssl']      = '';     // TODO: Configure base SSL
        $gPZ['suppress_ads']      = false;
        $gPZ['ssl_environment']   = false;
        $gPZ['admin_email']       = '';     // TODO: Configure admin email
        $gPZ['admin_cookie']      = '';     // TODO: Configure amin cookie name
        $gPZ['db_config']         = array(
            "username"  => "",              // TODO: Configure database username
            "password"  => "",              // TODO: Configure database password
            "database"  => "",              // TODO: Configure database name
            "hostname"  => ""               // TODO: Configure database host
        );
        $gPZ['uploads_path']        = "";   // TODO: Configure upload path
        $gPZ['session_cookie']      = 'coffee_9dk30fl032lf531';
// TODO        error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE ^ E_STRICT );
// TODO       ini_set( 'display_errors', 1 );
        error_reporting( 0 );
        break;

}


define( 'BLANK_DATE', '0000-00-00 00:00:00' );
?>