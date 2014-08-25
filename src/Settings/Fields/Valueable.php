<?php namespace GM\FCPCH\Settings\Fields;

trait Valueable {

    public function getValue() {
        return get_option( $this->getId() ) ? : $this->getDefault();
    }

}