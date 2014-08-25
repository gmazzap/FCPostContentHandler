<?php namespace GM\FCPCH\Settings;

trait Fieldable {

    private $fields = [ ];

    public function addField( Fields\FieldInterface $field ) {
        $this->fields[$field->getId()] = $field;
        return $this;
    }

    public function getFields() {
        return $this->fields;
    }

    public function getField( $id ) {
        return isset( $this->fields[$id] ) ? $this->fields[$id] : NULL;
    }

}