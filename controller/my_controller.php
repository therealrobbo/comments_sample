<?php
/**
 * Override base controller for site-wide services
 */

define( 'MODE_FRONT',    0 );
define( 'MODE_API',      1 );

define( 'URL_COLON_REPLACE', '_c_' );
define( 'URL_SLASH_REPLACE', '_s_' );


class My_Controller extends PZ_Controller {
    private $messages = null;

    private $default_template = '';

    private $page_title;
    private $mode;


    /**
     * Constructor method.
     *
     * @param $request - standard PZ requested method
     *
     * @param array $no_login - controller subclass can provide an array of methods that do not require the user to be logged in
     * @param array $method_auth - controller subclass can provide a table of authorization levels required
     *                             for methods within the controller
     *                             e.g. array( "index" => ADMIN_SEC_FREELANCER, "upgrade" => ADMIN_SEC_ADMIN ).
     *                             note: use special key "all" to assign all methods to a level
     */
    function __construct( $request ) {

        global $gPZ;

        parent::__construct( $request );

        // Refresh the gPZ local with the updated user info
        $this->gPZ = $gPZ;

        // Models

        // Libraries

        $this->data( 'app_name',  $this->gPZ['app_name'] );
        $this->data( 'site_name', $this->gPZ['app_name'] );

        $this->data( 'base_url',    $this->gPZ['base_url'] );
    }

    function init( ) {

        // Call the base init function to fire all libraries and models
        parent::init();
    }


    function get_var( $var_name, $default_value = '' ) {

        return( isset( $this->gPZ['post_vars'][$var_name] ) ? $this->gPZ['post_vars'][$var_name] : $default_value );
    }


    function set_mode( $mode = MODE_FRONT ) {

        $this->mode = $mode;

        switch( $this->mode ) {
            case MODE_FRONT:

                $this->page_title = $this->gPZ['app_name'];
                $this->data( 'site_title',  $this->gPZ['app_name'] );
                $this->data( 'header_title',  $this->gPZ['app_name'] );
                $this->data( 'header_slogan', $this->gPZ['app_slogan'] );

                $this->default_template = 'default';
                break;

            case MODE_API:
                $this->default_template = 'bare_data';
                break;
        }
    }

    public function view( $template_name = '', $display = true ) {

        $template_name = ( !empty( $template_name ) ? $template_name : $this->default_template );

        return( parent::view( $template_name, $display ) );
    }




}

?>
