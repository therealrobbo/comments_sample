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
        error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE ^ E_STRICT );
        break;

    case ENV_PROD:
        $gPZ['doc_root']          = '/rmw.technology/html/tmbc';

        $gPZ['app_dir']           = 'tmbc';
        $gPZ['base_url']          = 'http://rmw.technology/tmbc';
        $gPZ['base_url_ssl']      = 'http://rmw.technology/tmbc';
        $gPZ['ssl_environment']   = false;
        $gPZ['admin_email']       = $gPZ['inquiries_email'];
        $gPZ['admin_cookie']      = 'tmbc_comments';
        $gPZ['db_config']         = array(
            "username"  => "rmwtechn_tmbc",
            "password"  => "FoeBugledMetersLanded91",
            "database"  => "rmwtechn_tmbc_comments",
            "hostname"  => "localhost"
        );
        $gPZ['session_cookie']      = 'comments_9dk30fl032lf531';
// TODO        error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE ^ E_STRICT );
// TODO       ini_set( 'display_errors', 1 );
        error_reporting( 0 );
        break;

}


define( 'BLANK_DATE', '0000-00-00 00:00:00' );
?>