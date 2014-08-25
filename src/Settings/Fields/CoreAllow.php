<?php namespace GM\FCPCH\Settings\Fields;

class CoreAllow implements FieldInterface {

    use Defaultable,
        Valueable;

    private $default = [ ];

    public function field() {
        echo '<p>' . esc_html__( 'Fragment Cache plugin is not installed, but you can enable here its handlers. Choose which:', 'fcpch' ) . '</p>';
        $selected = (array) $this->getValue();
        echo '<fieldset>';
        echo '<label for="' . $this->getId() . '">';
        foreach ( [ 'menu', 'widget', 'gallery' ] as $handler ) {
            $f = '<input name="' . $this->getId() . '[]" id="' . $this->getId() . '_%1$s" value="%1$s"%2$s type="checkbox"> %3$s<br>';
            $checked = in_array( $handler, $selected, TRUE ) ? ' checked="checked"' : '';
            printf( $f, $handler, $checked, ucwords( $handler ) );
        }
        echo '</label></fieldset>';
    }

    public function sanitize( $data ) {
        $available = [ 'menu', 'widget', 'gallery' ];
        foreach ( (array) $data as $i => $handler ) {
            $handler = filter_var( $handler, FILTER_SANITIZE_STRING );
            if ( ! in_array( $handler, $available, TRUE ) ) {
                unset( $data[$i] );
            }
        }
        return array_unique( (array) $data );
    }

    public function getId() {
        return 'fcph_core_allow';
    }

    public function getTitle() {
        return esc_html__( 'Fragment Cache Handlers', 'fcpch' );
    }

}