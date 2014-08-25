<?php namespace GM\FCPCH\Settings;

class PostContent implements SectionInterface {

    use Fieldable;

    function title() {
        return esc_html__( 'Fragment Cache Post Content', 'fcpch' );
    }

    function subTitle() {
        printf( '<p>%s <a href="https://github.com/Rarst/fragment-cache" target="_blank">%s</a></p>', esc_html__( 'Settings for Post Content Handler of', 'fcpch' ), 'Fragment Cache' );
    }

    public function getId() {
        return 'fcpch_post_content';
    }

}