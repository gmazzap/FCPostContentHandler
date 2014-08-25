<?php namespace GM\FCPCH\Settings;

class Settings {

    const PAGE = 'fcpch';

    function page() {
        echo '<div class="wrap">';
        echo '<h2>' . esc_html__( 'Fragment Cache', 'fcpch' ) . '</h2>';
        echo '<form method="post" action="options.php">';
        settings_fields( self::PAGE );
        do_settings_sections( self::PAGE );
        submit_button();
        echo '</form></div>';
    }

    function addSection( SectionInterface $section ) {
        add_settings_section( $section->getId(), $section->title(), [ $section, 'subTitle' ], self::PAGE );
        foreach ( $section->getFields() as $field ) {
            add_settings_field( $field->getId(), $field->getTitle(), [ $field, 'field' ], self::PAGE, $section->getId() );
            register_setting( self::PAGE, $field->getId(), [ $field, 'sanitize' ] );
        }
        return $this;
    }

}