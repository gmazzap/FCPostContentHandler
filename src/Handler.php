<?php namespace GM\FCPCH;

class Handler extends \Rarst\Fragment_Cache\Fragment_Cache {

    private $original_filters;

    protected function callback( $name, $args ) {
        setup_postdata( $args['post'] );
        ob_start();
        the_content();
        $output = ob_get_clean();
        wp_reset_postdata();
        return $output . PHP_EOL . $this->get_comment( $name );
    }

    public function disable() {
        remove_action( 'pre_get_posts', [ $this, 'checkQuery' ] );
        remove_action( 'the_post', [ $this, 'resetFilters' ] );
        if ( has_action( 'loop_start', [ $this, 'onLoopStart' ] ) ) {
            remove_action( 'loop_start', [ $this, 'onLoopStart' ] );
            remove_action( 'loop_end', [ $this, 'resetFilters' ] );
        }
    }

    public function enable() {
        add_action( 'pre_get_posts', [ $this, 'checkQuery' ] );
        add_action( 'the_post', [ $this, 'resetFilters' ] );
    }

    public function checkQuery( \WP_Query $query ) {
        if ( ! is_admin() && $query->is_main_query() && $query->is_singular ) {
            add_action( 'loop_start', [ $this, 'onLoopStart' ], PHP_INT_MAX );
            add_action( 'loop_end', [ $this, 'resetFilters' ], 0 );
        } else {
            $this->flush( $query );
        }
    }

    public function onLoopStart( \WP_Query $query ) {
        if ( is_admin() || ! $query->is_main_query() || ! $query->is_singular ) {
            return;
        }
        $post = $query->posts[0];
        $enabled = get_option( 'fcph_post_types' );
        if (
            ! empty( $post->post_password ) // can't cache password protected posts
            || empty( $enabled )
            || ! in_array( $post->post_type, $enabled, TRUE )
        ) {
            $this->flush( $query );
            return;
        }
        $to_cache = get_post_meta( $post->ID, Metabox::NAME );
        if ( (int) $to_cache > 0 ) {
            $name = "fcpch-{$post->ID}";
            $salt = $post->post_modified;
            $args = [ 'post' => $post ];
            $query->posts[0]->fcpch_cached = TRUE;
            $query->posts[0]->post_content = $this->fetch( $name, $args, $salt );
            if ( isset( $GLOBALS['wp_filter']['the_content'] ) ) {
                $this->original_filters = $GLOBALS['wp_filter']['the_content'];
                unset( $GLOBALS['wp_filter']['the_content'] );
            }
        }
    }

    public function resetFilters() {
        if ( empty( $this->original_filters ) ) {
            return;
        }
        if ( current_filter() === 'the_post' ) {
            $post = func_get_arg( 0 );
            if ( isset( $post->fcpch_cached ) && $post->fcpch_cached ) {
                return;
            }
        }
        $GLOBALS['wp_filter']['the_content'] = $this->original_filters;
        $this->original_filters = NULL;
    }

    private function flush( \WP_Query $query ) {
        $this->resetFilters();
        if ( did_action( 'wp' ) || $query->is_main_query() ) {
            $this->disable();
        }
    }

}