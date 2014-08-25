<?php namespace GM\FCPCH\Settings\Fields;

class Capability implements FieldInterface {

    use Defaultable,
        Valueable;

    private $default = 'edit_others_posts';

    public function field() {
        echo '<p>';
        esc_html_e( 'Choose the capability users must have to see metabox in post edit screen.', 'fcpch' );
        echo ' ';
        printf( esc_html__( 'Check valid capabilities %shere%s', 'fcpch' ), '<a href="http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table" target="_blank">', '</a>' );
        echo '</p>';
        $value = esc_attr( $this->getValue() );
        printf( '<input name="' . $this->getId() . '" id="' . $this->getId() . '" value="%s" type="text">', $value );
    }

    public function getId() {
        return 'fcph_min_cap';
    }

    public function getTitle() {
        return esc_html__( 'Required capability', 'fcpch' );
    }

    public function sanitize( $data ) {
        $cap = filter_var( $data, FILTER_SANITIZE_STRING );
        $admin = get_role( 'administrator' );
        if ( ! in_array( $cap, array_keys( array_filter( $admin->capabilities ) ), TRUE ) ) {
            $cap = get_option( $this->getId() ) ? : $this->default;
        }
        return $cap;
    }

}