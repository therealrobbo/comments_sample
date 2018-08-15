$(document).ready(function() {


    var comment_replies = null;


    function move_comment_form( container, parent_id ) {
        comment_form.detach();
        comment_form.appendTo( container );
        comment_form.find( 'input[name=parent_id]' ).val( parent_id );
    }

    /**
     * Bind reply links to special reply logic.
     */
    function bind_replies( ) {

        comment_replies = $( 'a.comment_reply' );

        comment_replies.click( function( e ) {
            e.preventDefault();

            let reply_parent = $( this ).closest( '.comment_item' );
            let parent_id    = reply_parent.attr( 'data-id' );

            // Move the comment form from it's current place, to just below this comment
            move_comment_form( reply_parent, parent_id );

            // Show the new comment button so the user can make a comment that is not a reply later.
            comment_new.show();
        });

    }


    /**
     * Render the JSON comment thread as HTML
     *
     * @param comment_thread
     * @param level
     * @returns {string}
     */
    function render_comment_html( comment_thread, level ) {

        let comment_html = '<l1>';
        $.each( comment_thread, function( index, comment_info ) {

            comment_html +=
                '<div class="comment_item" data-id="' + comment_info.id + '">' +
                    '<div class="comment_info">' +
                        '<span class="author">' + comment_info.name + '</span>' +
                        ' @ ' +
                        '<span class="timestamp">' + comment_info.time + '</span>' +
                    '</div>' +
                    '<div class="comment_body">' + comment_info.body + '</div>';

            // Render reply link, if permitted
            if ( level < 3) {
                comment_html += '<a href="#" class="comment_reply">Reply</a>';
            }

            if ( comment_info.replies != null ) {
                comment_html += render_comment_html( comment_info.replies, level + 1 )
            }
            comment_html += '</div>';
        } );

        comment_html += '</li>';
        return( comment_html );
    }


    /**
     * Render JSON comments into the DOM
     *
     * @param comment_thread
     */
    function render_comments( comment_thread ) {

        // Get the HTML for the comment thread
        let comment_html = render_comment_html( comment_thread, 0 );

        // Place it in the comment list div
        comment_list.html( '' );
        comment_list.append( comment_html );

        // Bind the actions reply links
        bind_replies( );
    }


    /**
     * Fetch comments from the comment server
     */
    function fetch_comments() {
        $.ajax({
            type: "GET",
            url: '/api/get/' + content_id,
            success: function( data ) {

                if ( data.result == 'comments' ) {

                    render_comments( data.comments );
                    comment_list.show();
                }else if ( data.result == 'new' ) {
                    comment_list.hide();
                }
            },
            dataType: 'json'
        });
    }


    //----------------------------------------------------------------------------------------------------------------
    //  Bootstrap the comment system...
    //----------------------------------------------------------------------------------------------------------------
    var comment_container = $( '#comment_block' );

    // Does the page have a comment container?
    if ( comment_container.length ) {

        // Does the comment container have a content ID?
        var content_id   = comment_container.attr( 'data-content-id' );
        if ( ( typeof content_id !== typeof undefined ) && ( content_id > 0 ) ) {

            // We seem to have a valid comment container, so vars bootrap in the rest of it

            //----------------------------
            //  Bootstrap the comment list
            //----------------------------
            // If the comment list isn't present...
            var comment_list = comment_container.find( '#comment_list' );
            if ( !comment_list.length ) {
                //...create the comment list
                comment_container.append( '<div id="comment_list"></div>' );
                comment_list = comment_container.find( '#comment_list' )
            }

            // Load any existing comments from the system
            fetch_comments();

            //--------------------------------
            //  Bootstrap the New Comment Link
            //--------------------------------
            var comment_new = comment_container.find( '#comment_new' );
            if ( !comment_new.length ) {
                comment_container.append( '<a href="#" id="comment_new">New Comment</a>' );
                comment_new = comment_container.find( '#comment_new' );
            }
            function new_comment_form(  ) {
                // Move the comment form to the bottom of the comment container
                move_comment_form( comment_container, "0" )

                // Now hide the comment new button
                comment_new.hide();
            }
            comment_new.click( function( e ) {
                e.preventDefault();

                new_comment_form(  );
            });

            //----------------------------
            //  Bootstrap the comment Form
            //----------------------------
            // If the comment form isn't present...
            var comment_form = comment_container.find( 'form' );
            if ( !comment_form.length ) {

                // ...Create the comment form
                var form_code =
                    '<form class="comment_form">' +
                        '<input type="hidden" name="content_id" value="' + content_id + '" >' +
                        '<input type="hidden" name="parent_id" value="0" >' +
                        '<div class="form-group">' +
                            '<label for="name">Name</label>' +
                            '<input type="name" name="name" class="form-control" id="name" placeholder="Enter your name">' +
                        '</div>' +
                        '<div class="form-group">' +
                            '<label for="body">Comment:</label>' +
                            '<textarea class="form-control" id="body" name="body" placeholder="Enter your comment"></textarea>' +
                        '</div>' +
                        '<button type="submit" class="btn btn-default">Submit</button>' +
                        '<div id="comment_message"></div>'
                    '</form>';

                comment_container.append( form_code );
                comment_form = comment_container.find( 'form' );
            }
            var comment_message = comment_form.find( '#comment_message');


            /**
             * Deal with comment submissions
             */
            comment_form.submit( function( e ) {
                e.preventDefault();

                var form_data = comment_form.serialize( );

                $.ajax({
                    type: "POST",
                    url: '/api/add/',
                    data: form_data,
                    success: function( data ) {

                        if ( data.result == 'success' ) {

                            // The results were good!

                            // Lets move the comment form back to the bottom and clear it
                            new_comment_form(  );
                            comment_form.find( 'input[name=name]' ).val('');
                            comment_form.find( 'textarea[name=body]' ).val('');

                            // Now lets render the updated comments
                            render_comments( data.info );

                        } else {
                            if( data.result == 'invalid_banned_words' ) {
                                comment_message.html( 'Please do not use the word "' + data.info + '" in your comments.' );
                            } else if ( data.result == 'invalid_id' ) {
                                comment_message.html( 'There is something wrong with this comment form.' );
                            } else if ( data.result == 'invalid_name' ) {
                                comment_message.html( 'You must enter your name before submitting a comment.' );
                            } else if ( data.result == 'invalid_body' ) {
                                comment_message.html( 'You must enter a comment before clicking submit' );
                            }
                            comment_message.addClass( 'comment_invalid' );
                            comment_message.show();
                        }
                    },
                    dataType: 'json'
                });
            });


        }
    }
});