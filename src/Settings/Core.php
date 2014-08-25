<?php namespace GM\FCPCH\Settings;

class Core implements SectionInterface {

    use Fieldable;

    public function getId() {
        return 'fcpch_core';
    }

    public function title() {
        return esc_html__( 'Fragment Cache Plugin', 'fcpch' );
    }

    public function subTitle() {
        printf( '<p>%s <a href="https://github.com/Rarst/fragment-cache" target="_blank">%s</a></p>', esc_html__( 'Settings for core handlers of', 'fcpch' ), 'Fragment Cache' );
    }

}