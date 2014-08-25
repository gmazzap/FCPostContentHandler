<?php namespace GM\FCPCH\Settings\Fields;

class PostTypes implements FieldInterface {

    use Defaultable,
        Valueable;

    private $default = [ 'page', 'post' ];

    public function field() {
        echo '<p>' . esc_html__( 'Choose for which post types Post Content Handler of Fragment Cache should be available:', 'fcpch' ) . '</p>';
        $selected = (array) $this->getValue();
        echo '<fieldset>';
        echo '<label for="' . $this->getId() . '">';
        foreach ( get_post_types( [ 'public' => TRUE ], 'objects' ) as $type => $ptobj ) {
            if ( $type === 'attachment' ) {
                continue;
            }
            $f = '<input name="' . $this->getId() . '[]" id="' . $this->getId() . '_%1$s" value="%1$s"%2$s type="checkbox"> %3$s<br>';
            $checked = in_array( $type, $selected, TRUE ) ? ' checked="checked"' : '';
            printf( $f, $type, $checked, $ptobj->labels->name );
        }
        echo '</label></fieldset>';
    }

    public function getId() {
        return 'fcph_post_types';
    }

    public function getTitle() {
        return esc_html__( 'Enable for post types', 'fcpch' );
    }

    public function sanitize( $data ) {
        foreach ( (array) $data as $i => $post_type ) {
            $post_type = filter_var( $post_type, FILTER_SANITIZE_STRING );
            if ( ! post_type_exists( $post_type ) ) {
                unset( $data[$i] );
            }
        }
        return array_unique( (array) $data );
    }

}