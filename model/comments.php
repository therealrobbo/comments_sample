<?php

/**
 * Model for Coments
 */

class Comments extends PZ_Model {

    private $table     = 'comments';
    private $field_prefix     = 'comment_';
    private $field_dictionary = array(
        'id'                => 0,
        'content_id'        => 0,
        'parent_id'         => 0,
        'name'              => '',
        'time'              => BLANK_DATE,
        'body'              => '',
    );


    function __construct(  ) {
        parent::__construct(  );

    }

    private function field_is_in_dictionary( $field_name ) {
        return( isset( $this->field_dictionary[$field_name] ) );
    }

    private function strip_prefix( $prompt_rec ) {
        $new_user_rec = array();
        foreach( $prompt_rec as $key => $value ) {
            $stripped_key = str_replace( $this->field_prefix, '', $key );
            if ( $this->field_is_in_dictionary( $stripped_key ) ) {
                $new_user_rec[$stripped_key] = $value;
            }
        }

        return( $new_user_rec );
    }

    private function query_field( $field_suffix ) {
        return( $this->table . "." . $this->field_prefix . $field_suffix );
    }

    function dummy( ) {
        $return_rec = array( );
        foreach( $this->field_dictionary  as $key => $value ) {
            $return_rec[$key] = $value;
        }

        return( $return_rec );
    }

    /**
     * Add a new Comment to the database
     *
     * @param null $update_fields
     * @return int
     */
    function add( $update_fields = null ) {

        $set_part = '';
        foreach( $update_fields as $key => $value ) {
            if ( $this->field_is_in_dictionary( $key ) ) {

                if ( ( $key == 'id' ) || ( $key == 'time' ) ) {
                    continue;
                }
                if ( empty( $set_part ) ) {
                    $set_part = "SET ";
                } else {
                    $set_part .= ", ";
                }

                $set_part .= $this->query_field( $key ) . " = '" . $this->gCI->db->escape_string( $value ) .  "' ";
            }
        }

        if ( empty( $set_part ) ) {
            return ( 0 );
        } else {
            $set_part .= ", " . $this->query_field( 'time' ) . " = now()";

            // Now add the corresponding CSL Admin record...
            $query = "INSERT INTO " . $this->table . " " .
                        $set_part;

            $this->gCI->db->query( $query );
            return ( $this->gCI->db->insert_id() );
        }
    }


    /**
     * Get the data for a specified Comment
     *
     * @param $comment_id
     *
     * @return array|null
     */
    function get( $comment_id ) {

        $comment_rec = null;

        // Query the comment from the database
        $query = "SELECT * " .
                   "FROM " . $this->table . " " .
                  "WHERE " . $this->query_field( 'id' ) . " = '" . $this->gCI->db->escape_string( $comment_id ) . "'";
        $result = $this->gCI->db->query($query);
        if ( !empty( $result ) && ( $this->gCI->db->num_rows( $result ) != "0" ) ) {

            $comment_rec = $this->gCI->db->fetch_assoc( $result );
            $comment_rec = $this->strip_prefix( $comment_rec );
        }

        return( $comment_rec );
    }


    /**
     * get all comments for a specified piece of content
     * @param $content_id
     * @param int $parent_id
     *
     * @return array
     */
    function get_for_content( $content_id, $parent_id = 0 ) {

        $comment_list = null;
        $query = "SELECT * " .
                   "FROM " . $this->table . " " .
                  "WHERE " . $this->query_field( 'content_id' ) . "= '" . $this->gCI->db->escape_string( $content_id ) . "'" .
                    "AND " . $this->query_field( 'parent_id' ) . "= '" . $this->gCI->db->escape_string( $parent_id ) . "'" .
               "ORDER BY " . $this->query_field( 'time' ) . " ASC ";

        $result = $this->gCI->db->query($query);
        if ( !empty( $result ) && ( $this->gCI->db->num_rows( $result ) != "0" ) ) {

            $comment_list = array();

            while ( $comment_row = $this->gCI->db->fetch_assoc( $result ) ) {
                $comment_row = $this->strip_prefix( $comment_row );

                // Load in any replies to this comment
                $comment_row['replies'] = $this->get_for_content( $content_id, $comment_row['id'] );
                $comment_list[] = $comment_row;
            }
        }

        return( $comment_list );
    }
}
