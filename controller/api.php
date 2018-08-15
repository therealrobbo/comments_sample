<?php

/**
 * The base Api for accessing our commenting system
 */

class Api extends My_Controller {

    private $banned_words;
    private $word_used = null;

    function __construct( $request ) {
        parent::__construct( $request );

        $this->library( 'database', 'db' );

        $this->model( "comments" );

        // NOTE: this would probably be retrieved from a table but since the exercise doesn't call for backend tools
        // for maintaining that table, I'm just hardcoding it here.
        $this->banned_words = array(
            'poop', 'heck', 'dang'
        );
    }

    function init( ) {
        // Set API calls should run in API mode, which means BARE views are rendered
        $this->set_mode( MODE_API );

        parent::init( );
    }


    // Default API method - Get Content
    public function index( $content_id ) {
        return( $this->get( $content_id ) );
    }


    /**
     * Get the comments for a specified content ID
     *
     * @param int $content_id
     */
    public function get( $content_id = 0 ) {

        // If the specified a content_id
        if ( !empty( $content_id ) ) {

            // Fetch the comments for this content_id
            $comment_thread = $this->comments->get_for_content( $content_id );

            // Bundle it into a return data structure
            $return_data = array (
                'result'   => ( !empty( $comment_thread ) ? 'comments' : 'new' ),
                'message'  => ( !empty( $comment_thread ) ? 'Comments fetched' : 'No comments found' ),
                'comments' => $comment_thread
            );
        } else {

            // They didn't specify a content ID, so the call is invalid
            $return_data = array (
                'result'   => 'invalid',
                'message'  => 'Missing arg: content id',
                'comments' => null
            );
        }

        // Request the return be rendered as JSON and set the json data for the template
        $this->data( 'template',    'bare_json.php' );
        $this->data( 'json_data',   json_encode( $return_data ) );

        // Render the result to the caller
        $this->view( );
    }

    private function validate_body( $body ) {

        $valid = true;
        $body = strtolower( $body );

        // Go through the list of curse words
        foreach( $this->banned_words as $bad_word ) {

            // If the comment contains a curse word
            if ( strstr( $body, $bad_word ) ) {
                $this->word_used = $bad_word;
                $valid = false;
            }
        }

        return( $valid );
    }


    /**
     * Add a comment to the database
     *
     * @param int $content_id - the content the comment belongs to
     * @param string $name    - the name of the commenter
     * @param string $body    - the comment
     * @param int $parent_id  - the parent comment (or zero if direct comment)
     */
    public function add( $content_id = 0, $name = '', $body = '', $parent_id = 0 ) {

        $return_data = array (
            'result'   => 'invalid'
        );

        // Do they have the baseline arguments?
        if ( !empty( $content_id ) && !empty( $name ) && !empty( $body ) ) {

            // Only allow the comment if there are no bad words in the body
            if ( $this->validate_body( $body ) ) {

                // Add the comment to the database
                $new_id = $this->comments->add( array(
                    'content_id'        => $content_id,
                    'parent_id'         => $parent_id,
                    'name'              => $name,
                    'body'              => $body,
                ) );

                $comment_thread = $this->comments->get_for_content( $content_id );

                $return_data['result'] = 'success';
                $return_data['info']   = $comment_thread;
                $return_data['debut']  = "content_id = $content_id";

            } else {
                $return_data['result'] = 'invalid_banned_words';
                $return_data['info']   = $this->word_used;
            }
        } else {

            if ( empty( $content_id ) ) {
                $return_data['result'] = 'invalid_id';
            } elseif( empty( $name ) ) {
                $return_data['result'] = 'invalid_name';
            } else {
                $return_data['result'] = 'invalid_body';
            }
        }

        // Request the return be rendered as JSON and set the json data for the template
        $this->data( 'template',    'bare_json.php' );
        $this->data( 'json_data',   json_encode( $return_data ) );

        // Render the result to the caller
        $this->view( );
    }
}
