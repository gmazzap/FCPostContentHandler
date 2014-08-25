<?php namespace GM\FCPCH\Settings\Fields;

interface FieldInterface {

    public function getId();

    public function getTitle();

    public function field();

    public function sanitize( $data );

    public function getDefault();

    public function getValue();
}