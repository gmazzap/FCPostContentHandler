<?php namespace GM\FCPCH;

if ( ! function_exists( 'is_admin' ) || ! is_admin() ) {
    return;
}

add_action( 'admin_menu', function() {
    if ( FRAGMENT_CACHE_WORKS ) {
        $title = esc_html__( 'Fragment Cache', 'fcpch' );
        add_options_page( $title, $title, 'manage_options', 'fcpch', [ fcpch( 'settings' ), 'page' ] );
    }
} );

add_action( 'admin_init', function() {
    if ( FRAGMENT_CACHE_WORKS ) {
        if ( defined( 'FRAGMENT_CACHE_NOT_INSTALLED' ) && FRAGMENT_CACHE_NOT_INSTALLED ) {
            fcpch( 'settings' )->addSection(
                fcpch( 'set_section_core' )->addField( fcpch( 'set_field_coreallow' ) )
            );
        }
        fcpch( 'settings' )->addSection(
            fcpch( 'set_section_postcontent' )
                ->addField( fcpch( 'set_field_posttypes' ) )
                ->addField( fcpch( 'set_field_capability' ) )
        );
    }
} );

add_action( "add_meta_boxes", function( $type ) {
    if ( FRAGMENT_CACHE_WORKS ) {
        $cap = get_option( 'fcph_min_cap' ) ? : 'edit_others_posts';
        $enabled = get_option( 'fcph_post_types' );
        if ( current_user_can( $cap ) && in_array( $type, $enabled, TRUE ) ) {
            $title = esc_html__( 'Fragment Cache Post Content', 'fcpch' );
            add_meta_box( Metabox::NAME, $title, [ fcpch( 'metabox' ), 'display' ], $type, 'side', 'high' );
        }
    }
}, 0 );

add_action( 'save_post', function( $post_id, $post ) {
    if ( FRAGMENT_CACHE_WORKS ) {
        $cap = get_option( 'fcph_min_cap' ) ? : 'edit_others_posts';
        $enabled = get_option( 'fcph_post_types' );
        if ( current_user_can( $cap ) && in_array( $post->post_type, $enabled, TRUE ) ) {
            fcpch( 'metabox' )->save( $post_id, $post );
        }
    }
}, 10, 2 );
