<?php

/**
 * This just renders a dummy content page for the purposes of demonstration
 */

class Home extends My_Controller {

    function __construct( $request ) {
        parent::__construct( $request );
    }

    function init( ) {
        // This is FRONT-facing mode, so load views inside the front-facing template view
        $this->set_mode( MODE_FRONT );

        parent::init( );
    }


    /**
     * Just render a dummy content page
     */
    public function index( ) {


        // The dummy template just has a dummy header and a dummy body
        $this->data( 'template',    'dummy.php' );

        // Bring in the desired CSS
        $this->asset_request( REQ_ASSET_CSS, 'bootstrap' );
        $this->asset_request( REQ_ASSET_CSS, 'home' );
        $this->asset_request( REQ_ASSET_CSS, 'comments' );

        // Bring in the supporting JS
        $this->asset_request( REQ_ASSET_JS, "https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" );
        $this->asset_request( REQ_ASSET_JS, "comments" );

        $this->view( );
    }
}
