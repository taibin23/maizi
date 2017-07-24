<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package qiaomi
 */

if ( ! function_exists( 'qiaomi_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
    function qiaomi_posted_on() {
        $posted_on = '<time class="published" datetime="%1$s">%2$s</time>';
        $posted_on = sprintf( $posted_on, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ));
        //$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $posted_on . '</a>';
        echo '<span class="posted-date meta-item mr-3"><i class="icon-time icon12"></i> ' . $posted_on . '</span>';

        // Display author name
        $display_author = get_theme_mod( 'qiaomi_display_author', 'no' );
        if ($display_author === 'yes') {
            $byline = sprintf(
                '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
            );
            echo '<span class="byline meta-item mr-3"><i class="icon-account icon12"></i> ' . $byline . '</span>';
        }

        // Hide category and tag text for pages.
        if ( 'post' === get_post_type() ) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list( esc_html__( ', ', 'qiaomi' ) );
            if ( $categories_list && qiaomi_categorized_blog() ) {
                printf( '<span class="cat-links meta-item mr-3"><i class="icon-category icon12"></i> ' . esc_html__( 'Posted in %1$s', 'qiaomi' ) . '</span>', $categories_list ); // WPCS: XSS OK.
            }
        }

        if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link meta-item mr-3"><i class="icon-comment icon12"></i> ';
            comments_popup_link( esc_html__( 'Leave a comment', 'qiaomi' ), esc_html__( '1 Comment', 'qiaomi' ), esc_html__( '% Comments', 'qiaomi' ) );
            echo '</span>';
        }

        edit_post_link(
            __( 'Edit', 'qiaomi' ),

            '<span class="edit-link meta-item mr-3"><i class="icon-edit icon12"></i> ',
            '</span>'
        );
    }
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function qiaomi_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'qiaomi_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );
		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'qiaomi_categories', $all_the_cool_cats );
	}
	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so components_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so components_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in qiaomi_categorized_blog.
 */
function qiaomi_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'qiaomi_categories' );
}
add_action( 'edit_category', 'qiaomi_category_transient_flusher' );
add_action( 'save_post',     'qiaomi_category_transient_flusher' );

function custom_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

function custom_excerpt_length( $length ) {
    return 120;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );