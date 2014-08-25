<?php namespace GM\FCPCH;

class Metabox {

    const NONCE = 'fcpch_nonce';

    const NAME = '_fcph_to_cache';

    private $capability;

    function __construct( Settings\Fields\Capability $capability ) {
        $this->capability = $capability;
    }

    function display( $post ) {
        if ( ! current_user_can( $this->capability->getValue() ) || ! $post instanceof \WP_Post ) {
            return;
        }
        if ( ! empty( $post->post_password ) ) {
            echo '<p>';
            esc_html_e( 'Can\'t cache password protected posts.', 'fcpch' );
            echo '</p>';
        } else {
            wp_nonce_field( $this->nonce( $post ), self::NONCE );
            $value = (int) get_post_meta( $post->ID, self::NAME, TRUE );
            echo '<label for="' . self::NAME . '">';
            printf( '<input id="%1$s" name="%1$s" type="checkbox" value="1"%2$s/> ', self::NAME, checked( 1, $value, FALSE ) );
            esc_html_e( 'Fragment cache content?', 'fcpch' );
            echo '</label>';
            echo '<p>';
            esc_html_e( 'Please check only if post content contain resource intensive and/or time consuming shortcodes.', 'fcpch' );
            echo '</p>';
            echo '<p>' . esc_html__( 'Do NOT check if:', 'fcpch' ) . '</p>';
            echo '<ul style="list-style:outside;padding-left:inherit;">';
            echo '<li>' . esc_html__( 'there are no shortcodes in content', 'fcpch' ) . '</li>';
            echo '<li>' . esc_html__( 'shortcodes output user-specific content for logged users', 'fcpch' ) . '</li>';
            echo '<li>' . esc_html__( 'shortcodes output random content that you need to keep randomly changing', 'fcpch' ) . '</li>';
            echo '</ul>';
        }
    }

    function save( $post_id, $post ) {
        if (
            ! current_user_can( $this->capability->getValue() )
            || current_filter() !== 'save_post'
        ) {
            return;
        }
        $cap = $post->post_type === 'page' ? 'edit_page' : 'edit_post';
        $nonce = filter_input( INPUT_POST, self::NONCE, FILTER_SANITIZE_STRING );
        if (
            ! current_user_can( $cap, $post_id )
            || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            || ! (bool) wp_verify_nonce( $nonce, $this->nonce( $post ) )
        ) {
            return;
        }
        $value = (int) filter_input( INPUT_POST, self::NAME, FILTER_SANITIZE_NUMBER_INT );
        if ( $value <= 0 || ! empty( $post->post_password ) ) {
            delete_post_meta( $post_id, self::NAME );
        } else {
            update_post_meta( $post_id, self::NAME, '1' );
        }
    }

    private function nonce( \WP_Post $post ) {
        return self::NONCE . '_' . get_current_blog_id() . '_' . $post->ID;
    }

}