<?php namespace GM\FCPCH\Settings\Fields;

trait Defaultable {

    public function getDefault() {
        return isset( $this->default ) ? $this->default : NULL;
    }

}